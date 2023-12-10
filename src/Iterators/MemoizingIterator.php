<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Iterators;


/**
 * MemoizingIterator wraps around another iterator and caches its keys and values during iteration.
 * This allows the data to be re-iterated multiple times.
 * @template K
 * @template V
 * @implements \Iterator<K, V>
 */
class MemoizingIterator implements \Iterator
{
	private array $cache = [];
	private int $index = 0;


	/**
	 * @param  \Iterator<K, V>  $inner
	 */
	public function __construct(
		private readonly \Iterator $inner,
	) {
	}


	public function rewind(): void
	{
		if (!$this->cache) {
			$this->inner->rewind();
		}
		$this->index = 0;
	}


	/**
	 * @return V
	 */
	public function current(): mixed
	{
		return $this->cache[$this->index][1] ?? null;
	}


	/**
	 * @return K
	 */
	public function key(): mixed
	{
		return $this->cache[$this->index][0] ?? null;
	}


	public function next(): void
	{
		if (!isset($this->cache[++$this->index])) {
			$this->inner->next();
		}
	}


	public function valid(): bool
	{
		if (!isset($this->cache[$this->index]) && $this->inner->valid()) {
			$this->cache[$this->index] = [$this->inner->key(), $this->inner->current()];
		}
		return isset($this->cache[$this->index]);
	}
}
