<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 */

/*namespace Nette;*/



/**
 * PHP callback encapsulation.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette
 */
final class Callback extends Object
{
	/** @var callback */
	private $cb;
	/**/
	/** @var bool */
	public static $fix520;
	/**/



	/**
	 * @param  mixed   class, object, function, callback
	 * @param  string  method
	 */
	public function __construct($t, $m = NULL)
	{
		if ($m === NULL) {
			$this->cb = $t;
		} else {
			$this->cb = array($t, $m);
		}

		/**/
		// __invoke support
		if (is_object($this->cb)) {
			$this->cb = array($this->cb, '__invoke');

		} elseif (self::$fix520) {
			// explode 'Class::method' into array
			if (is_string($this->cb) && strpos($this->cb, ':')) {
				$this->cb = explode('::', $this->cb);
			}

			// remove class namespace
			if (is_array($this->cb) && is_string($this->cb[0]) && $a = strrpos($this->cb[0], '\\')) {
				$this->cb[0] = substr($this->cb[0], $a + 1);
			}

		} else {
			// remove class namespace
			if (is_string($this->cb) && $a = strrpos($this->cb, '\\')) {
				$this->cb = substr($this->cb, $a + 1);

			} elseif (is_array($this->cb) && is_string($this->cb[0]) && $a = strrpos($this->cb[0], '\\')) {
				$this->cb[0] = substr($this->cb[0], $a + 1);
			}
		}
		/**/

		if (!is_callable($this->cb, TRUE)) {
			throw new /*\*/InvalidArgumentException("Invalid callback.");
		}
	}



	/**
	 * Invokes callback. Do not call directly.
	 * @return mixed
	 */
	public function __invoke()
	{
		if (!is_callable($this->cb)) {
			throw new /*\*/InvalidStateException("Callback '$this' is not callable.");
		}
		/**/$args = func_get_args();/**/
		return call_user_func_array($this->cb, /**/$args/**//*func_get_args()*/);
	}



	/**
	 * Invokes callback.
	 * @return mixed
	 */
	public function invoke()
	{
		if (!is_callable($this->cb)) {
			throw new /*\*/InvalidStateException("Callback '$this' is not callable.");
		}
		/**/$args = func_get_args();/**/
		return call_user_func_array($this->cb, /**/$args/**//*func_get_args()*/);
	}



	/**
	 * Invokes callback with an array of parameters.
	 * @param  array
	 * @return mixed
	 */
	public function invokeArgs(array $args)
	{
		if (!is_callable($this->cb)) {
			throw new /*\*/InvalidStateException("Callback '$this' is not callable.");
		}
		return call_user_func_array($this->cb, $args);
	}



	/**
	 * Verifies that callback can be called.
	 * @return bool
	 */
	public function isCallable()
	{
		return is_callable($this->cb);
	}



	/**
	 * Returns PHP callback pseudotype.
	 * @return callback
	 */
	public function getNative()
	{
		return $this->cb;
	}



	/**
	 * @return string
	 */
	public function __toString()
	{
		is_callable($this->cb, TRUE, $textual);
		return $textual;
	}

}



/**/Callback::$fix520 = version_compare(PHP_VERSION , '5.2.2', '<');/**/
