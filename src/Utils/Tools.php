<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette;

use Nette;



/**
 * Tools library.
 *
 * @author     David Grudl
 */
final class Tools
{

	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new \LogicException("Cannot instantiate static class " . get_class($this));
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

}