<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;
use function is_array, is_int, is_object;


/**
 * Array tools library.
 */
class Arrays
{
	use Nette\StaticClass;

	/**
	 * Returns item from array or $default if item is not set.
	 * @param  string|int|array $key one or more keys
	 * @return mixed
	 * @throws Nette\InvalidArgumentException if item does not exist and default value is not provided
	 */
	public static function get(array $arr, $key, $default = NULL)
	{
		foreach (is_array($key) ? $key : [$key] as $k) {
			if (is_array($arr) && array_key_exists($k, $arr)) {
				$arr = $arr[$k];
			} else {
				if (func_num_args() < 3) {
					throw new Nette\InvalidArgumentException("Missing item '$k'.");
				}
				return $default;
			}
		}
		return $arr;
	}


	/**
	 * Returns reference to array item.
	 * @param  string|int|array $key one or more keys
	 * @return mixed
	 * @throws Nette\InvalidArgumentException if traversed item is not an array
	 */
	public static function &getRef(array &$arr, $key)
	{
		foreach (is_array($key) ? $key : [$key] as $k) {
			if (is_array($arr) || $arr === NULL) {
				$arr = &$arr[$k];
			} else {
				throw new Nette\InvalidArgumentException('Traversed item is not an array.');
			}
		}
		return $arr;
	}


	/**
	 * Recursively appends elements of remaining keys from the second array to the first.
	 */
	public static function mergeTree(array $arr1, array $arr2): array
	{
		$res = $arr1 + $arr2;
		foreach (array_intersect_key($arr1, $arr2) as $k => $v) {
			if (is_array($v) && is_array($arr2[$k])) {
				$res[$k] = self::mergeTree($v, $arr2[$k]);
			}
		}
		return $res;
	}


	/**
	 * Searches the array for a given key and returns the offset if successful.
	 * @return int|NULL offset if it is found, NULL otherwise
	 */
	public static function searchKey(array $arr, $key)
	{
		$foo = [$key => NULL];
		return ($tmp = array_search(key($foo), array_keys($arr), TRUE)) === FALSE ? NULL : $tmp;
	}


	/**
	 * Inserts new array before item specified by key.
	 * @return void
	 */
	public static function insertBefore(array &$arr, $key, array $inserted)
	{
		$offset = (int) self::searchKey($arr, $key);
		$arr = array_slice($arr, 0, $offset, TRUE) + $inserted + array_slice($arr, $offset, count($arr), TRUE);
	}


	/**
	 * Inserts new array after item specified by key.
	 * @return void
	 */
	public static function insertAfter(array &$arr, $key, array $inserted)
	{
		$offset = self::searchKey($arr, $key);
		$offset = $offset === NULL ? count($arr) : $offset + 1;
		$arr = array_slice($arr, 0, $offset, TRUE) + $inserted + array_slice($arr, $offset, count($arr), TRUE);
	}


	/**
	 * Renames key in array.
	 * @return void
	 */
	public static function renameKey(array &$arr, $oldKey, $newKey)
	{
		$offset = self::searchKey($arr, $oldKey);
		if ($offset !== NULL) {
			$keys = array_keys($arr);
			$keys[$offset] = $newKey;
			$arr = array_combine($keys, $arr);
		}
	}


	/**
	 * Returns array entries that match the pattern.
	 */
	public static function grep(array $arr, string $pattern, int $flags = 0): array
	{
		return Strings::pcre('preg_grep', [$pattern, $arr, $flags]);
	}


	/**
	 * Returns flattened array.
	 */
	public static function flatten(array $arr, bool $preserveKeys = FALSE): array
	{
		$res = [];
		$cb = $preserveKeys
			? function ($v, $k) use (&$res) { $res[$k] = $v; }
			: function ($v) use (&$res) { $res[] = $v; };
		array_walk_recursive($arr, $cb);
		return $res;
	}


	/**
	 * Finds whether a variable is a zero-based integer indexed array.
	 */
	public static function isList($value): bool
	{
		return is_array($value) && (!$value || array_keys($value) === range(0, count($value) - 1));
	}


	/**
	 * Reformats table to associative tree. Path looks like 'field|field[]field->field=field'.
	 * @return array|\stdClass
	 */
	public static function associate(array $arr, $path)
	{
		$parts = is_array($path)
			? $path
			: preg_split('#(\[\]|->|=|\|)#', $path, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		if (!$parts || $parts[0] === '=' || $parts[0] === '|' || $parts === ['->']) {
			throw new Nette\InvalidArgumentException("Invalid path '$path'.");
		}

		$res = $parts[0] === '->' ? new \stdClass : [];

		foreach ($arr as $rowOrig) {
			$row = (array) $rowOrig;
			$x = &$res;

			for ($i = 0; $i < count($parts); $i++) {
				$part = $parts[$i];
				if ($part === '[]') {
					$x = &$x[];

				} elseif ($part === '=') {
					if (isset($parts[++$i])) {
						$x = $row[$parts[$i]];
						$row = NULL;
					}

				} elseif ($part === '->') {
					if (isset($parts[++$i])) {
						$x = &$x->{$row[$parts[$i]]};
					} else {
						$row = is_object($rowOrig) ? $rowOrig : (object) $row;
					}

				} elseif ($part !== '|') {
					$x = &$x[(string) $row[$part]];
				}
			}

			if ($x === NULL) {
				$x = $row;
			}
		}

		return $res;
	}


	/**
	 * Normalizes to associative array.
	 */
	public static function normalize(array $arr, $filling = NULL): array
	{
		$res = [];
		foreach ($arr as $k => $v) {
			$res[is_int($k) ? $v : $k] = is_int($k) ? $filling : $v;
		}
		return $res;
	}


	/**
	 * Picks element from the array by key and return its value.
	 * @param  string|int $key array key
	 * @return mixed
	 * @throws Nette\InvalidArgumentException if item does not exist and default value is not provided
	 */
	public static function pick(array &$arr, $key, $default = NULL)
	{
		if (array_key_exists($key, $arr)) {
			$value = $arr[$key];
			unset($arr[$key]);
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
	public static function some(array $arr, callable $callback): bool
	{
		foreach ($arr as $k => $v) {
			if ($callback($v, $k, $arr)) {
				return TRUE;
			}
		}
		return FALSE;
	}


	/**
	 * Tests whether all elements in the array pass the callback test.
	 */
	public static function every(array $arr, callable $callback): bool
	{
		foreach ($arr as $k => $v) {
			if (!$callback($v, $k, $arr)) {
				return FALSE;
			}
		}
		return TRUE;
	}


	/**
	 * Applies the callback to the elements of the array.
	 */
	public static function map(array $arr, callable $callback): array
	{
		$res = [];
		foreach ($arr as $k => $v) {
			$res[$k] = $callback($v, $k, $arr);
		}
		return $res;
	}

}
