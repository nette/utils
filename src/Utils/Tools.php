<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2008 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2008 David Grudl
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
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette
 */
final class Tools
{
	/** @var int  limit whether expiration is number of seconds starting from current time or timestamp */
	const EXPIRATION_DELTA_LIMIT = 31622400; // 366 days



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new /*::*/LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * Generates a unique ID.
	 * @return string
	 */
	public static function uniqueId()
	{
		static $entropy = 0;
		$entropy++;
		$id = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$id = md5(uniqid($id . $entropy, TRUE));
		$id = base_convert($id, 16, 36);
		return $id;
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
	 * Force ini_set.
	 * @param  string  variable.
	 * @param  mixed   value.
	 * @return void
	 * @throws ::NotSupportedException
	 */
	public static function iniSet($var, $value)
	{
		ini_set($var, $value);
		$current = is_bool($value) ? self::iniFlag($var) : ini_get($var);
		if ($current !== $value) {
			throw new /*::*/NotSupportedException('Function ini_set() is not enabled.');
		}
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
	 * Recursive glob(). Finds pathnames matching a pattern.
	 * @param  string
	 * @param  int
	 * @return array
	 */
	public static function glob($pattern, $flags = 0)
	{
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
	 */
	public static function _errorHandler($code, $message)
	{
		restore_error_handler();

		if (ini_get('html_errors')) {
			$message = strip_tags($message);
			$message = html_entity_decode($message);
		}

		self::$errorMsg = $message;
	}

}