<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette;

use Nette\Utils\Callback;
use Nette\Utils\ObjectMixin;


/**
 * Strict class for better experience.
 * - 'did you mean' hints
 * - access to undeclared members throws exceptions
 * - support for @property annotations
 * - support for calling event handlers stored in $onEvent via onEvent()
 */
trait SmartObject
{

	/**
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function __call($name, $args)
	{
		$class = get_class($this);

		if (ObjectMixin::hasProperty($class, $name) === 'event') { // calling event handlers
			if (is_array($this->$name) || $this->$name instanceof \Traversable) {
				foreach ($this->$name as $handler) {
					Callback::invokeArgs($handler, $args);
				}
			} elseif ($this->$name !== NULL) {
				throw new UnexpectedValueException("Property $class::$$name must be array or NULL, " . gettype($this->$name) . ' given.');
			}

		} else {
			ObjectMixin::strictCall($class, $name);
		}
	}


	/**
	 * @return void
	 * @throws MemberAccessException
	 */
	public static function __callStatic($name, $args)
	{
		ObjectMixin::strictStaticCall(get_called_class(), $name);
	}


	/**
	 * @return mixed   property value
	 * @throws MemberAccessException if the property is not defined.
	 */
	public function &__get($name)
	{
		$class = get_class($this);

		if ($prop = ObjectMixin::getMagicProperties($class)[$name] ?? NULL) { // property getter
			if (!($prop & 0b0001)) {
				throw new MemberAccessException("Cannot read a write-only property $class::\$$name.");
			}
			$m = ($prop & 0b0010 ? 'get' : 'is') . $name;
			if ($prop & 0b0100) { // return by reference
				return $this->$m();
			} else {
				$val = $this->$m();
				return $val;
			}
		} else {
			ObjectMixin::strictGet($class, $name);
		}
	}


	/**
	 * @return void
	 * @throws MemberAccessException if the property is not defined or is read-only
	 */
	public function __set($name, $value)
	{
		$class = get_class($this);

		if (ObjectMixin::hasProperty($class, $name)) { // unsetted property
			$this->$name = $value;

		} elseif ($prop = ObjectMixin::getMagicProperties($class)[$name] ?? NULL) { // property setter
			if (!($prop & 0b1000)) {
				throw new MemberAccessException("Cannot write to a read-only property $class::\$$name.");
			}
			$this->{'set' . $name}($value);

		} else {
			ObjectMixin::strictSet($class, $name);
		}
	}


	/**
	 * @return void
	 * @throws MemberAccessException
	 */
	public function __unset($name)
	{
		$class = get_class($this);
		if (!ObjectMixin::hasProperty($class, $name)) {
			throw new MemberAccessException("Cannot unset the property $class::\$$name.");
		}
	}


	/**
	 * @return bool
	 */
	public function __isset($name)
	{
		return isset(ObjectMixin::getMagicProperties(get_class($this))[$name]);
	}

}
