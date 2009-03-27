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
 * @version    $Id$
 */

/*namespace Nette;*/



/**
 * Tools library.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette
 */
final class Tools
{
	/** hour in seconds */
	const HOUR = 3600;

	/** day in seconds */
	const DAY = 86400;

	/** year in seconds */
	const YEAR = 31557600;



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new /*\*/LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * Gets the boolean value of a configuration option.
	 * @param  string  configuration option name
	 * @return bool
	 */
	public static function iniFlag($var)
	{
		$status = strtolower(ini_get($var));
		return $status === 'on' || $status === 'true' || $status === 'yes' || $status % 256;
	}



	/**
	 * Initializes variable with $default value.
	 *
	 * @param  mixed  variable
	 * @param  mixed  default value
	 * @return void
	 */
	public static function defaultize(&$var, $default)
	{
		if ($var === NULL) $var = $default;
	}



	/**
	 * Returns array item or $default if item is not set.
	 * Example: $val = arrayGet($arr, 'i', 123);
	 *
	 * @param  mixed  array
	 * @param  scalar key
	 * @param  mixed  default value
	 * @return mixed
	 */
	public static function arrayGet(array $arr, $key, $default = NULL)
	{
		if (isset($arr[$key])) return $arr[$key];
		return $default;
	}



	/**
	 * Recursively appends elements of remaining keys from the second array to the first.
	 * @param  array
	 * @param  array
	 * @return array
	 */
	public static function arrayMergeTree($arr1, $arr2)
	{
		$res = $arr1 + $arr2;
		foreach (array_intersect_key($arr1, $arr2) as $k => $v) {
			if (is_array($v) && is_array($arr2[$k])) {
				$res[$k] = self::arrayMergeTree($v, $arr2[$k]);
			}
		}
		return $res;
	}



	/**
	 * Recursive glob(). Finds pathnames matching a pattern.
	 * @param  string
	 * @param  int
	 * @return array
	 */
	public static function glob($pattern, $flags = 0)
	{
		// TODO: replace by RecursiveDirectoryIterator
		$files = glob($pattern, $flags);
		if (!is_array($files)) {
			$files = array();
		}

		$dirs = glob(dirname($pattern) . '/*', $flags | GLOB_ONLYDIR);
		if (is_array($dirs)) {
			$mask = basename($pattern);
			foreach ($dirs as $dir) {
				$files = array_merge($files, self::glob($dir . '/' . $mask, $flags));
			}
		}

		return $files;
	}



	/********************* errors and warnings catching ****************d*g**/



	/** @var string */
	private static $errorMsg;



	/**
	 * Starts catching potential errors/warnings.
	 *
	 * @return void
	 */
	public static function tryError($level = E_ALL)
	{
		set_error_handler(array(__CLASS__, '_errorHandler'), $level);
		self::$errorMsg = NULL;
	}



	/**
	 * Returns catched error/warning message.
	 *
	 * @param  string  catched message
	 * @return bool
	 */
	public static function catchError(& $message)
	{
		restore_error_handler();
		$message = self::$errorMsg;
		self::$errorMsg = NULL;
		return $message !== NULL;
	}



	/**
	 * Internal error handler. Do not call directly.
	 * @internal
	 */
	public static function _errorHandler($code, $message)
	{
		restore_error_handler();

		if (ini_get('html_errors')) {
			$message = strip_tags($message);
			$message = html_entity_decode($message);
		}

		if (($a = strpos($message, ': ')) !== FALSE) {
			$message = substr($message, $a + 2);
		}

		self::$errorMsg = $message;
	}

}