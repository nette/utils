<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types = 1);

namespace Nette\Utils;

use Nette;


/**
 * JSON encoder and decoder.
 */
class Json
{
	const FORCE_ARRAY = 0b0001;
	const PRETTY = 0b0010;


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
	public static function encode($value, int $options = 0): string
	{
		$flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | ($options & self::PRETTY ? JSON_PRETTY_PRINT : 0);

		$json = json_encode($value, $flags);
		if ($error = json_last_error()) {
			throw new JsonException(json_last_error_msg(), $error);
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
	public static function decode(string $json, int $options = 0)
	{
		$json = (string) $json;
		if (defined('JSON_C_VERSION') && !preg_match('##u', $json)) {
			throw new JsonException('Invalid UTF-8 sequence', 5);
		} elseif ($json === '') { // for PHP < 7
			throw new JsonException('Syntax error');
		}

		$forceArray = (bool) ($options & self::FORCE_ARRAY);
		if (PHP_VERSION_ID < 70000 && !$forceArray && preg_match('#(?<=[^\\\\]")\\\\u0000(?:[^"\\\\]|\\\\.)*+"\s*+:#', $json)) {
			throw new JsonException('The decoded property name is invalid'); // workaround for json_decode fatal error when object key starts with \u0000
		}
		$flags = !defined('JSON_C_VERSION') || PHP_INT_SIZE === 4 ? JSON_BIGINT_AS_STRING : 0; // not implemented in PECL JSON-C 1.3.2 for 64bit systems

		$value = json_decode($json, $forceArray, 512, $flags);
		if ($error = json_last_error()) {
			throw new JsonException(json_last_error_msg(), $error);
		}
		return $value;
	}

}
