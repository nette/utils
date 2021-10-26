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

	/** @deprecated */
	public const FORCE_ARRAY = 0b0001;

	/** @deprecated */
	public const PRETTY = 0b0010;

	/** @deprecated */
	public const ESCAPE_UNICODE = 0b0100;


	/**
	 * Converts value to JSON format. Use $pretty for easier reading and clarity and $escapeUnicode for ASCII output.
	 * @throws JsonException
	 */
	public static function encode(
		mixed $value,
		bool|int $pretty = false,
		bool $escapeUnicode = false,
	): string {
		if (is_int($pretty)) { // back compatibility
			$escapeUnicode = $pretty & self::ESCAPE_UNICODE;
			$pretty &= self::PRETTY;
		}
		$flags = ($escapeUnicode ? 0 : JSON_UNESCAPED_UNICODE)
			| JSON_UNESCAPED_SLASHES
			| ($pretty ? JSON_PRETTY_PRINT : 0)
			| (defined('JSON_PRESERVE_ZERO_FRACTION') ? JSON_PRESERVE_ZERO_FRACTION : 0); // since PHP 5.6.6 & PECL JSON-C 1.3.7

		$json = json_encode($value, $flags);
		if ($error = json_last_error()) {
			throw new JsonException(json_last_error_msg(), $error);
		}
		return $json;
	}


	/**
	 * Parses JSON to PHP value. Parameter $forceArray forces an array instead of an object as the return value.
	 * @throws JsonException
	 */
	public static function decode(string $json, bool|int $forceArray = false): mixed
	{
		$value = json_decode($json, (bool) $forceArray, 512, JSON_BIGINT_AS_STRING);
		if ($error = json_last_error()) {
			throw new JsonException(json_last_error_msg(), $error);
		}
		return $value;
	}
}
