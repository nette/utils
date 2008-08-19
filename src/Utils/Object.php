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



require_once dirname(__FILE__) . '/exceptions.php';



/**
 * Nette::Object is the ultimate ancestor of all instantiable classes.
 *
 * It defines some handful methods and enhances object core of PHP:
 *   - access to undeclared members throws exceptions
 *   - support for conventional properties with getters and setters
 *   - support for event raising functionality
 *   - ability to add new methods to class (extension methods)
 *
 * Properties is a syntactic sugar which allows access public getter and setter
 * methods as normal object variables. A property is defined by a getter method
 * and optional setter method (no setter method means read-only property).
 * <code>
 * $val = $obj->label;     // equivalent to $val = $obj->getLabel();
 * $obj->label = 'Nette';  // equivalent to $obj->setLabel('Nette');
 * </code>
 * Property names are case-sensitive, and they are written in the camelCaps
 * or PascalCaps.
 *
 * Event functionality is provided by declaration of property named 'on{Something}'
 * Multiple handlers are allowed.
 * <code>
 * public $onClick;                // declaration in class
 * $this->onClick[] = 'callback';  // attaching event handler
 * if (!empty($this->onClick)) ... // are there any handlers?
 * $this->onClick($sender, $arg);  // raises the event with arguments
 * </code>
 *
 * Adding method to class (i.e. to all instances) works similar to JavaScript
 * prototype property. The syntax for adding a new method is:
 * <code>
 * MyClass::extensionMethod('newMethod', function(MyClass $obj, $arg, ...) { ... });
 * $obj = new MyClass;
 * $obj->newMethod($x);
 * </code>
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette
 */
abstract class Object
{
	/** @var array (method => array(type => callback)) */
	private static $extMethods;



	/**
	 * Returns the name of the class of this object.
	 *
	 * @return string
	 */
	final public /*static*/ function getClass()
	{
		return /*get_called_class()*/ /**/get_class($this)/**/;
	}



	/**
	 * Access to reflection.
	 *
	 * @return ReflectionObject
	 */
	final public function getReflection()
	{
		return new ReflectionObject($this);
	}



	/**
	 * Call to undefined method.
	 *
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws ::MemberAccessException
	 */
	public function __call($name, $args)
	{
		$class = get_class($this);

		if ($name === '') {
			throw new /*::*/MemberAccessException("Call to class '$class' method without name.");
		}

		// event functionality
		if (self::hasEvent($class, $name)) {
			$list = $this->$name;
			if (is_array($list) || $list instanceof Traversable) {
				foreach ($list as $handler) {
					call_user_func_array($handler, $args);
				}
			}
			return;
		}

		// extension methods
		if ($cb = self::extensionMethod("$class::$name")) {
			array_unshift($args, $this);
			return call_user_func_array($cb, $args);
		}

		throw new /*::*/MemberAccessException("Call to undefined method $class::$name().");
	}



	/**
	 * Call to undefined static method.
	 *
	 * @param  string  method name (in lower case!)
	 * @param  array   arguments
	 * @return mixed
	 * @throws ::MemberAccessException
	 */
	public static function __callStatic($name, $args)
	{
		$class = get_called_class();
		throw new /*::*/MemberAccessException("Call to undefined static method $class::$name().");
	}



	/**
	 * Adding method to class.
	 *
	 * @param  string  method name
	 * @param  mixed   callback or closure
	 * @return mixed
	 */
	public static function extensionMethod($name, $callback = NULL)
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
			if ($name === NULL) return;
		}

		$name = strtolower($name);
		$a = strrpos($name, ':'); // search ::
		if ($a === FALSE) {
			$class = strtolower(get_called_class());
			$l = & self::$extMethods[$name];
		} else {
			$class = substr($name, 0, $a - 1);
			$l = & self::$extMethods[substr($name, $a + 1)];
		}

		if ($callback !== NULL) { // works as setter
			$l[$class] = $callback;
			$l[''] = NULL;
			return;
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
	 * Returns property value. Do not call directly.
	 *
	 * @param  string  property name
	 * @return mixed   property value
	 * @throws ::MemberAccessException if the property is not defined.
	 */
	public function &__get($name)
	{
		$class = get_class($this);

		if ($name === '') {
			throw new /*::*/MemberAccessException("Cannot read an class '$class' property without name.");
		}

		// property getter support
		$m = 'get' . $name;
		if (self::hasAccessor($class, $m)) {
			// ampersands:
			// - uses &__get() because declaration should be forward compatible (e.g. with Nette::Web::Html)
			// - doesn't call &$this->$m because user could bypass property setter by: $x = & $obj->property; $x = 'new value';
			$val = $this->$m();
			return $val;

		} else {
			throw new /*::*/MemberAccessException("Cannot read an undeclared property $class::\$$name.");
		}
	}



	/**
	 * Sets value of a property. Do not call directly.
	 *
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 * @throws ::MemberAccessException if the property is not defined or is read-only
	 */
	public function __set($name, $value)
	{
		$class = get_class($this);

		if ($name === '') {
			throw new /*::*/MemberAccessException("Cannot assign to an class '$class' property without name.");
		}

		// property setter support
		if (self::hasAccessor($class, 'get' . $name)) {
			$m = 'set' . $name;
			if (self::hasAccessor($class, $m)) {
				$this->$m($value);

			} else {
				throw new /*::*/MemberAccessException("Cannot assign to a read-only property $class::\$$name.");
			}

		} else {
			throw new /*::*/MemberAccessException("Cannot assign to an undeclared property $class::\$$name.");
		}
	}



	/**
	 * Is property defined?
	 *
	 * @param  string  property name
	 * @return bool
	 */
	public function __isset($name)
	{
		return $name !== '' && self::hasAccessor(get_class($this), 'get' . $name);
	}



	/**
	 * Access to undeclared property.
	 *
	 * @param  string  property name
	 * @return void
	 * @throws ::MemberAccessException
	 */
	public function __unset($name)
	{
		$class = get_class($this);
		throw new /*::*/MemberAccessException("Cannot unset an property $class::\$$name.");
	}



	/**
	 * Has property an accessor?
	 *
	 * @param  string  class name
	 * @param  string  method name
	 * @return bool
	 */
	private static function hasAccessor($c, $m)
	{
		static $cache;
		if (!isset($cache[$c])) {
			// get_class_methods returns private, protected and public methods of Object (doesn't matter)
			// and ONLY PUBLIC methods of descendants (perfect!)
			// but returns static methods too (nothing doing...)
			// and is much faster than reflection
			// (works good since 5.0.4)
			$cache[$c] = array_flip(get_class_methods($c));
		}
		// case-sensitive checking, capitalize the fourth character
		$m[3] = $m[3] & "\xDF";
		return isset($cache[$c][$m]);
	}



	/**
	 * Is property an event?
	 *
	 * @param  string  class name
	 * @param  string  method name
	 * @return bool
	 */
	private static function hasEvent($c, $m)
	{
		return preg_match('#^on[A-Z]#', $m) && property_exists($c, $m);
	}

}
