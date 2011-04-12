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
 * Nette\Object behaviour mixin.
 *
 * @author     David Grudl
 */
final class ObjectMixin
{
	/** @var array */
	private static $methods;



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new \LogicException("Cannot instantiate static class " . get_class($this));
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
		$class = new Reflection\ClassType($_this);

		if ($name === '') {
			throw new MemberAccessException("Call to class '$class->name' method without name.");
		}

		// event functionality
		if ($class->hasEventProperty($name)) {
			if (is_array($list = $_this->$name) || $list instanceof \Traversable) {
				foreach ($list as $handler) {
					callback($handler)->invokeArgs($args);
				}
			}
			return NULL;
		}

		// extension methods
		if ($cb = $class->getExtensionMethod($name)) {
			array_unshift($args, $_this);
			return $cb->invokeArgs($args);
		}

		throw new MemberAccessException("Call to undefined method $class->name::$name().");
	}



	/**
	 * Call to undefined static method.
	 * @param  object
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
			// get_class_methods returns ONLY PUBLIC methods of objects
			// but returns static methods too (nothing doing...)
			// and is much faster than reflection
			// (works good since 5.0.4)
			self::$methods[$class] = array_flip(get_class_methods($class));
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
	 * @param  mixed   property value
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

}
