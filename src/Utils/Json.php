<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;


/**
 * JSON encoder and decoder.
 *
 * @author     David Grudl
 */
class Json
{
	const FORCE_ARRAY = 1;
	const PRETTY = 2;

	/** @var array */
	private static $messages = array(
		JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
		JSON_ERROR_STATE_MISMATCH => 'Syntax error, malformed JSON',
		JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
		JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
		5 /*JSON_ERROR_UTF8*/ => 'Invalid UTF-8 sequence', // exists since 5.3.3, but is returned since 5.3.1
	);


	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new Nette\StaticClassException;
	}


	/**
	 * Returns the JSON representation of a value.
	 * @param  mixed
	 * @param  int  accepts Json::PRETTY
	 * @return string
	 */
	public static function encode($value, $options = 0)
	{
		// workaround for detecting recursion, encoding INF, NAN or resource (5.3.x and 5.4.x) and suppressing invalid UTF-8 sequence warning (5.3.14 only)
		if (PHP_VERSION_ID < 50500) {
			set_error_handler(function($severity, $message) {
				restore_error_handler();
				throw new JsonException($message);
			});
		}

		$options = PHP_VERSION_ID >= 50400 ? (JSON_UNESCAPED_UNICODE | ($options & self::PRETTY ? JSON_PRETTY_PRINT : 0)) : 0;
		$json = json_encode($value, $options);
		$error = json_last_error();

		if (PHP_VERSION_ID < 50500) {
			restore_error_handler();
		}

		if ($error === JSON_ERROR_NONE) {
			return str_replace(array("\xe2\x80\xa8", "\xe2\x80\xa9"), array('\u2028', '\u2029'), $json);
		}

		// workaround for unavailable json_last_error_msg()
		if (PHP_VERSION_ID < 50500) {
			$message = (isset(static::$messages[$error]) ? static::$messages[$error] : 'Unknown error');
		} else {
			$message = json_last_error_msg();
		}

		throw new JsonException($message, $error);
	}


	/**
	 * Decodes a JSON string.
	 * @param  string
	 * @param  int  accepts Json::FORCE_ARRAY
	 * @return mixed
	 */
	public static function decode($json, $options = 0)
	{
		$json = (string) $json;
		$forceArray = (bool) ($options & self::FORCE_ARRAY);
		$args = array($json, $forceArray, 512);

		// workaround for PHP < 5.3.3 (bug #52262) & PECL JSON-C (https://github.com/json-c/json-c/issues/122)
		if ((PHP_VERSION_ID < 50303 || defined('JSON_C_VERSION')) && !preg_match('##u', $json)) {
			throw new JsonException('Invalid UTF-8 sequence', 5);
		}

		// workaround for fatal error when object key starts with \u0000
		if (/*PHP_VERSION_ID < 50600 &&*/ !$forceArray && preg_match('#[^\\\\]"\\\\u0000(?:[^"\\\\]|\\\\.)*+"\s*+:#', $json)) {
			throw new JsonException(static::$messages[JSON_ERROR_CTRL_CHAR], JSON_ERROR_CTRL_CHAR);
		}

		// workaround for unavailable JSON_BIGINT_AS_STRING (not implemented in PECL JSON-C 1.3.2 for 64bit systems)
		if (PHP_VERSION_ID >= 50400 && (!defined('JSON_C_VERSION') || PHP_INT_SIZE === 4)) {
			$args[] = JSON_BIGINT_AS_STRING;
		}

		$value = call_user_func_array('json_decode', $args);
		$error = json_last_error();

		// workaround for incorrect error code in PHP < 5.3.6 (bug #53963)
		if (PHP_VERSION_ID < 50306 && $error === JSON_ERROR_NONE && $value === NULL && $json !== '' && strcasecmp(trim($json), 'null')) {
			$error = JSON_ERROR_SYNTAX;
		}

		// workaround for '' not clearing json_last_error in PHP < 5.3.7 (bug #54484)
		if (PHP_VERSION_ID < 50307 && $json === '') {
			$error = JSON_ERROR_NONE;
		}

		if ($error === JSON_ERROR_NONE) {
			return $value;
		}

		// workaround for unavailable json_last_error_msg() in PHP < 5.5.0
		if (PHP_VERSION_ID < 50500) {
			$message = (isset(static::$messages[$error]) ? static::$messages[$error] : 'Unknown error');
		} else {
			$message = json_last_error_msg();
		}

		throw new JsonException($message, $error);
	}

}


/**
 * The exception that indicates error of JSON encoding/decoding.
 */
class JsonException extends \Exception
{
}
