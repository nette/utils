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
	 * Returns item from array. If it does not exist, it throws an exception, unless a default value is set.
	 * @param  string|int|array  $key one or more keys
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
	 * Returns reference to array item. If the index does not exist, new one is created with value null.
	 * @param  string|int|array  $key one or more keys
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
	 * Recursively merges two fields. It is useful, for example, for merging tree structures. It behaves as
	 * the + operator for array, ie. it adds a key/value pair from the second array to the first one and retains
	 * the value from the first array in the case of a key collision.
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
	 * Returns zero-indexed position of given array key. Returns null if key is not found.
	 * @param  string|int  $key
	 * @return int|null offset if it is found, null otherwise
	 */
	public static function getKeyOffset(array $array, $key): ?int
	{
		return Helpers::falseToNull(array_search(self::toKey($key), array_keys($array), true));
	}


	/**
	 * @deprecated  use  getKeyOffset()
	 */
	public static function searchKey(array $array, $key): ?int
	{
		return self::getKeyOffset($array, $key);
	}


	/**
	 * Inserts the contents of the $inserted array into the $array immediately after the $key.
	 * If $key is null (or does not exist), it is inserted at the beginning.
	 * @param  string|int|null  $key
	 */
	public static function insertBefore(array &$array, $key, array $inserted): void
	{
		$offset = (int) self::searchKey($array, $key);
		$array = array_slice($array, 0, $offset, true)
			+ $inserted
			+ array_slice($array, $offset, count($array), true);
	}


	/**
	 * Inserts the contents of the $inserted array into the $array before the $key.
	 * If $key is null (or does not exist), it is inserted at the end.
	 * @param  string|int|null  $key
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
	public static function renameKey(array &$array, $oldKey, $newKey): bool
	{
		$offset = self::searchKey($array, $oldKey);
		if ($offset === null) {
			return false;
		}
		$val = &$array[$oldKey];
		$keys = array_keys($array);
		$keys[$offset] = $newKey;
		$array = array_combine($keys, $array);
		$array[$newKey] = &$val;
		return true;
	}


	/**
	 * Returns only those array items, which matches a regular expression $pattern.
	 * @throws Nette\RegexpException  on compilation or runtime error
	 */
	public static function grep(array $array, string $pattern, int $flags = 0): array
	{
		return Strings::pcre('preg_grep', [$pattern, $array, $flags]);
	}


	/**
	 * Transforms multidimensional array to flat array.
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
	 * Checks if the array is indexed in ascending order of numeric keys from zero, a.k.a list.
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
	 * Normalizes array to associative array. Replace numeric keys with their values, the new value will be $filling.
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
	 * Returns and removes the value of an item from an array. If it does not exist, it throws an exception,
	 * or returns $default, if provided.
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
	 * Tests whether at least one element in the array passes the test implemented by the
	 * provided callback with signature `function ($value, $key, array $array): bool`.
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
	 * Tests whether all elements in the array pass the test implemented by the provided function,
	 * which has the signature `function ($value, $key, array $array): bool`.
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
	 * Calls $callback on all elements in the array and returns the array of return values.
	 * The callback has the signature `function ($value, $key, array $array): bool`.
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
	 * Copies the elements of the $array array to the $object object and then returns it.
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


	/**
	 * Converts value to array key.
	 * @param  mixed  $value
	 * @return int|string
	 */
	public static function toKey($value)
	{
		return key([$value => null]);
	}
}
