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

	public const FORCE_ARRAY = JSON_OBJECT_AS_ARRAY;
	public const PRETTY = JSON_PRETTY_PRINT;
	public const ESCAPE_UNICODE = 1 << 19;


	/**
	 * Converts value to JSON format. Use $pretty for easier reading and clarity and $escapeUnicode for ASCII output.
	 * @throws JsonException
	 */
	public static function encode(
		mixed $value,
		bool|int $pretty = false,
		bool $escapeUnicode = false,
	): string {
		$flags = 0;
		if (is_int($pretty)) { // back compatibility
			$escapeUnicode = $pretty & self::ESCAPE_UNICODE;
			$flags = $pretty & ~self::ESCAPE_UNICODE;
			$pretty = false;
		}

		$flags |= ($escapeUnicode ? 0 : JSON_UNESCAPED_UNICODE)
			| ($pretty ? JSON_PRETTY_PRINT : 0)
			| JSON_UNESCAPED_SLASHES
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
		$flags = is_int($forceArray) // back compatibility
			? $forceArray
			: ($forceArray ? JSON_OBJECT_AS_ARRAY : 0);

		$value = json_decode($json, null, 512, $flags | JSON_BIGINT_AS_STRING);
		if ($error = json_last_error()) {
			throw new JsonException(json_last_error_msg(), $error);
		}

		return $value;
	}


	/**
	 * Converts given JSON file to PHP value.
	 * @throws JsonException
	 */
	public static function decodeFile(string $file, bool|int $forceArray = false): mixed
	{
		if (!is_file($file)) {
			throw new Nette\IOException("File '$file' does not exist.");
		}

		$input = file_get_contents($file);
		return self::decode($input, $forceArray);
	}
}
