<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types = 1);

namespace Nette\Iterators;

use Nette;
use Nette\Utils\ObjectMixin;


/**
 * Smarter caching iterator.
 *
 * @property-read bool $first
 * @property-read bool $last
 * @property-read bool $empty
 * @property-read bool $odd
 * @property-read bool $even
 * @property-read int $counter
 * @property-read mixed $nextKey
 * @property-read mixed $nextValue
 */
class CachingIterator extends \CachingIterator implements \Countable
{
	/** @var int */
	private $counter = 0;


	public function __construct($iterator)
	{
		if (is_array($iterator) || $iterator instanceof \stdClass) {
			$iterator = new \ArrayIterator($iterator);

		} elseif ($iterator instanceof \IteratorAggregate) {
			do {
				$iterator = $iterator->getIterator();
			} while ($iterator instanceof \IteratorAggregate);

		} elseif ($iterator instanceof \Traversable) {
			if (!$iterator instanceof \Iterator) {
				$iterator = new \IteratorIterator($iterator);
			}
		} else {
			throw new Nette\InvalidArgumentException(sprintf('Invalid argument passed to %s; array or Traversable expected, %s given.', __CLASS__, is_object($iterator) ? get_class($iterator) : gettype($iterator)));
		}

		parent::__construct($iterator, 0);
	}


	/**
	 * Is the current element the first one?
	 * @param  int  grid width
	 * @return bool
	 */
	public function isFirst(int $width = NULL): bool
	{
		return $this->counter === 1 || ($width && $this->counter !== 0 && (($this->counter - 1) % $width) === 0);
	}


	/**
	 * Is the current element the last one?
	 * @param  int  grid width
	 * @return bool
	 */
	public function isLast(int $width = NULL): bool
	{
		return !$this->hasNext() || ($width && ($this->counter % $width) === 0);
	}


	/**
	 * Is the iterator empty?
	 * @return bool
	 */
	public function isEmpty(): bool
	{
		return $this->counter === 0;
	}


	/**
	 * Is the counter odd?
	 * @return bool
	 */
	public function isOdd(): bool
	{
		return $this->counter % 2 === 1;
	}


	/**
	 * Is the counter even?
	 * @return bool
	 */
	public function isEven(): bool
	{
		return $this->counter % 2 === 0;
	}


	/**
	 * Returns the counter.
	 * @return int
	 */
	public function getCounter(): int
	{
		return $this->counter;
	}


	/**
	 * Returns the count of elements.
	 * @return int
	 */
	public function count(): int
	{
		$inner = $this->getInnerIterator();
		if ($inner instanceof \Countable) {
			return $inner->count();

		} else {
			throw new Nette\NotSupportedException('Iterator is not countable.');
		}
	}


	/**
	 * Forwards to the next element.
	 * @return void
	 */
	public function next()
	{
		parent::next();
		if (parent::valid()) {
			$this->counter++;
		}
	}


	/**
	 * Rewinds the Iterator.
	 * @return void
	 */
	public function rewind()
	{
		parent::rewind();
		$this->counter = parent::valid() ? 1 : 0;
	}


	/**
	 * Returns the next key.
	 * @return mixed
	 */
	public function getNextKey()
	{
		return $this->getInnerIterator()->key();
	}


	/**
	 * Returns the next element.
	 * @return mixed
	 */
	public function getNextValue()
	{
		return $this->getInnerIterator()->current();
	}


	/********************* Nette\Object behaviour ****************d*g**/


	/**
	 * Call to undefined method.
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws Nette\MemberAccessException
	 */
	public function __call($name, $args)
	{
		return ObjectMixin::call($this, $name, $args);
	}


	/**
	 * Returns property value. Do not call directly.
	 * @param  string  property name
	 * @return mixed   property value
	 * @throws Nette\MemberAccessException if the property is not defined.
	 */
	public function &__get(string $name)
	{
		return ObjectMixin::get($this, $name);
	}


	/**
	 * Sets value of a property. Do not call directly.
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 * @throws Nette\MemberAccessException if the property is not defined or is read-only
	 */
	public function __set(string $name, $value)
	{
		ObjectMixin::set($this, $name, $value);
	}


	/**
	 * Is property defined?
	 * @param  string  property name
	 * @return bool
	 */
	public function __isset(string $name): bool
	{
		return ObjectMixin::has($this, $name);
	}


	/**
	 * Access to undeclared property.
	 * @param  string  property name
	 * @return void
	 * @throws Nette\MemberAccessException
	 */
	public function __unset(string $name)
	{
		ObjectMixin::remove($this, $name);
	}


}
