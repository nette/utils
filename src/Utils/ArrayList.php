<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * Provides the base class for a generic list (items can be accessed by index).
 */
class ArrayList implements \ArrayAccess, \Countable, \IteratorAggregate
{
	use Nette\SmartObject;

	private $list = [];


	/**
	 * Returns an iterator over all items.
	 */
	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->list);
	}


	/**
	 * Returns items count.
	 */
	public function count(): int
	{
		return count($this->list);
	}


	/**
	 * Replaces or appends a item.
	 * @param  int|NULL
	 * @return void
	 * @throws Nette\OutOfRangeException
	 */
	public function offsetSet($index, $value)
	{
		if ($index === NULL) {
			$this->list[] = $value;

		} elseif ($index < 0 || $index >= count($this->list)) {
			throw new Nette\OutOfRangeException('Offset invalid or out of range');

		} else {
			$this->list[(int) $index] = $value;
		}
	}


	/**
	 * Returns a item.
	 * @param  int
	 * @return mixed
	 * @throws Nette\OutOfRangeException
	 */
	public function offsetGet($index)
	{
		if ($index < 0 || $index >= count($this->list)) {
			throw new Nette\OutOfRangeException('Offset invalid or out of range');
		}
		return $this->list[(int) $index];
	}


	/**
	 * Determines whether a item exists.
	 * @param  int
	 */
	public function offsetExists($index): bool
	{
		return $index >= 0 && $index < count($this->list);
	}


	/**
	 * Removes the element at the specified position in this list.
	 * @param  int
	 * @return void
	 * @throws Nette\OutOfRangeException
	 */
	public function offsetUnset($index)
	{
		if ($index < 0 || $index >= count($this->list)) {
			throw new Nette\OutOfRangeException('Offset invalid or out of range');
		}
		array_splice($this->list, (int) $index, 1);
	}


	/**
	 * Prepends a item.
	 * @return void
	 */
	public function prepend($value)
	{
		$first = array_slice($this->list, 0, 1);
		$this->offsetSet(0, $value);
		array_splice($this->list, 1, 0, $first);
	}

}
