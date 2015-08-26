<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;


/**
 * JSON encoder and decoder.
 */
class Json
{
	const FORCE_ARRAY = 0b0001;
	const PRETTY = 0b0010;

	/** @var array */
	private static $messages = [
		JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
		JSON_ERROR_STATE_MISMATCH => 'Syntax error, malformed JSON',
		JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
		JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
		JSON_ERROR_UTF8 => 'Invalid UTF-8 sequence',
	];


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
		$flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | ($options & self::PRETTY ? JSON_PRETTY_PRINT : 0);

		$json = json_encode($value, $flags);

		if ($error = json_last_error()) {
			$message = isset(static::$messages[$error]) ? static::$messages[$error] : json_last_error_msg();
			throw new JsonException($message, $error);
		}

		$json = str_replace(["\xe2\x80\xa8", "\xe2\x80\xa9"], ['\u2028', '\u2029'], $json);
		return $json;
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
		if (defined('JSON_C_VERSION') && !preg_match('##u', $json)) {
			throw new JsonException('Invalid UTF-8 sequence', 5);
		}

		$forceArray = (bool) ($options & self::FORCE_ARRAY);
		if (!$forceArray && preg_match('#(?<=[^\\\\]")\\\\u0000(?:[^"\\\\]|\\\\.)*+"\s*+:#', $json)) { // workaround for json_decode fatal error when object key starts with \u0000
			throw new JsonException(static::$messages[JSON_ERROR_CTRL_CHAR]);
		}
		$args = [$json, $forceArray, 512];
		if (!defined('JSON_C_VERSION') || PHP_INT_SIZE === 4) { // not implemented in PECL JSON-C 1.3.2 for 64bit systems
			$args[] = JSON_BIGINT_AS_STRING;
		}
		$value = call_user_func_array('json_decode', $args);

		if ($value === NULL && $json !== '' && strcasecmp(trim($json, " \t\n\r"), 'null') !== 0) { // '' is not clearing json_last_error
			$error = json_last_error();
			throw new JsonException(isset(static::$messages[$error]) ? static::$messages[$error] : 'Unknown error', $error);
		}
		return $value;
	}

}
