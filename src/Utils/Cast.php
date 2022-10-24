<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;
use TypeError;


/**
 * Converts variables in a similar way to implicit casting in PHP in strict types mode.
 */
final class Cast
{
	use Nette\StaticClass;

	public static function bool(mixed $value): bool
	{
		return match (true) {
			is_bool($value) => $value,
			is_int($value) => $value !== 0,
			is_float($value) => $value !== 0.0,
			is_string($value) => $value !== '' && $value !== '0',
			default => throw new TypeError('Cannot cast ' . get_debug_type($value) . ' to bool.'),
		};
	}


	public static function int(mixed $value): int
	{
		return match (true) {
			is_bool($value) => (int) $value,
			is_int($value) => $value,
			is_float($value) => $value === (float) ($tmp = (int) $value)
				? $tmp
				: throw new TypeError('Cannot cast ' . self::string($value) . ' to int.'),
			is_string($value) => preg_match('~^-?\d+(\.0*)?$~D', $value)
				? (int) $value
				: throw new TypeError("Cannot cast '$value' to int."),
			default => throw new TypeError('Cannot cast ' . get_debug_type($value) . ' to int.'),
		};
	}


	public static function float(mixed $value): float
	{
		return match (true) {
			is_bool($value) => $value ? 1.0 : 0.0,
			is_int($value) => (float) $value,
			is_float($value) => $value,
			is_string($value) => preg_match('~^-?\d+(\.\d*)?$~D', $value)
				? (float) $value
				: throw new TypeError("Cannot cast '$value' to float."),
			default => throw new TypeError('Cannot cast ' . get_debug_type($value) . ' to float.'),
		};
	}


	public static function string(mixed $value): string
	{
		return match (true) {
			is_bool($value) => $value ? '1' : '0',
			is_int($value) => (string) $value,
			is_float($value) => str_contains($tmp = (string) $value, '.') ? $tmp : $tmp . '.0',
			is_string($value) => $value,
			default => throw new TypeError('Cannot cast ' . get_debug_type($value) . ' to string.'),
		};
	}


	public static function boolOrNull(mixed $value): ?bool
	{
		return $value === null ? null : self::bool($value);
	}


	public static function intOrNull(mixed $value): ?int
	{
		return $value === null ? null : self::int($value);
	}


	public static function floatOrNull(mixed $value): ?float
	{
		return $value === null ? null : self::float($value);
	}


	public static function stringOrNull(mixed $value): ?string
	{
		return $value === null ? null : self::string($value);
	}
}
