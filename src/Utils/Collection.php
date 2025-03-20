<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * Collection of items (probably of same type).
 */
abstract class Collection
{

	/** @var array */
	private $items = [];

	/** @var bool */
	private $keysMatter = FALSE;


	/**
	 * @return static
	 * @throws Nette\NotSupportedException
	 */
	public static function fromIterator(\Traversable $iterator, array $constructorArgs = [])
	{
		$me = new static(...array_values($constructorArgs));
		if (!method_exists($me, 'add')) {
			throw new Nette\NotSupportedException(__METHOD__ . '() requires ' . get_class($me) . '::add() to be implemented.');
		}

		foreach ($iterator as $item) {
			$me->add($item);
		}

		return $me;
	}


	/**
	 * @return static
	 * @throws Nette\NotSupportedException
	 */
	public static function fromArray(array $items, array $constructorArgs = [])
	{
		return static::fromIterator(new \ArrayIterator($items), $constructorArgs);
	}


	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->items);
	}


	/**
	 * @param  mixed
	 * @return bool
	 */
	public function has($key)
	{
		return array_key_exists($this->normalizeKey($key), $this->items);
	}


	/**
	 * @return array
	 */
	public function getKeys()
	{
		return array_keys($this->items);
	}


	/**
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->items);
	}


	/**
	 * @param  callable
	 * @return static
	 */
	public function filter(callable $cb)
	{
		$me = clone $this;
		$me->items = array_filter($this->items, $cb, ARRAY_FILTER_USE_BOTH);
		return $me;
	}


	/**
	 * @param  callable
	 * @return static
	 */
	public function walk(callable $cb)
	{
		array_walk($this->items, $cb);
		return $this;
	}


	/**
	 * @param  callable
	 * @return array
	 */
	public function convert(callable $cb)
	{
		$result = [];
		foreach ($this->items as $key => $item) {
			$unset = FALSE;
			$item = $cb($item, $key, $unset);
			if (!$unset) {
				if ($key === NULL) {
					$result[] = $item;
				} else {
					$result[$key] = $item;
				}
			}
		}

		return $result;
	}


	/**
	 * @param  callable $cb
	 * @return static
	 */
	public function sortBy(callable $cb)
	{
		if ($this->keysMatter) {
			uasort($this->items, $cb);
		} else {
			usort($this->items, $cb);
		}

		return $this;
	}


	/**
	 * @param  mixed $key
	 * @return int|string
	 */
	protected function normalizeKey($key)
	{
		return $key;
	}


	/**
	 * @param  mixed
	 * @param  mixed
	 * @throws Nette\ArgumentOutOfRangeException
	 * @return void
	 */
	protected function addItem($item, $key)
	{
		$key = $this->normalizeKey($key);

		if ($key === NULL) {
			$this->items[] = $item;
		} else {
			if (array_key_exists($key, $this->items)) {
				throw new Nette\ArgumentOutOfRangeException("Item with key '$key' already exists in " . get_class($this) . " collection.");
			}
			$this->items[$key] = $item;
			$this->keysMatter = TRUE;
		}
	}


	/**
	 * @param  mixed
	 * @return mixed
	 * @throws ItemNotFoundException
	 */
	protected function getItem($key)
	{
		$key = $this->normalizeKey($key);

		if (!array_key_exists($key, $this->items)) {
			throw new ItemNotFoundException("Item with key '$key' was not found in collection.");
		}

		return $this->items[$key];
	}

}
