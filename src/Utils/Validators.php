<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette\Utils;

use Nette;



/**
 * Validation utilites.
 *
 * @author     David Grudl
 */
class Validators extends Nette\Object
{
	protected static $validators = array(
		'bool' => 'is_bool',
		'boolean' => 'is_bool',
		'int' => 'is_int',
		'integer' => 'is_int',
		'float' => 'is_float',
		'number' => NULL, // is_int || is_float,
		'numeric' => 'Nette\Utils\Validators::isNumeric',
		'numericint' => 'Nette\Utils\Validators::isNumericInt',
		'string' =>  'is_string',
		'unicode' => 'Nette\Utils\Validators::isUnicode',
		'array' => 'is_array',
		'list' => 'Nette\Utils\Validators::isList',
		'object' => 'is_object',
		'resource' => 'is_resource',
		'scalar' => 'is_scalar',
		'callable' => 'Nette\Utils\Validators::isCallable',
		'null' => 'is_null',
		'email' => 'Nette\Utils\Validators::isEmail',
		'url' => 'Nette\Utils\Validators::isUrl',
		'none' => 'Nette\Utils\Validators::isNone',
		'pattern' => NULL,
		'alnum' => 'ctype_alnum',
		'alpha' => 'ctype_alpha',
		'digit' => 'ctype_digit',
		'lower' => 'ctype_lower',
		'upper' => 'ctype_upper',
		'space' => 'ctype_space',
		'xdigit' => 'ctype_xdigit',
	);

	protected static $counters = array(
		'string' =>  'strlen',
		'unicode' => 'Nette\Utils\Strings::length',
		'array' => 'count',
		'list' => 'count',
		'alnum' => 'strlen',
		'alpha' => 'strlen',
		'digit' => 'strlen',
		'lower' => 'strlen',
		'space' => 'strlen',
		'upper' => 'strlen',
		'xdigit' => 'strlen',
	);



	/**
	 * Throws exception if a variable is of unexpected type.
	 * @param  mixed
	 * @param  string  expected types separated by pipe
	 * @param  string  label
	 * @return void
	 */
	public static function assert($value, $expected, $label = 'variable')
	{
		if (!static::is($value, $expected)) {
			$expected = str_replace(array('|', ':'), array(' or ', ' in range '), $expected);
			if (is_array($value)) {
				$type = 'array(' . count($value) . ')';
			} elseif (is_object($value)) {
				$type = 'object ' . get_class($value);
			} elseif (is_string($value) && strlen($value) < 40) {
				$type = "string '$value'";
			} else {
				$type = gettype($value);
			}
			throw new AssertionException("The $label expects to be $expected, $type given.");
		}
	}



	/**
	 * Throws exception if an array field is missing or of unexpected type.
	 * @param  array
	 * @param  string  item
	 * @param  string  expected types separated by pipe
	 * @return void
	 */
	public static function assertField($arr, $field, $expected = NULL, $label = "item '%' in array")
	{
		self::assert($arr, 'array', 'first argument');
		if (!array_key_exists($field, $arr)) {
			throw new AssertionException('Missing ' . str_replace('%', $field, $label) . '.');

		} elseif ($expected) {
			static::assert($arr[$field], $expected, str_replace('%', $field, $label));
		}
	}



	/**
	 * Finds whether a variable is of expected type.
	 * @param  mixed
	 * @param  string  expected types separated by pipe with optional ranges
	 * @return bool
	 */
	public static function is($value, $expected)
	{
		foreach (explode('|', $expected) as $item) {
			list($type) = $item = explode(':', $item, 2);
			if (isset(static::$validators[$type])) {
				if (!call_user_func(static::$validators[$type], $value)) {
					continue;
				}
			} elseif ($type === 'number') {
				if (!is_int($value) && !is_float($value)) {
					continue;
				}
			} elseif ($type === 'pattern') {
				if (preg_match('|^' . (isset($item[1]) ? $item[1] : '') . '$|', $value)) {
					return TRUE;
				}
				continue;
			} elseif (!$value instanceof $type) {
				continue;
			}

			if (isset($item[1])) {
				if (isset(static::$counters[$type])) {
					$value = call_user_func(static::$counters[$type], $value);
				}
				$range = explode('..', $item[1]);
				if (!isset($range[1])) {
					$range[1] = $range[0];
				}
				if (($range[0] !== '' && $value < $range[0]) || ($range[1] !== '' && $value > $range[1])) {
					continue;
				}
			}
			return TRUE;
		}
		return FALSE;
	}



	/**
	 * Finds whether a value is an integer.
	 * @param  mixed
	 * @return bool
	 */
	public static function isNumericInt($value)
	{
		return is_int($value) || is_string($value) && preg_match('#^-?[0-9]+$#', $value);
	}



	/**
	 * Finds whether a string is a floating point number in decimal base.
	 * @param  mixed
	 * @return bool
	 */
	public static function isNumeric($value)
	{
		return is_float($value) || is_int($value) || is_string($value) && preg_match('#^-?[0-9]*[.]?[0-9]+$#', $value);
	}



	/**
	 * Finds whether a value is a syntactically correct callback.
	 * @param  mixed
	 * @return bool
	 */
	public static function isCallable($value)
	{
		return $value && is_callable($value, TRUE);
	}



	/**
	 * Finds whether a value is an UTF-8 encoded string.
	 * @param  string
	 * @return bool
	 */
	public static function isUnicode($value)
	{
		return is_string($value) && preg_match('##u', $value);
	}



	/**
	 * Finds whether a value is "falsy".
	 * @param  mixed
	 * @return bool
	 */
	public static function isNone($value)
	{
		return $value == NULL; // intentionally ==
	}



	/**
	 * Finds whether a variable is a zero-based integer indexed array.
	 * @param  array
	 * @return bool
	 */
	public static function isList($value)
	{
		return is_array($value) && (!$value || array_keys($value) === range(0, count($value) - 1));
	}



	/**
	 * Is a value in specified range?
	 * @param  mixed
	 * @param  array  min and max value pair
	 * @return bool
	 */
	public static function isInRange($value, $range)
	{
		return (!isset($range[0]) || $value >= $range[0]) && (!isset($range[1]) || $value <= $range[1]);
	}



	/**
	 * Finds whether a string is a valid email address.
	 * @param  string
	 * @return bool
	 */
	public static function isEmail($value)
	{
		$atom = "[-a-z0-9!#$%&'*+/=?^_`{|}~]"; // RFC 5322 unquoted characters in local-part
		$localPart = "(?:\"(?:[ !\\x23-\\x5B\\x5D-\\x7E]*|\\\\[ -~])+\"|$atom+(?:\\.$atom+)*)"; // quoted or unquoted
		$chars = "a-z0-9\x80-\xFF"; // superset of IDN
		$domain = "[$chars](?:[-$chars]{0,61}[$chars])"; // RFC 1034 one domain component
		return (bool) preg_match("(^$localPart@(?:$domain?\\.)+[-$chars]{2,19}\\z)i", $value);
	}



	/**
	 * Finds whether a string is a valid URL.
	 * @param  string
	 * @return bool
	 */
	public static function isUrl($value)
	{
		$chars = "a-z0-9\x80-\xFF";
		return (bool) preg_match("#^https?://(?:[$chars](?:[-$chars]{0,61}[$chars])?\\.)+[-$chars]{2,19}(/\S*)?$#i", $value);
	}

}



/**
 * The exception that indicates assertion error.
 */
class AssertionException extends \Exception
{
}
