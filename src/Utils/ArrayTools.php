<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 */

/*namespace Nette;*/



/**
 * Array tools library.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette
 */
final class ArrayTools
{

	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new /*\*/LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * Returns array item or $default if item is not set.
	 * Example: $val = ArrayTools::get($arr, 'i', 123);
	 * @param  mixed  array
	 * @param  scalar key
	 * @param  mixed  default value
	 * @return mixed
	 */
	public static function get(array $arr, $key, $default = NULL)
	{
		return array_key_exists($key, $arr) ? $arr[$key] : $default;
	}



	/**
	 * Recursively appends elements of remaining keys from the second array to the first.
	 * @param  array
	 * @param  array
	 * @return array
	 */
	public static function mergeTree($arr1, $arr2)
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
	 * @param  array  input array
	 * @param  mixed  key
	 * @return int    offset if it is found, FALSE otherwise
	 */
	public static function searchKey($arr, $key)
	{
		$foo = array($key => NULL);
		return array_search(key($foo), array_keys($arr), TRUE);
	}



	/**
	 * Inserts new array before item specified by key.
	 * @param  array  input array
	 * @param  mixed  key
	 * @param  array  inserted array
	 * @return void
	 */
	public static function insertBefore(array &$arr, $key, array $inserted)
	{
		$offset = self::searchKey($arr, $key);
		$arr = array_slice($arr, 0, $offset, TRUE) + $inserted + array_slice($arr, $offset, NULL, TRUE);
	}



	/**
	 * Inserts new array after item specified by key.
	 * @param  array  input array
	 * @param  mixed  key
	 * @param  array  inserted array
	 * @return void
	 */
	public static function insertAfter(array &$arr, $key, array $inserted)
	{
		$offset = self::searchKey($arr, $key);
		$offset = $offset === FALSE ? NULL : $offset + 1;
		$arr = array_slice($arr, 0, $offset, TRUE) + $inserted + array_slice($arr, $offset, NULL, TRUE);
	}

}