<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * Provides objects to work as array.
 */
class ArrayHash extends \stdClass implements \ArrayAccess, \Countable, \IteratorAggregate
{

	/**
	 * @return static
	 */
	public static function from(array $arr, bool $recursive = TRUE)
	{
		$obj = new static;
		foreach ($arr as $key => $value) {
			if ($recursive && is_array($value)) {
				$obj->$key = static::from($value, TRUE);
			} else {
				$obj->$key = $value;
			}
		}
		return $obj;
	}


	/**
	 * Returns an iterator over all items.
	 */
	public function getIterator(): \RecursiveArrayIterator
	{
		return new \RecursiveArrayIterator((array) $this);
	}


	/**
	 * Returns items count.
	 */
	public function count(): int
	{
		return count((array) $this);
	}


	/**
	 * Replaces or appends a item.
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		if (!is_scalar($key)) { // prevents NULL
			throw new Nette\InvalidArgumentException(sprintf('Key must be either a string or an integer, %s given.', gettype($key)));
		}
		$this->$key = $value;
	}


	/**
	 * Returns a item.
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		return $this->$key;
	}


	/**
	 * Determines whether a item exists.
	 */
	public function offsetExists($key): bool
	{
		return isset($this->$key);
	}


	/**
	 * Removes the element from this list.
	 * @return void
	 */
	public function offsetUnset($key)
	{
		unset($this->$key);
	}

}
