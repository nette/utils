<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette;

use Nette;



/**
 * Nette\Object behaviour mixin.
 *
 * @author     David Grudl
 */
final class ObjectMixin
{
	/** @var array */
	private static $methods;

	/** @var array */
	private static $props;

	/** @var array (method => array(type => callable)) */
	private static $extMethods;



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new StaticClassException;
	}



	/**
	 * __call() implementation.
	 * @param  object
	 * @param  string
	 * @param  array
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public static function call($_this, $name, $args)
	{
		$class = get_class($_this);
		$isProp = self::hasProperty($class, $name);

		if ($name === '') {
			throw new MemberAccessException("Call to class '$class' method without name.");

		} elseif ($isProp && $_this->$name instanceof \Closure) { // closure in property
			return call_user_func_array($_this->$name, $args);

		} elseif ($isProp === 'event') { // calling event handlers
			if (is_array($_this->$name)) {
				foreach ($_this->$name as $handler) {
					callback($handler)->invokeArgs($args);
				}
			} elseif ($_this->$name !== NULL) {
				throw new UnexpectedValueException("Property $class::$$name must be array or NULL, " . gettype($_this->$name) ." given.");
			}

		} elseif ($cb = self::getExtensionMethod($class, $name)) { // extension methods
			array_unshift($args, $_this);
			return $cb->invokeArgs($args);

		} else {
			throw new MemberAccessException("Call to undefined method $class::$name().");
		}
	}



	/**
	 * __call() implementation for entities.
	 * @param  object
	 * @param  string
	 * @param  array
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public static function callProperty($_this, $name, $args)
	{
		if (strlen($name) > 3) {
			$op = substr($name, 0, 3);
			$prop = strtolower($name[3]) . substr($name, 4);
			if ($op === 'add' && self::hasProperty(get_class($_this), $prop.'s')) {
				$_this->{$prop.'s'}[] = $args[0];
				return $_this;

			} elseif ($op === 'set' && self::hasProperty(get_class($_this), $prop)) {
				$_this->$prop = $args[0];
				return $_this;

			} elseif ($op === 'get' && self::hasProperty(get_class($_this), $prop)) {
				return $_this->$prop;
			}
		}
		return self::call($_this, $name, $args);
	}



	/**
	 * __callStatic() implementation.
	 * @param  string
	 * @param  string
	 * @param  array
	 * @return void
	 * @throws MemberAccessException
	 */
	public static function callStatic($class, $method, $args)
	{
		throw new MemberAccessException("Call to undefined static method $class::$method().");
	}



	/**
	 * __get() implementation.
	 * @param  object
	 * @param  string  property name
	 * @return mixed   property value
	 * @throws MemberAccessException if the property is not defined.
	 */
	public static function & get($_this, $name)
	{
		$class = get_class($_this);
		$uname = ucfirst($name);

		if (!isset(self::$methods[$class])) {
			self::$methods[$class] = array_flip(get_class_methods($class)); // public (static and non-static) methods
		}

		if ($name === '') {
			throw new MemberAccessException("Cannot read a class '$class' property without name.");

		} elseif (isset(self::$methods[$class][$name])) { // public method as closure getter
			$val = function() use ($_this, $name) {
				return call_user_func_array(array($_this, $name), func_get_args());
			};
			return $val;

		} elseif (isset(self::$methods[$class][$m = 'get' . $uname]) || isset(self::$methods[$class][$m = 'is' . $uname])) { // property getter
			$isRef = & self::$methods[$class][$m];
			if (!is_bool($isRef)) {
				$rm = new \ReflectionMethod($class, $m);
				$isRef = $rm->returnsReference();
			}
			if ($isRef) {
				return $_this->$m();
			} else {
				$val = $_this->$m();
				return $val;
			}

		} else { // strict class
			$type = isset(self::$methods[$class]['set' . $uname]) ? 'a write-only' : 'an undeclared';
			throw new MemberAccessException("Cannot read $type property $class::\$$name.");
		}
	}



	/**
	 * __set() implementation.
	 * @param  object
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 * @throws MemberAccessException if the property is not defined or is read-only
	 */
	public static function set($_this, $name, $value)
	{
		$class = get_class($_this);
		$uname = ucfirst($name);

		if (!isset(self::$methods[$class])) {
			self::$methods[$class] = array_flip(get_class_methods($class));
		}

		if ($name === '') {
			throw new MemberAccessException("Cannot write to a class '$class' property without name.");

		} elseif (self::hasProperty($class, $name)) { // unsetted property
			$_this->$name = $value;

		} elseif (isset(self::$methods[$class][$m = 'set' . $uname])) { // property setter
			$_this->$m($value);

		} else { // strict class
			$type = isset(self::$methods[$class]['get' . $uname]) || isset(self::$methods[$class]['is' . $uname])
				? 'a read-only' : 'an undeclared';
			throw new MemberAccessException("Cannot write to $type property $class::\$$name.");
		}
	}



	/**
	 * __unset() implementation.
	 * @param  object
	 * @param  string  property name
	 * @return void
	 * @throws MemberAccessException
	 */
	public static function remove($_this, $name)
	{
		$class = get_class($_this);
		if (!self::hasProperty($class, $name)) { // strict class
			throw new MemberAccessException("Cannot unset the property $class::\$$name.");
		}
	}



	/**
	 * __isset() implementation.
	 * @param  object
	 * @param  string  property name
	 * @return bool
	 */
	public static function has($_this, $name)
	{
		$class = get_class($_this);
		$name = ucfirst($name);
		if (!isset(self::$methods[$class])) {
			self::$methods[$class] = array_flip(get_class_methods($class));
		}
		return $name !== '' && (isset(self::$methods[$class]['get' . $name]) || isset(self::$methods[$class]['is' . $name]));
	}



	/**
	 * Checks if the public non-static property exists.
	 * @return mixed
	 */
	private static function hasProperty($class, $name)
	{
		$prop = & self::$props[$class][$name];
		if ($prop === NULL) {
			$prop = FALSE;
			try {
				$rp = new \ReflectionProperty($class, $name);
				if ($name === $rp->getName() && $rp->isPublic() && !$rp->isStatic()) {
					$prop = preg_match('#^on[A-Z]#', $name) ? 'event' : TRUE;
				}
			} catch (\ReflectionException $e) {}
		}
		return $prop;
	}



	/**
	 * Adds a method to class.
	 * @param  string
	 * @param  string
	 * @param  mixed   callable
	 * @return void
	 */
	public static function setExtensionMethod($class, $name, $callback)
	{
		$l = & self::$extMethods[strtolower($name)];
		$l[strtolower($class)] = callback($callback);
		$l[''] = NULL;
	}



	/**
	 * Returns extension method.
	 * @param  string
	 * @param  string
	 * @return mixed
	 */
	public static function getExtensionMethod($class, $name)
	{
		/*5.2* if (self::$extMethods === NULL || $name === NULL) { // for backwards compatibility
			$list = get_defined_functions(); // names are lowercase!
			foreach ($list['user'] as $fce) {
				$pair = explode('_prototype_', $fce);
				if (count($pair) === 2) {
					self::$extMethods[$pair[1]][$pair[0]] = callback($fce);
					self::$extMethods[$pair[1]][''] = NULL;
				}
			}
			if ($name === NULL) {
				return NULL;
			}
		}
		*/

		$class = strtolower($class);
		$l = & self::$extMethods[strtolower($name)];

		if (empty($l)) {
			return FALSE;

		} elseif (isset($l[''][$class])) { // cached value
			return $l[''][$class];
		}

		$cl = $class;
		do {
			if (isset($l[$cl])) {
				return $l[''][$class] = $l[$cl];
			}
		} while (($cl = strtolower(get_parent_class($cl))) !== '');

		foreach (class_implements($class) as $cl) {
			$cl = strtolower($cl);
			if (isset($l[$cl])) {
				return $l[''][$class] = $l[$cl];
			}
		}
		return $l[''][$class] = FALSE;
	}

}
