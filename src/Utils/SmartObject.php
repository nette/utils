<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette;

use Nette\Utils\Callback;
use Nette\Utils\ObjectHelpers;


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
	 * @throws MemberAccessException
	 */
	public function __call($name, $args)
	{
		$class = get_class($this);

		if (ObjectHelpers::hasProperty($class, $name) === 'event') { // calling event handlers
			if (is_array($this->$name) || $this->$name instanceof \Traversable) {
				foreach ($this->$name as $handler) {
					Callback::invokeArgs($handler, $args);
				}
			} elseif ($this->$name !== NULL) {
				throw new UnexpectedValueException("Property $class::$$name must be array or NULL, " . gettype($this->$name) . ' given.');
			}

		} else {
			ObjectHelpers::strictCall($class, $name);
		}
	}


	/**
	 * @throws MemberAccessException
	 */
	public static function __callStatic($name, $args)
	{
		ObjectHelpers::strictStaticCall(get_called_class(), $name);
	}


	/**
	 * @return mixed
	 * @throws MemberAccessException if the property is not defined.
	 */
	public function &__get($name)
	{
		$class = get_class($this);

		if ($prop = ObjectHelpers::getMagicProperties($class)[$name] ?? NULL) { // property getter
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
			ObjectHelpers::strictGet($class, $name);
		}
	}


	/**
	 * @return void
	 * @throws MemberAccessException if the property is not defined or is read-only
	 */
	public function __set($name, $value)
	{
		$class = get_class($this);

		if (ObjectHelpers::hasProperty($class, $name)) { // unsetted property
			$this->$name = $value;

		} elseif ($prop = ObjectHelpers::getMagicProperties($class)[$name] ?? NULL) { // property setter
			if (!($prop & 0b1000)) {
				throw new MemberAccessException("Cannot write to a read-only property $class::\$$name.");
			}
			$this->{'set' . $name}($value);

		} else {
			ObjectHelpers::strictSet($class, $name);
		}
	}


	/**
	 * @return void
	 * @throws MemberAccessException
	 */
	public function __unset($name)
	{
		$class = get_class($this);
		if (!ObjectHelpers::hasProperty($class, $name)) {
			throw new MemberAccessException("Cannot unset the property $class::\$$name.");
		}
	}


	public function __isset($name): bool
	{
		return isset(ObjectHelpers::getMagicProperties(get_class($this))[$name]);
	}

}
