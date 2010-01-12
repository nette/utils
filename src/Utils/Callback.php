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
	public function __construct($callback, $m = NULL)
	{
		if ($m === NULL) {
			$this->cb = $callback;
		} else {
			$this->cb = $callback = array($callback, $m);
		}

		/**/
		// __invoke support
		if (is_object($callback)) {
			$this->cb = array($callback, '__invoke');

		} elseif (self::$fix520) {
			// explode 'Class::method' into array
			if (is_string($callback) && strpos($callback, ':')) {
				$this->cb = explode('::', $callback);
			}

			// remove class namespace
			if (is_array($callback) && is_string($callback[0]) && $a = strrpos($callback[0], '\\')) {
				$this->cb[0] = substr($callback[0], $a + 1);
			}

		} else {
			// remove class namespace
			if (is_string($callback) && $a = strrpos($callback, '\\')) {
				$this->cb = substr($callback, $a + 1);

			} elseif (is_array($callback) && is_string($callback[0]) && $a = strrpos($callback[0], '\\')) {
				$this->cb[0] = substr($callback[0], $a + 1);
			}
		}
		/**/
	}



	/**
	 * Invokes callback.
	 * @return mixed
	 */
	public function __invoke()
	{
		if (!is_callable($this->cb)) {
			$this->check();
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
			$this->check();
		}
		return call_user_func_array($this->cb, $args);
	}



	/**
	 * Throws exception is callback cannot be called.
	 * @param  string
	 * @return void
	 * @throws \InvalidStateException
	 */
	public function check($label = 'Callback')
	{
		if (!is_callable($this->cb)) {
			$able = is_callable($this->cb, TRUE, $textual);
			throw new /*\*/InvalidStateException("$label '$textual' is not " . ($able ? 'callable.' : 'valid.'));
		}
		return $this;
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
