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
 * Provides objects to work as array.
 *
 * @author     David Grudl
 */
class ArrayHash implements \ArrayAccess, \Countable, \IteratorAggregate
{

	/**
	 * @param  array to wrap
	 * @return ArrayHash
	 */
	public static function from($arr)
	{
		$obj = new static;
		foreach ($arr as $key => $value) {
			$obj->$key = $value;
		}
		return $obj;
	}



	/**
	 * Returns an iterator over all items.
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this);
	}



	/**
	 * Returns items count.
	 * @return int
	 */
	public function count()
	{
		return count((array) $this);
	}



	/**
	 * Replaces or appends a item.
	 * @param  mixed
	 * @param  mixed
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		if (!is_scalar($key)) { // prevents NULL
			throw new InvalidArgumentException("Key must be either a string or an integer, " . gettype($key) ." given.");
		}
		$this->$key = $value;
	}



	/**
	 * Returns a item.
	 * @param  mixed
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		return $this->$key;
	}



	/**
	 * Determines whether a item exists.
	 * @param  mixed
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return isset($this->$key);
	}



	/**
	 * Removes the element from this list.
	 * @param  mixed
	 * @return void
	 */
	public function offsetUnset($key)
	{
		unset($this->$key);
	}

}
