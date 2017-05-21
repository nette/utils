<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * JSON encoder and decoder.
 */
final class Json
{
	use Nette\StaticClass;

	const FORCE_ARRAY = 0b0001;
	const PRETTY = 0b0010;


	/**
	 * Returns the JSON representation of a value. Accepts flag Json::PRETTY.
	 */
	public static function encode($value, int $flags = 0): string
	{
		$flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			| ($flags & self::PRETTY ? JSON_PRETTY_PRINT : 0)
			| (defined('JSON_PRESERVE_ZERO_FRACTION') ? JSON_PRESERVE_ZERO_FRACTION : 0); // since PHP 5.6.6 & PECL JSON-C 1.3.7

		$json = json_encode($value, $flags);
		if ($error = json_last_error()) {
			throw new JsonException(json_last_error_msg(), $error);
		}

		if (PHP_VERSION_ID < 70100) {
			$json = str_replace(["\u{2028}", "\u{2029}"], ['\u2028', '\u2029'], $json);
		}

		return $json;
	}


	/**
	 * Decodes a JSON string. Accepts flag Json::FORCE_ARRAY.
	 * @return mixed
	 */
	public static function decode(string $json, int $flags = 0)
	{
		$forceArray = (bool) ($flags & self::FORCE_ARRAY);
		$value = json_decode($json, $forceArray, 512, JSON_BIGINT_AS_STRING);
		if ($error = json_last_error()) {
			throw new JsonException(json_last_error_msg(), $error);
		}

		return $value;
	}


	/**
	 * Safely decodes a JSON string.
	 * @param  string
	 * @param  int  accepts Json::FORCE_ARRAY
	 * @param  float
	 * @return mixed
	 */
	public static function decodeSafe($json, $options = 0, $timeLimit = 2.0)
	{
		$timeLimit += microtime(true);

		$json = preg_replace_callback('#"(?:\\\\.|[^"\\\\])*"#', function($m) use (&$salt) {
			if ($salt === null || !mt_rand(0, 100)) {
				$salt = Nette\Utils\Random::generate(3, 'a-zA-Z');
			}
			return '"' . $salt . substr($m[0], 1);
		}, $json);

		$value = static::decode($json, $options);

		$queue = array(& $value);
		while (list($key, $val) = each($queue)) {
			if (is_string($val)) {
				if (ctype_alpha($val[0])) {
					$queue[$key] = substr($val, 3);
				}
			} elseif (is_array($val)) {
				$iterations = 0;
				$cleaned = array();
				foreach ($val as $k => $v) {
					$k = substr($k, 3);
					$cleaned[$k] = $v;
					$queue[] = & $cleaned[$k];
					if (++$iterations % 1000 === 0 && microtime(true) > $timeLimit) {
						throw new JsonException('Time limit exceeded');
					}
				}
				$queue[$key] = $cleaned;
			} elseif (is_object($val)) {
				$iterations = 0;
				$cleaned = new \stdClass;
				foreach ((array) $val as $k => $v) {
					$k = substr($k, 3);
					if (substr($k, 0) === "\0") {
						throw new JsonException(static::$messages[JSON_ERROR_CTRL_CHAR]);
					}
					$cleaned->$k = $v;
					$queue[] = & $cleaned->$k;
					if (++$iterations % 1000 === 0 && microtime(true) > $timeLimit) {
						throw new JsonException('Time limit exceeded');
					}
				}
				$queue[$key] = $cleaned;
			}
		}
		return $value;
	}

}
