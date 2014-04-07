<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;


/**
 * PHP callable tools.
 *
 * @author     David Grudl
 */
class Callback
{

	/**
	 * @param  mixed   class, object, callable
	 * @param  string  method
	 * @return \Closure
	 */
	public static function closure($callable, $m = NULL)
	{
		if ($m !== NULL) {
			$callable = array($callable, $m);
		} elseif ($callable instanceof \Closure) {
			return $callable;
		}

		self::check($callable, TRUE);
		$_callable_ = $callable;
		return function() use ($_callable_) {
			Callback::check($_callable_);
			return call_user_func_array($_callable_, func_get_args());
		};
	}


	/**
	 * Invokes callback.
	 * @return mixed
	 */
	public static function invoke($callable)
	{
		self::check($callable);
		return call_user_func_array($callable, array_slice(func_get_args(), 1));
	}


	/**
	 * Invokes callback with an array of parameters.
	 * @return mixed
	 */
	public static function invokeArgs($callable, array $args = array())
	{
		self::check($callable);
		return call_user_func_array($callable, $args);
	}


	/**
	 * @return callable
	 */
	public static function check($callable, $syntax = FALSE)
	{
		if (!is_callable($callable, $syntax)) {
			throw new Nette\InvalidArgumentException($syntax
				? 'Given value is not a callable type.'
				: sprintf("Callback '%s' is not callable.", self::toString($callable))
			);
		}
		return $callable;
	}


	/**
	 * @return string
	 */
	public static function toString($callable)
	{
		if ($callable instanceof \Closure) {
			if ($inner = self::unwrap($callable)) {
				return '{closure ' . self::toString($inner) . '}';
			}
			return '{closure}';
		} elseif (is_string($callable) && $callable[0] === "\0") {
			return '{lambda}';
		} else {
			is_callable($callable, TRUE, $textual);
			return $textual;
		}
	}


	/**
	 * @return Nette\Reflection\GlobalFunction|Nette\Reflection\Method
	 */
	public static function toReflection($callable)
	{
		if ($callable instanceof \Closure && $inner = self::unwrap($callable)) {
			$callable = $inner;
		} elseif ($callable instanceof Nette\Callback) {
			$callable = $callable->getNative();
		}

		$class = class_exists('Nette\Reflection\Method') ? 'Nette\Reflection\Method' : 'ReflectionMethod';
		if (is_string($callable) && strpos($callable, '::')) {
			return new $class($callable);
		} elseif (is_array($callable)) {
			return new $class($callable[0], $callable[1]);
		} elseif (is_object($callable) && !$callable instanceof \Closure) {
			return new $class($callable, '__invoke');
		} else {
			$class = class_exists('Nette\Reflection\GlobalFunction') ? 'Nette\Reflection\GlobalFunction' : 'ReflectionFunction';
			return new $class($callable);
		}
	}


	/**
	 * @return bool
	 */
	public static function isStatic($callable)
	{
		return is_array($callable) ? is_string($callable[0]) : is_string($callable);
	}



	/**
	 * Unwraps closure created by self::closure(), used i.e. by ObjectMixin in PHP < 5.4
	 * @internal
	 * @return callable
	 */
	public static function unwrap(\Closure $closure)
	{
		$rm = new \ReflectionFunction($closure);
		$vars = $rm->getStaticVariables();
		return isset($vars['_callable_']) ? $vars['_callable_'] : NULL;
	}

}
