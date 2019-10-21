<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * Validation utilities.
 */
class Validators
{
	use Nette\StaticClass;

	protected static $validators = [
		// PHP types
		'array' => 'is_array',
		'bool' => 'is_bool',
		'boolean' => 'is_bool',
		'float' => 'is_float',
		'int' => 'is_int',
		'integer' => 'is_int',
		'null' => 'is_null',
		'object' => 'is_object',
		'resource' => 'is_resource',
		'scalar' => 'is_scalar',
		'string' => 'is_string',

		// pseudo-types
		'callable' => [__CLASS__, 'isCallable'],
		'iterable' => 'is_iterable',
		'list' => [Arrays::class, 'isList'],
		'mixed' => [__CLASS__, 'isMixed'],
		'none' => [__CLASS__, 'isNone'],
		'number' => [__CLASS__, 'isNumber'],
		'numeric' => [__CLASS__, 'isNumeric'],
		'numericint' => [__CLASS__, 'isNumericInt'],

		// string patterns
		'alnum' => 'ctype_alnum',
		'alpha' => 'ctype_alpha',
		'digit' => 'ctype_digit',
		'lower' => 'ctype_lower',
		'pattern' => null,
		'space' => 'ctype_space',
		'unicode' => [__CLASS__, 'isUnicode'],
		'upper' => 'ctype_upper',
		'xdigit' => 'ctype_xdigit',

		// syntax validation
		'email' => [__CLASS__, 'isEmail'],
		'identifier' => [__CLASS__, 'isPhpIdentifier'],
		'uri' => [__CLASS__, 'isUri'],
		'url' => [__CLASS__, 'isUrl'],

		// environment validation
		'class' => 'class_exists',
		'interface' => 'interface_exists',
		'directory' => 'is_dir',
		'file' => 'is_file',
		'type' => [__CLASS__, 'isType'],
	];

	protected static $counters = [
		'string' => 'strlen',
		'unicode' => [Strings::class, 'length'],
		'array' => 'count',
		'list' => 'count',
		'alnum' => 'strlen',
		'alpha' => 'strlen',
		'digit' => 'strlen',
		'lower' => 'strlen',
		'space' => 'strlen',
		'upper' => 'strlen',
		'xdigit' => 'strlen',
	];


	/**
	 * Throws exception if a variable is of unexpected type (separated by pipe).
	 */
	public static function assert($value, string $expected, string $label = 'variable'): void
	{
		if (!static::is($value, $expected)) {
			$expected = str_replace(['|', ':'], [' or ', ' in range '], $expected);
			static $translate = ['boolean' => 'bool', 'integer' => 'int', 'double' => 'float', 'NULL' => 'null'];
			$type = $translate[gettype($value)] ?? gettype($value);
			if (is_int($value) || is_float($value) || (is_string($value) && strlen($value) < 40)) {
				$type .= ' ' . var_export($value, true);
			} elseif (is_object($value)) {
				$type .= ' ' . get_class($value);
			}
			throw new AssertionException("The $label expects to be $expected, $type given.");
		}
	}


	/**
	 * Throws exception if an array field is missing or of unexpected type (separated by pipe).
	 */
	public static function assertField(array $arr, $field, string $expected = null, string $label = "item '%' in array"): void
	{
		if (!array_key_exists($field, $arr)) {
			throw new AssertionException('Missing ' . str_replace('%', $field, $label) . '.');

		} elseif ($expected) {
			static::assert($arr[$field], $expected, str_replace('%', $field, $label));
		}
	}


	/**
	 * Finds whether a variable is of expected type (separated by pipe).
	 */
	public static function is($value, string $expected): bool
	{
		foreach (explode('|', $expected) as $item) {
			if (substr($item, -2) === '[]') {
				if (is_iterable($value) && self::everyIs($value, substr($item, 0, -2))) {
					return true;
				}
				continue;
			} elseif (substr($item, 0, 1) === '?') {
				$item = substr($item, 1);
				if ($value === null) {
					return true;
				}
			}

			[$type] = $item = explode(':', $item, 2);
			if (isset(static::$validators[$type])) {
				try {
					if (!static::$validators[$type]($value)) {
						continue;
					}
				} catch (\TypeError $e) {
					continue;
				}
			} elseif ($type === 'pattern') {
				if (preg_match('|^' . ($item[1] ?? '') . '$|D', $value)) {
					return true;
				}
				continue;
			} elseif (!$value instanceof $type) {
				continue;
			}

			if (isset($item[1])) {
				$length = $value;
				if (isset(static::$counters[$type])) {
					$length = static::$counters[$type]($value);
				}
				$range = explode('..', $item[1]);
				if (!isset($range[1])) {
					$range[1] = $range[0];
				}
				if (($range[0] !== '' && $length < $range[0]) || ($range[1] !== '' && $length > $range[1])) {
					continue;
				}
			}
			return true;
		}
		return false;
	}


