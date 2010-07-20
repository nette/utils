<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 * @link       http://nette.org
 * @category   Nette
 * @package    Nette
 */

namespace Nette;

use Nette;



/**
 * Tools library.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette
 */
final class Tools
{
	/** minute in seconds */
	const MINUTE = 60;

	/** hour in seconds */
	const HOUR = 3600;

	/** day in seconds */
	const DAY = 86400;

	/** week in seconds */
	const WEEK = 604800;

	/** average month in seconds */
	const MONTH = 2629800;

	/** average year in seconds */
	const YEAR = 31557600;



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new \LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * DateTime object factory.
	 * @param  string|int|DateTime
	 * @return DateTime
	 */
	public static function createDateTime($time)
	{
		if ($time instanceof \DateTime) {
			return clone $time;

		} elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}
			return new \DateTime(date('Y-m-d H:i:s', $time));

		} else { // textual or NULL
			return new \DateTime($time);
		}
	}



	/**
	 * Gets the boolean value of a configuration option.
	 * @param  string  configuration option name
	 * @return bool
	 */
	public static function iniFlag($var)
	{
		$status = strtolower(ini_get($var));
		return $status === 'on' || $status === 'true' || $status === 'yes' || (int) $status;
	}



	/**
	 * Initializes variable with $default value.
	 * @param  mixed  variable
	 * @param  mixed  default value
	 * @return void
	 */
	public static function defaultize(&$var, $default)
	{
		if ($var === NULL) $var = $default;
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



	/**
	 * Returns the MIME content type of file.
	 * @param  string
	 * @return string
	 */
	public static function detectMimeType($file)
	{
		if (!is_file($file)) {
			throw new \FileNotFoundException("File '$file' not found.");
		}

		$info = getimagesize($file);
		if (isset($info['mime'])) {
			return $info['mime'];

		} elseif (extension_loaded('fileinfo')) {
			$type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);

		} elseif (function_exists('mime_content_type')) {
			$type = mime_content_type($file);
		}

		return isset($type) && preg_match('#^\S+/\S+$#', $type) ? $type : 'application/octet-stream';
	}

}