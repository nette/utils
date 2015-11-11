<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;
use Nette\MemberAccessException;


/**
 * Nette\Object behaviour mixin.
 */
class ObjectMixin
{
	/** @var array (name => 0 | bool | array)  used by getMethods() */
	private static $methods;

	/** @var array (name => 'event' | TRUE)  used by hasProperty() */
	private static $props;

	/** @var array (name => array(type => callback))  used by get|setExtensionMethod() */
	private static $extMethods;


	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new Nette\StaticClassException;
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
			if (is_array($_this->$name) || $_this->$name instanceof \Traversable) {
				foreach ($_this->$name as $handler) {
					Callback::invokeArgs($handler, $args);
				}
			} elseif ($_this->$name !== NULL) {
				throw new Nette\UnexpectedValueException("Property $class::$$name must be array or NULL, " . gettype($_this->$name) . ' given.');
			}

		} elseif (($methods = & self::getMethods($class)) && isset($methods[$name]) && is_array($methods[$name])) { // magic @methods
			list($op, $rp, $type) = $methods[$name];
			if (count($args) !== ($op === 'get' ? 0 : 1)) {
				throw new Nette\InvalidArgumentException("$class::$name() expects " . ($op === 'get' ? 'no' : '1') . ' argument, ' . count($args) . ' given.');

			} elseif ($type && $args && !self::checkType($args[0], $type)) {
				throw new Nette\InvalidArgumentException("Argument passed to $class::$name() must be $type, " . gettype($args[0]) . ' given.');
			}

			if ($op === 'get') {
				return $rp->getValue($_this);
			} elseif ($op === 'set') {
				$rp->setValue($_this, $args[0]);
			} elseif ($op === 'add') {
				$val = $rp->getValue($_this);
				$val[] = $args[0];
				$rp->setValue($_this, $val);
			}
			return $_this;

		} elseif ($cb = self::getExtensionMethod($class, $name)) { // extension methods
			array_unshift($args, $_this);
			return Callback::invokeArgs($cb, $args);

		} else {
			$hint = self::getSuggestion(array_merge(
				get_class_methods($class),
				self::parseFullDoc($class, '~^[ \t*]*@method[ \t]+(?:\S+[ \t]+)??(\w+)\(~m'),
				array_keys(self::getExtensionMethods($class))
			), $name);

			if (method_exists($class, $name)) { // called parent::$name()
				$class = 'parent';
			}
			throw new MemberAccessException("Call to undefined method $class::$name()" . ($hint ? ", did you mean $hint()?" : '.'));
		}
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
		$hint = self::getSuggestion(array_filter(
			get_class_methods($class),
			function ($m) use ($class) { $rm = new \ReflectionMethod($class, $m); return $rm->isStatic(); }
		), $method);
		throw new MemberAccessException("Call to undefined static method $class::$method()" . ($hint ? ", did you mean $hint()?" : '.'));
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
		$methods = & self::getMethods($class);

		if ($name === '') {
			throw new MemberAccessException("Cannot read a class '$class' property without name.");

		} elseif (isset($methods[$m = 'get' . $uname]) || isset($methods[$m = 'is' . $uname])) { // property getter
			if ($methods[$m] === 0) {
				$rm = new \ReflectionMethod($class, $m);
				$methods[$m] = $rm->returnsReference();
			}
			if ($methods[$m] === TRUE) {
				return $_this->$m();
			} else {
				$val = $_this->$m();
				return $val;
			}

		} elseif (isset($methods[$name])) { // public method as closure getter
			if (preg_match('#^(is|get|has)([A-Z]|$)#', $name) && ($rm = new \ReflectionMethod($class, $name)) && !$rm->getNumberOfRequiredParameters()) {
				$source = '';
				foreach (debug_backtrace(PHP_VERSION_ID >= 50306 ? DEBUG_BACKTRACE_IGNORE_ARGS : FALSE) as $item) {
					if (isset($item['file']) && dirname($item['file']) !== __DIR__) {
						$source = " in $item[file]:$item[line]";
						break;
					}
				}
				trigger_error("Did you forgot parentheses after $name$source?", E_USER_WARNING);
			}
			$val = Callback::closure($_this, $name);
			return $val;

		} elseif (isset($methods['set' . $uname])) { // strict class
			throw new MemberAccessException("Cannot read a write-only property $class::\$$name.");

		} else { // strict class
			$hint = self::getSuggestion(array_merge(
				array_keys(get_class_vars($class)),
				self::parseFullDoc($class, '~^[ \t*]*@property(?:-read)?[ \t]+(?:\S+[ \t]+)??\$(\w+)~m')
			), $name);
			throw new MemberAccessException("Cannot read an undeclared property $class::\$$name" . ($hint ? ", did you mean \$$hint?" : '.'));
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
		$methods = & self::getMethods($class);

		if ($name === '') {
			throw new MemberAccessException("Cannot write to a class '$class' property without name.");

		} elseif (self::hasProperty($class, $name)) { // unsetted property
			$_this->$name = $value;

		} elseif (isset($methods[$m = 'set' . $uname])) { // property setter
			$_this->$m($value);

		} elseif (isset($methods['get' . $uname]) || isset($methods['is' . $uname])) { // strict class
			throw new MemberAccessException("Cannot write to a read-only property $class::\$$name.");

		} else { // strict class
			$hint = self::getSuggestion(array_merge(
				array_keys(get_class_vars($class)),
				self::parseFullDoc($class, '~^[ \t*]*@property(?:-write)?[ \t]+(?:\S+[ \t]+)??\$(\w+)~m')
			), $name);
			throw new MemberAccessException("Cannot write to an undeclared property $class::\$$name" . ($hint ? ", did you mean \$$hint?" : '.'));
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
		$name = ucfirst($name);
		$methods = & self::getMethods(get_class($_this));
		return $name !== '' && (isset($methods['get' . $name]) || isset($methods['is' . $name]));
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
				if ($rp->isPublic() && !$rp->isStatic()) {
					$prop = $name >= 'onA' && $name < 'on_' ? 'event' : TRUE;
				}
			} catch (\ReflectionException $e) {
			}
		}
		return $prop;
	}


	/**
	 * Returns array of public (static, non-static and magic) methods.
	 * @return array
	 */
	private static function & getMethods($class)
	{
		if (!isset(self::$methods[$class])) {
			self::$methods[$class] = array_fill_keys(get_class_methods($class), 0) + self::getMagicMethods($class);
			if ($parent = get_parent_class($class)) {
				self::$methods[$class] += self::getMethods($parent);
			}
		}
		return self::$methods[$class];
	}


	/**
	 * Returns array of magic methods defined by annotation @method.
	 * @return array
	 */
	public static function getMagicMethods($class)
	{
		$rc = new \ReflectionClass($class);
		preg_match_all('~^
			[ \t*]*  @method  [ \t]+
			(?: [^\s(]+  [ \t]+ )?
			(set|get|is|add)  ([A-Z]\w*)  [ \t]*
			(?: \(  [ \t]* ([^)$\s]+)  )?
		()~mx', $rc->getDocComment(), $matches, PREG_SET_ORDER);

		$methods = array();
		foreach ($matches as $m) {
			list(, $op, $prop, $type) = $m;
			$name = $op . $prop;
			$prop = strtolower($prop[0]) . substr($prop, 1) . ($op === 'add' ? 's' : '');
			if ($rc->hasProperty($prop) && ($rp = $rc->getProperty($prop)) && !$rp->isStatic()) {
				$rp->setAccessible(TRUE);
				if ($op === 'get' || $op === 'is') {
					$type = NULL;
					$op = 'get';
				} elseif (!$type && preg_match('#@var[ \t]+(\S+)' . ($op === 'add' ? '\[\]#' : '#'), $rp->getDocComment(), $m)) {
					$type = $m[1];
				}
				if ($rc->inNamespace() && preg_match('#^[A-Z]\w+(\[|\||\z)#', $type)) {
					$type = $rc->getNamespaceName() . '\\' . $type;
				}
				$methods[$name] = array($op, $rp, $type);
			}
		}
		return $methods;
	}


	/**
	 * Finds whether a variable is of expected type and do non-data-loss conversion.
	 * @return bool
	 * @internal
	 */
	public static function checkType(& $val, $type)
	{
		if (strpos($type, '|') !== FALSE) {
			$found = NULL;
			foreach (explode('|', $type) as $type) {
				$tmp = $val;
				if (self::checkType($tmp, $type)) {
					if ($val === $tmp) {
						return TRUE;
					}
					$found[] = $tmp;
				}
			}
			if ($found) {
				$val = $found[0];
				return TRUE;
			}
			return FALSE;

		} elseif (substr($type, -2) === '[]') {
			if (!is_array($val)) {
				return FALSE;
			}
			$type = substr($type, 0, -2);
			$res = array();
			foreach ($val as $k => $v) {
				if (!self::checkType($v, $type)) {
					return FALSE;
				}
				$res[$k] = $v;
			}
			$val = $res;
			return TRUE;
		}

		switch (strtolower($type)) {
			case NULL:
			case 'mixed':
				return TRUE;
			case 'bool':
			case 'boolean':
				return ($val === NULL || is_scalar($val)) && settype($val, 'bool');
			case 'string':
				return ($val === NULL || is_scalar($val) || (is_object($val) && method_exists($val, '__toString'))) && settype($val, 'string');
			case 'int':
			case 'integer':
				return ($val === NULL || is_bool($val) || is_numeric($val)) && ((float) (int) $val === (float) $val) && settype($val, 'int');
			case 'float':
				return ($val === NULL || is_bool($val) || is_numeric($val)) && settype($val, 'float');
			case 'scalar':
			case 'array':
			case 'object':
			case 'callable':
			case 'resource':
			case 'null':
				return call_user_func("is_$type", $val);
			default:
				return $val instanceof $type;
		}
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
		$name = strtolower($name);
		self::$extMethods[$name][$class] = Callback::check($callback);
		self::$extMethods[$name][''] = NULL;
	}


	/**
	 * Returns extension method.
	 * @param  string
	 * @param  string
	 * @return mixed
	 */
	public static function getExtensionMethod($class, $name)
	{
		$list = & self::$extMethods[strtolower($name)];
		$cache = & $list[''][$class];
		if (isset($cache)) {
			return $cache;
		}

		foreach (array($class) + class_parents($class) + class_implements($class) as $cl) {
			if (isset($list[$cl])) {
				return $cache = $list[$cl];
			}
		}
		return $cache = FALSE;
	}


	/**
	 * Returns extension methods.
	 * @param  string
	 * @return array
	 */
	public static function getExtensionMethods($class)
	{
		$res = array();
		foreach (array_keys(self::$extMethods) as $name) {
			if ($cb = self::getExtensionMethod($class, $name)) {
				$res[$name] = $cb;
			}
		}
		return $res;
	}


	/**
	 * Finds the best suggestion (for 8-bit encoding).
	 * @return string|NULL
	 * @internal
	 */
	public static function getSuggestion(array $items, $value)
	{
		$norm = preg_replace($re = '#^(get|set|has|is|add)(?=[A-Z])#', '', $value);
		$best = NULL;
		$min = (strlen($value) / 4 + 1) * 10 + .1;
		foreach (array_unique($items) as $item) {
			if ($item !== $value && (
				($len = levenshtein($item, $value, 10, 11, 10)) < $min
				|| ($len = levenshtein(preg_replace($re, '', $item), $norm, 10, 11, 10) + 20) < $min
			)) {
				$min = $len;
				$best = $item;
			}
		}
		return $best;
	}


	private static function parseFullDoc($class, $pattern)
	{
		$rc = new \ReflectionClass($class);
		do {
			$doc[] = $rc->getDocComment();
		} while ($rc = $rc->getParentClass());
		return preg_match_all($pattern, implode($doc), $m) ? $m[1] : array();
	}

}
