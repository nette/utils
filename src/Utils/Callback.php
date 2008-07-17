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



require_once dirname(__FILE__) . '/Object.php';



/**
 * Immutable encapsulation of pseudo-type callback.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette
 */
class Callback extends /*Nette::*/Object
{
	/** @var callback */
	private $callback;



	/**
	 * @param  callback|string|object
	 * @param  string
	 */
	public function __construct($a, $b = NULL)
	{
		$this->callback = func_num_args() === 1 ? $a : array($a, $b);
	}



	/**
	 * Verifies that user function is callable.
	 * @return bool
	 */
	public function isCallable()
	{
		return is_callable($this->callback);
	}



	/**
	 * Calls a user function.
	 * @return mixed
	 * @throws ::InvalidStateException
	 */
	public function invoke()
	{
		if (!is_callable($this->callback)) {
			throw new /*::*/InvalidStateException('The callback is not valid.');
		}
		$args = func_get_args();
		return call_user_func_array($this->callback, $args);
	}



	/**
	 * Calls a user function.
	 * @param  array
	 * @return mixed
	 * @throws ::InvalidStateException
	 */
	public function invokeArgs(array $args)
	{
		if (!is_callable($this->callback)) {
			throw new /*::*/InvalidStateException('The callback is not valid.');
		}
		return call_user_func_array($this->callback, $args);
	}



	/**
	 * Returns native PHP callback.
	 * @return callback
	 */
	public function getNative()
	{
		return $this->callback;
	}



	/**
	 * @return string
	 */
	public function __toString()
	{
		try {
			ob_start();
			call_user_func($this->callback);
			return ob_get_clean();

		} catch (Exception $e) {
			return $e->__toString();
		}
	}

}
