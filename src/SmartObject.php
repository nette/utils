<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette;

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
	public function __call(string $name, array $args): mixed
	{
		$class = static::class;

		if (ObjectHelpers::hasProperty($class, $name) === 'event') { // calling event handlers
			$handlers = $this->$name ?? null;
			if (is_iterable($handlers)) {
				foreach ($handlers as $handler) {
					$handler(...$args);
				}
			} elseif ($handlers !== null) {
				throw new UnexpectedValueException("Property $class::$$name must be iterable or null, " . gettype($handlers) . ' given.');
			}

			return null;
		}

		ObjectHelpers::strictCall($class, $name);
	}


	/**
	 * @throws MemberAccessException
	 */
	public static function __callStatic(string $name, array $args): mixed
	{
		ObjectHelpers::strictStaticCall(static::class, $name);
	}


	/**
	 * @throws MemberAccessException if the property is not defined.
	 */
	public function &__get(string $name): mixed
	{
		$class = static::class;

		if ($prop = ObjectHelpers::getMagicProperties($class)[$name] ?? null) { // property getter
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
	 * @throws MemberAccessException if the property is not defined or is read-only
	 */
	public function __set(string $name, mixed $value): void
	{
		$class = static::class;

		if (ObjectHelpers::hasProperty($class, $name)) { // unsetted property
			$this->$name = $value;

		} elseif ($prop = ObjectHelpers::getMagicProperties($class)[$name] ?? null) { // property setter
			if (!($prop & 0b1000)) {
				throw new MemberAccessException("Cannot write to a read-only property $class::\$$name.");
			}

			$this->{'set' . $name}($value);

		} else {
			ObjectHelpers::strictSet($class, $name);
		}
	}


	/**
	 * @throws MemberAccessException
	 */
	public function __unset(string $name): void
	{
		$class = static::class;
		if (!ObjectHelpers::hasProperty($class, $name)) {
			throw new MemberAccessException("Cannot unset the property $class::\$$name.");
		}
	}


	public function __isset(string $name): bool
	{
		return isset(ObjectHelpers::getMagicProperties(static::class)[$name]);
	}
}
