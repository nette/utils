<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 * @version    $Id$
 */

/*namespace Nette;*/



/**/require_once dirname(__FILE__) . '/compatibility.php';/**/

require_once dirname(__FILE__) . '/exceptions.php';



/**
 * Nette\Object behaviour mixin.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette
 */
final class ObjectMixin
{
	/** @var array (method => array(type => callback)) */
	private static $extMethods;

	/** @var array */
	private static $methods;



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new /*\*/LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * Call to undefined method.
	 *
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws \MemberAccessException
	 */
	public static function call($_this, $name, $args)
	{
		$class = get_class($_this);

		if ($name === '') {
			throw new /*\*/MemberAccessException("Call to class '$class' method without name.");
		}

		// event functionality
		if (preg_match('#^on[A-Z]#', $name)) {
			$rp = new /*\*/ReflectionProperty($class, $name);
			if ($rp->isPublic() && !$rp->isStatic()) {
				$list = $_this->$name;
				if (is_array($list) || $list instanceof /*\*/Traversable) {
					foreach ($list as $handler) {
						/**/fixCallback($handler);/**/
						if (!is_callable($handler)) {
							$able = is_callable($handler, TRUE, $textual);
							throw new /*\*/InvalidStateException("Event handler '$textual' is not " . ($able ? 'callable.' : 'valid PHP callback.'));
						}
						call_user_func_array($handler, $args);
					}
				}
				return NULL;
			}
		}

		// extension methods
		if ($cb = self::extensionMethod($class, $name)) {
			array_unshift($args, $_this);
			return call_user_func_array($cb, $args);
		}

		throw new /*\*/MemberAccessException("Call to undefined method $class::$name().");
	}



	/**
	 * Adding method to class.
	 *
	 * @param  string  class name
	 * @param  string  method name
	 * @param  mixed   callback or closure
	 * @return mixed
	 */
	public static function extensionMethod($class, $name, $callback = NULL)
	{
		if (self::$extMethods === NULL || $name === NULL) { // for backwards compatibility
			$list = get_defined_functions();
			foreach ($list['user'] as $fce) {
				$pair = explode('_prototype_', $fce);
				if (count($pair) === 2) {
					self::$extMethods[$pair[1]][$pair[0]] = $fce;
					self::$extMethods[$pair[1]][''] = NULL;
				}
			}
			if ($name === NULL) return NULL;
		}

		$class = strtolower($class);
		$l = & self::$extMethods[strtolower($name)];

		if ($callback !== NULL) { // works as setter
			/**/fixCallback($callback);/**/
			if (!is_callable($callback)) {
				$able = is_callable($callback, TRUE, $textual);
				throw new /*\*/InvalidArgumentException("Extension method handler '$textual' is not " . ($able ? 'callable.' : 'valid PHP callback.'));
			}
			$l[$class] = $callback;
			$l[''] = NULL;
			return NULL;
		}

		// works as getter
		if (empty($l)) {
			return FALSE;

		} elseif (isset($l[''][$class])) { // cached value
			return $l[''][$class];
		}

		$cl = $class;
		do {
			$cl = strtolower($cl);
			if (isset($l[$cl])) {
				return $l[''][$class] = $l[$cl];
			}
		} while (($cl = get_parent_class($cl)) !== FALSE);

		foreach (class_implements($class) as $cl) {
			$cl = strtolower($cl);
			if (isset($l[$cl])) {
				return $l[''][$class] = $l[$cl];
			}
		}
		return $l[''][$class] = FALSE;
	}



	/**
	 * Returns property value.
	 *
	 * @param  string  property name
	 * @return mixed   property value
	 * @throws \MemberAccessException if the property is not defined.
	 */
	public static function & get($_this, $name)
	{
		$class = get_class($_this);

		if ($name === '') {
			throw new /*\*/MemberAccessException("Cannot read a class '$class' property without name.");
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
			// - uses &__get() because declaration should be forward compatible (e.g. with Nette\Web\Html)
			// - doesn't call &$_this->$m because user could bypass property setter by: $x = & $obj->property; $x = 'new value';
			$val = $_this->$m();
			return $val;
		}

		$m = 'is' . $name;
		if (isset(self::$methods[$class][$m])) {
			$val = $_this->$m();
			return $val;
		}

		$name = func_get_arg(1);
		throw new /*\*/MemberAccessException("Cannot read an undeclared property $class::\$$name.");
	}



	/**
	 * Sets value of a property.
	 *
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 * @throws \MemberAccessException if the property is not defined or is read-only
	 */
	public static function set($_this, $name, $value)
	{
		$class = get_class($_this);

		if ($name === '') {
			throw new /*\*/MemberAccessException("Cannot assign to a class '$class' property without name.");
		}

		if (!isset(self::$methods[$class])) {
			self::$methods[$class] = array_flip(get_class_methods($class));
		}

		// property setter support
		$name[0] = $name[0] & "\xDF"; // case-sensitive checking, capitalize first character
		if (isset(self::$methods[$class]['get' . $name]) || isset(self::$methods[$class]['is' . $name])) {
			$m = 'set' . $name;
			if (isset(self::$methods[$class][$m])) {
				$_this->$m($value);
				return;

			} else {
				$name = func_get_arg(1);
				throw new /*\*/MemberAccessException("Cannot assign to a read-only property $class::\$$name.");
			}
		}

		$name = func_get_arg(1);
		throw new /*\*/MemberAccessException("Cannot assign to an undeclared property $class::\$$name.");
	}



	/**
	 * Is property defined?
	 *
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
