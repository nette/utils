<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;
use function is_array, is_int, is_object, count;


/**
 * Array tools library.
 */
class Arrays
{
	use Nette\StaticClass;

	/**
	 * Returns item from array or $default if item is not set.
	 * @param  string|int|array $key one or more keys
	 * @param  mixed  $default
	 * @return mixed
	 * @throws Nette\InvalidArgumentException if item does not exist and default value is not provided
	 */
	public static function get(array $array, $key, $default = null)
	{
		foreach (is_array($key) ? $key : [$key] as $k) {
			if (is_array($array) && array_key_exists($k, $array)) {
				$array = $array[$k];
			} else {
				if (func_num_args() < 3) {
					throw new Nette\InvalidArgumentException("Missing item '$k'.");
				}
				return $default;
			}
		}
		return $array;
	}


	/**
	 * Returns reference to array item.
	 * @param  string|int|array $key one or more keys
	 * @return mixed
	 * @throws Nette\InvalidArgumentException if traversed item is not an array
	 */
	public static function &getRef(array &$array, $key)
	{
		foreach (is_array($key) ? $key : [$key] as $k) {
			if (is_array($array) || $array === null) {
				$array = &$array[$k];
			} else {
				throw new Nette\InvalidArgumentException('Traversed item is not an array.');
			}
		}
		return $array;
	}


	/**
	 * Recursively appends elements of remaining keys from the second array to the first.
	 */
	public static function mergeTree(array $array1, array $array2): array
	{
		$res = $array1 + $array2;
		foreach (array_intersect_key($array1, $array2) as $k => $v) {
			if (is_array($v) && is_array($array2[$k])) {
				$res[$k] = self::mergeTree($v, $array2[$k]);
			}
		}
		return $res;
	}


	/**
	 * Searches the array for a given key and returns the offset if successful.
	 * @param  string|int  $key
	 * @return int|null offset if it is found, null otherwise
	 */
	public static function searchKey(array $array, $key): ?int
	{
		$foo = [$key => null];
		return Helpers::falseToNull(array_search(key($foo), array_keys($array), true));
	}


	/**
	 * Inserts new array before item specified by key.
	 * @param  string|int  $key
	 */
	public static function insertBefore(array &$array, $key, array $inserted): void
	{
		$offset = (int) self::searchKey($array, $key);
		$array = array_slice($array, 0, $offset, true)
			+ $inserted
			+ array_slice($array, $offset, count($array), true);
	}


	/**
	 * Inserts new array after item specified by key.
	 * @param  string|int  $key
	 */
	public static function insertAfter(array &$array, $key, array $inserted): void
	{
		$offset = self::searchKey($array, $key);
		$offset = $offset === null ? count($array) : $offset + 1;
		$array = array_slice($array, 0, $offset, true)
			+ $inserted
			+ array_slice($array, $offset, count($array), true);
	}


	/**
	 * Renames key in array.
	 * @param  string|int  $oldKey
	 * @param  string|int  $newKey
	 */
	public static function renameKey(array &$array, $oldKey, $newKey): void
	{
		$offset = self::searchKey($array, $oldKey);
		if ($offset !== null) {
			$keys = array_keys($array);
			$keys[$offset] = $newKey;
			$array = array_combine($keys, $array);
		}
	}


	/**
	 * Returns array entries that match the pattern.
	 */
	public static function grep(array $array, string $pattern, int $flags = 0): array
	{
		return Strings::pcre('preg_grep', [$pattern, $array, $flags]);
	}


	/**
	 * Returns flattened array.
	 */
	public static function flatten(array $array, bool $preserveKeys = false): array
	{
		$res = [];
		$cb = $preserveKeys
			? function ($v, $k) use (&$res): void { $res[$k] = $v; }
		: function ($v) use (&$res): void { $res[] = $v; };
		array_walk_recursive($array, $cb);
		return $res;
	}


	/**
	 * Finds whether a variable is a zero-based integer indexed array.
	 * @param  mixed  $value
	 */
	public static function isList($value): bool
	{
		return is_array($value) && (!$value || array_keys($value) === range(0, count($value) - 1));
	}


	/**
	 * Reformats table to associative tree. Path looks like 'field|field[]field->field=field'.
	 * @param  string|string[]  $path
	 * @return array|\stdClass
	 */
	public static function associate(array $array, $path)
	{
		$parts = is_array($path)
			? $path
			: preg_split('#(\[\]|->|=|\|)#', $path, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		if (!$parts || $parts === ['->'] || $parts[0] === '=' || $parts[0] === '|') {
			throw new Nette\InvalidArgumentException("Invalid path '$path'.");
		}

		$res = $parts[0] === '->' ? new \stdClass : [];

		foreach ($array as $rowOrig) {
			$row = (array) $rowOrig;
			$x = &$res;

			for ($i = 0; $i < count($parts); $i++) {
				$part = $parts[$i];
				if ($part === '[]') {
					$x = &$x[];

				} elseif ($part === '=') {
					if (isset($parts[++$i])) {
						$x = $row[$parts[$i]];
						$row = null;
					}

				} elseif ($part === '->') {
					if (isset($parts[++$i])) {
						if ($x === null) {
							$x = new \stdClass;
						}
						$x = &$x->{$row[$parts[$i]]};
					} else {
						$row = is_object($rowOrig) ? $rowOrig : (object) $row;
					}

				} elseif ($part !== '|') {
					$x = &$x[(string) $row[$part]];
				}
			}

			if ($x === null) {
				$x = $row;
			}
		}

		return $res;
	}


	/**
	 * Normalizes to associative array.
	 * @param  mixed  $filling
	 */
	public static function normalize(array $array, $filling = null): array
	{
		$res = [];
		foreach ($array as $k => $v) {
			$res[is_int($k) ? $v : $k] = is_int($k) ? $filling : $v;
		}
		return $res;
	}


	/**
	 * Picks element from the array by key and return its value.
	 * @param  string|int  $key
	 * @param  mixed  $default
	 * @return mixed
	 * @throws Nette\InvalidArgumentException if item does not exist and default value is not provided
	 */
	public static function pick(array &$array, $key, $default = null)
	{
		if (array_key_exists($key, $array)) {
			$value = $array[$key];
			unset($array[$key]);
			return $value;

		} elseif (func_num_args() < 3) {
			throw new Nette\InvalidArgumentException("Missing item '$key'.");

		} else {
			return $default;
		}
	}


	/**
	 * Tests whether some element in the array passes the callback test.
	 */
	public static function some(array $array, callable $callback): bool
	{
		foreach ($array as $k => $v) {
			if ($callback($v, $k, $array)) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Tests whether all elements in the array pass the callback test.
	 */
	public static function every(array $array, callable $callback): bool
	{
		foreach ($array as $k => $v) {
			if (!$callback($v, $k, $array)) {
				return false;
			}
		}
		return true;
	}


	/**
	 * Applies the callback to the elements of the array.
	 */
	public static function map(array $array, callable $callback): array
	{
		$res = [];
		foreach ($array as $k => $v) {
			$res[$k] = $callback($v, $k, $array);
		}
		return $res;
	}


	/**
	 * Converts array to object
	 * @param  object  $object
	 * @return object
	 */
	public static function toObject(array $array, $object)
	{
		foreach ($array as $k => $v) {
			$object->$k = $v;
		}
		return $object;
	}
}