	/**
	 * Finds whether all values are of expected type (separated by pipe).
	 */
	public static function everyIs(iterable $values, string $expected): bool
	{
		foreach ($values as $value) {
			if (!static::is($value, $expected)) {
				return false;
			}
		}
		return true;
	}


	/**
	 * Finds whether a value is an integer or a float.
	 */
	public static function isNumber($value): bool
	{
		return is_int($value) || is_float($value);
	}


	/**
	 * Finds whether a value is an integer.
	 */
	public static function isNumericInt($value): bool
	{
		return is_int($value) || (is_string($value) && preg_match('#^[+-]?[0-9]+$#D', $value));
	}


	/**
	 * Finds whether a string is a floating point number in decimal base.
	 */
	public static function isNumeric($value): bool
	{
		return is_float($value) || is_int($value) || (is_string($value) && preg_match('#^[+-]?[0-9]*[.]?[0-9]+$#D', $value));
	}


	/**
	 * Finds whether a value is a syntactically correct callback.
	 */
	public static function isCallable($value): bool
	{
		return $value && is_callable($value, true);
	}


	/**
	 * Finds whether a value is an UTF-8 encoded string.
	 */
	public static function isUnicode($value): bool
	{
		return is_string($value) && preg_match('##u', $value);
	}


	/**
	 * Finds whether a value is "falsy".
	 */
	public static function isNone($value): bool
	{
		return $value == null; // intentionally ==
	}


	/** @internal */
	public static function isMixed(): bool
	{
		return true;
	}


	/**
	 * Finds whether a variable is a zero-based integer indexed array.
	 */
	public static function isList($value): bool
	{
		return Arrays::isList($value);
	}


	/**
	 * Is a value in specified min and max value pair?
	 */
	public static function isInRange($value, array $range): bool
	{
		if ($value === null || !(isset($range[0]) || isset($range[1]))) {
			return false;
		}
		$limit = $range[0] ?? $range[1];
		if (is_string($limit)) {
			$value = (string) $value;
		} elseif ($limit instanceof \DateTimeInterface) {
			if (!$value instanceof \DateTimeInterface) {
				return false;
			}
		} elseif (is_numeric($value)) {
			$value *= 1;
		} else {
			return false;
		}
		return (!isset($range[0]) || ($value >= $range[0])) && (!isset($range[1]) || ($value <= $range[1]));
	}


	/**
	 * Finds whether a string is a valid email address.
	 */
	public static function isEmail(string $value): bool
	{
		$atom = "[-a-z0-9!#$%&'*+/=?^_`{|}~]"; // RFC 5322 unquoted characters in local-part
		$alpha = "a-z\x80-\xFF"; // superset of IDN
		return (bool) preg_match("(^
			(\"([ !#-[\\]-~]*|\\\\[ -~])+\"|$atom+(\\.$atom+)*)  # quoted or unquoted
			@
			([0-9$alpha]([-0-9$alpha]{0,61}[0-9$alpha])?\\.)+    # domain - RFC 1034
			[$alpha]([-0-9$alpha]{0,17}[$alpha])?                # top domain
		$)Dix", $value);
	}


	/**
	 * Finds whether a string is a valid http(s) URL.
	 */
	public static function isUrl(string $value): bool
	{
		$alpha = "a-z\x80-\xFF";
		return (bool) preg_match("(^
			https?://(
				(([-_0-9$alpha]+\\.)*                       # subdomain
					[0-9$alpha]([-0-9$alpha]{0,61}[0-9$alpha])?\\.)?  # domain
					[$alpha]([-0-9$alpha]{0,17}[$alpha])?   # top domain
				|\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}  # IPv4
				|\[[0-9a-f:]{3,39}\]                        # IPv6
			)(:\\d{1,5})?                                   # port
			(/\\S*)?                                        # path
			(\?\\S*)?                                       # query
			(\#\\S*)?                                       # fragment
		$)Dix", $value);
	}


	/**
	 * Finds whether a string is a valid URI according to RFC 1738.
	 */
	public static function isUri(string $value): bool
	{
		return (bool) preg_match('#^[a-z\d+\.-]+:\S+$#Di', $value);
	}


	/**
	 * Checks whether the input is a class, interface or trait.
	 */
	public static function isType(string $type): bool
	{
		return class_exists($type) || interface_exists($type) || trait_exists($type);
	}


	/**
	 * Checks whether the input is a valid PHP identifier.
	 */
	public static function isPhpIdentifier(string $value): bool
	{
		return is_string($value) && preg_match('#^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$#D', $value);
	}
}
