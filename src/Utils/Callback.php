<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;


/**
 * PHP callable tools.
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

		} elseif (is_string($callable) && count($tmp = explode('::', $callable)) === 2) {
			$callable = $tmp;

		} elseif ($callable instanceof \Closure) {
			return $callable;

		} elseif (is_object($callable)) {
			$callable = array($callable, '__invoke');
		}

		if (PHP_VERSION_ID >= 50400) {
			if (is_string($callable) && function_exists($callable)) {
				$r = new \ReflectionFunction($callable);
				return $r->getClosure();

			} elseif (is_array($callable) && method_exists($callable[0], $callable[1])) {
				$r = new \ReflectionMethod($callable[0], $callable[1]);
				return $r->getClosure($callable[0]);
			}
		}

		self::check($callable);
		$_callable_ = $callable;
		return function () use ($_callable_) {
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
	 * Invokes internal PHP function with own error handler.
	 * @param  string
	 * @return mixed
	 */
	public static function invokeSafe($function, array $args, $onError)
	{
		$prev = set_error_handler(function ($severity, $message, $file, $line, $context = NULL, $stack = NULL) use ($onError, & $prev, $function) {
			if ($file === '' && defined('HHVM_VERSION')) { // https://github.com/facebook/hhvm/issues/4625
				$file = $stack[1]['file'];
			}
			if ($file === __FILE__ && $onError(str_replace("$function(): ", '', $message), $severity) !== FALSE) {
				return;
			} elseif ($prev) {
				return call_user_func_array($prev, func_get_args());
			}
			return FALSE;
		});

		try {
			$res = call_user_func_array($function, $args);
			restore_error_handler();
			return $res;

		} catch (\Exception $e) {
			restore_error_handler();
			throw $e;
		}
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
			$inner = self::unwrap($callable);
			return '{closure' . ($inner instanceof \Closure ? '}' : ' ' . self::toString($inner) . '}');
		} elseif (is_string($callable) && $callable[0] === "\0") {
			return '{lambda}';
		} else {
			is_callable($callable, TRUE, $textual);
			return $textual;
		}
	}


	/**
	 * @return \ReflectionMethod|\ReflectionFunction
	 */
	public static function toReflection($callable)
	{
		if ($callable instanceof \Closure) {
			$callable = self::unwrap($callable);
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
	 * Unwraps closure created by self::closure()
	 * @internal
	 * @return callable
	 */
	public static function unwrap(\Closure $closure)
	{
		$r = new \ReflectionFunction($closure);
		if (substr($r->getName(), -1) === '}') {
			$vars = $r->getStaticVariables();
			return isset($vars['_callable_']) ? $vars['_callable_'] : $closure;

		} elseif ($obj = $r->getClosureThis()) {
			return array($obj, $r->getName());

		} elseif ($class = $r->getClosureScopeClass()) {
			return array($class->getName(), $r->getName());

		} else {
			return $r->getName();
		}
	}

}
