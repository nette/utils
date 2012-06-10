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
	 * Call to undefined method.
	 * @param  object
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public static function call($_this, $name, $args)
	{
		$class = get_class($_this);

		if ($name === '') {
			throw new MemberAccessException("Call to class '$class' method without name.");
		}

		if (property_exists($class, $name) && ($rp = new \ReflectionProperty($class, $name)) && $rp->isPublic() && !$rp->isStatic()) {
			// event functionality
			if (preg_match('#^on[A-Z]#', $name)) {
				if (is_array($list = $_this->$name) || $list instanceof \Traversable) {
					foreach ($list as $handler) {
						callback($handler)->invokeArgs($args);
					}
				} elseif ($list !== NULL) {
					throw new UnexpectedValueException("Property $class::$$name must be array or NULL, " . gettype($list) ." given.");
				}
				return NULL;
			}

			// closure in property
			if ($_this->$name instanceof \Closure) {
				return call_user_func_array($_this->$name, $args);
			}
		}

		// extension methods
		if ($cb = static::getExtensionMethod($class, $name)) {
			array_unshift($args, $_this);
			return $cb->invokeArgs($args);
		}

		throw new MemberAccessException("Call to undefined method $class::$name().");
	}



	/**
	 * Call to undefined method.
	 * @param  object
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public static function callProperty($_this, $name, $args)
	{
		if (strlen($name) > 3) {
			$op = substr($name, 0, 3);
			$prop = strtolower($name[3]) . substr($name, 4);
			if ($op === 'add' && property_exists($_this, $prop.'s')) {
				$_this->{$prop.'s'}[] = $args[0];
				return $_this;

			} elseif ($op === 'set' && property_exists($_this, $prop)) {
				$_this->$prop = $args[0];
				return $_this;

			} elseif ($op === 'get' && property_exists($_this, $prop)) {
				return $_this->$prop;
			}
		}
		self::call($_this, $name, $args);
	}



	/**
	 * Call to undefined static method.
	 * @param  string
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public static function callStatic($class, $name, $args)
	{
		throw new MemberAccessException("Call to undefined static method $class::$name().");
	}



	/**
	 * Returns property value.
	 * @param  object
	 * @param  string  property name
	 * @return mixed   property value
	 * @throws MemberAccessException if the property is not defined.
	 */
	public static function & get($_this, $name)
	{
		$class = get_class($_this);

		if ($name === '') {
			throw new MemberAccessException("Cannot read a class '$class' property without name.");
		}

		if (!isset(self::$methods[$class])) {
			// get_class_methods returns only public methods of objects
			// but returns static methods too
			// and is much faster than reflection
			self::$methods[$class] = array_flip(get_class_methods($class));
		}

		// public method as closure getter
		if (isset(self::$methods[$class][$name])) {
			$val = function() use ($_this, $name) {
				return call_user_func_array(array($_this, $name), func_get_args());
			};
			return $val;
		}

		// property getter support
		$name[0] = $name[0] & "\xDF"; // case-sensitive checking, capitalize first character
		$m = 'get' . $name;
		if (isset(self::$methods[$class][$m])) {
			// ampersands:
			// - uses &__get() because declaration should be forward compatible (e.g. with Nette\Utils\Html)
			// - doesn't call &$_this->$m because user could bypass property setter by: $x = & $obj->property; $x = 'new value';
			$val = $_this->$m();
			return $val;
		}

		$m = 'is' . $name;
		if (isset(self::$methods[$class][$m])) {
			$val = $_this->$m();
			return $val;
		}

		$type = isset(self::$methods[$class]['set' . $name]) ? 'a write-only' : 'an undeclared';
		$name = func_get_arg(1);
		throw new MemberAccessException("Cannot read $type property $class::\$$name.");
	}



	/**
	 * Sets value of a property.
	 * @param  object
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 * @throws MemberAccessException if the property is not defined or is read-only
	 */
	public static function set($_this, $name, $value)
	{
		$class = get_class($_this);

		if ($name === '') {
			throw new MemberAccessException("Cannot write to a class '$class' property without name.");
		}

		if (!isset(self::$methods[$class])) {
			self::$methods[$class] = array_flip(get_class_methods($class));
		}

		// property setter support
		$name[0] = $name[0] & "\xDF"; // case-sensitive checking, capitalize first character

		$m = 'set' . $name;
		if (isset(self::$methods[$class][$m])) {
			$_this->$m($value);
			return;
		}

		$type = isset(self::$methods[$class]['get' . $name]) || isset(self::$methods[$class]['is' . $name])
			? 'a read-only' : 'an undeclared';
		$name = func_get_arg(1);
		throw new MemberAccessException("Cannot write to $type property $class::\$$name.");
	}



	/**
	 * Throws exception.
	 * @param  object
	 * @param  string  property name
	 * @return void
	 * @throws MemberAccessException
	 */
	public static function remove($_this, $name)
	{
		$class = get_class($_this);
		throw new MemberAccessException("Cannot unset the property $class::\$$name.");
	}



	/**
	 * Is property defined?
	 * @param  object
	 * @param  string  property name
	 * @return bool
	 */
	public static function has($_this, $name)
	{
		if ($name === '') {
			return FALSE;
		}

		$class = get_class($_this);
		if (!isset(self::$methods[$class])) {
			self::$methods[$class] = array_flip(get_class_methods($class));
		}

		$name[0] = $name[0] & "\xDF";
		return isset(self::$methods[$class]['get' . $name]) || isset(self::$methods[$class]['is' . $name]);
	}



	/**
	 * Adds a method to class.
	 * @param  string  method name
	 * @param  mixed   callable
	 * @return ClassType  provides a fluent interface
	 */
	public static function setExtensionMethod($class, $name, $callback)
	{
		$l = & self::$extMethods[strtolower($name)];
		$l[strtolower($class)] = callback($callback);
		$l[''] = NULL;
	}



	/**
	 * Returns extension method.
	 * @param  string  method name
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
