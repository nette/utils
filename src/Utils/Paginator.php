<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * Paginating math.
 *
 * @property   int $page
 * @property-read int $firstPage
 * @property-read int|NULL $lastPage
 * @property   int $base
 * @property-read bool $first
 * @property-read bool $last
 * @property-read int|NULL $pageCount
 * @property   int $itemsPerPage
 * @property   int|NULL $itemCount
 * @property-read int $offset
 * @property-read int|NULL $countdownOffset
 * @property-read int|NULL $length
 */
class Paginator
{
	use Nette\SmartObject;

	/** @var int */
	private $base = 1;

	/** @var int */
	private $itemsPerPage = 1;

	/** @var int */
	private $page = 1;

	/** @var int|NULL */
	private $itemCount;


	/**
	 * Sets current page number.
	 * @return static
	 */
	public function setPage(int $page)
	{
		$this->page = $page;
		return $this;
	}


	/**
	 * Returns current page number.
	 */
	public function getPage(): int
	{
		return $this->base + $this->getPageIndex();
	}


	/**
	 * Returns first page number.
	 */
	public function getFirstPage(): int
	{
		return $this->base;
	}


	/**
	 * Returns last page number.
	 * @return int|NULL
	 */
	public function getLastPage()
	{
		return $this->itemCount === NULL ? NULL : $this->base + max(0, $this->getPageCount() - 1);
	}


	/**
	 * Sets first page (base) number.
	 * @return static
	 */
	public function setBase(int $base)
	{
		$this->base = $base;
		return $this;
	}


	/**
	 * Returns first page (base) number.
	 */
	public function getBase(): int
	{
		return $this->base;
	}


	/**
	 * Returns zero-based page number.
	 */
	protected function getPageIndex(): int
	{
		$index = max(0, $this->page - $this->base);
		return $this->itemCount === NULL ? $index : min($index, max(0, $this->getPageCount() - 1));
	}


	/**
	 * Is the current page the first one?
	 */
	public function isFirst(): bool
	{
		return $this->getPageIndex() === 0;
	}


	/**
	 * Is the current page the last one?
	 */
	public function isLast(): bool
	{
		return $this->itemCount === NULL ? FALSE : $this->getPageIndex() >= $this->getPageCount() - 1;
	}


	/**
	 * Returns the total number of pages.
	 * @return int|NULL
	 */
	public function getPageCount()
	{
		return $this->itemCount === NULL ? NULL : (int) ceil($this->itemCount / $this->itemsPerPage);
	}


	/**
	 * Sets the number of items to display on a single page.
	 * @return static
	 */
	public function setItemsPerPage(int $itemsPerPage)
	{
		$this->itemsPerPage = max(1, $itemsPerPage);
		return $this;
	}


	/**
	 * Returns the number of items to display on a single page.
	 */
	public function getItemsPerPage(): int
	{
		return $this->itemsPerPage;
	}


	/**
	 * Sets the total number of items.
	 * @return static
	 */
	public function setItemCount(int $itemCount = NULL)
	{
		$this->itemCount = $itemCount === NULL ? NULL : max(0, $itemCount);
		return $this;
	}


	/**
	 * Returns the total number of items.
	 * @return int|NULL
	 */
	public function getItemCount()
	{
		return $this->itemCount;
	}


	/**
	 * Returns the absolute index of the first item on current page.
	 */
	public function getOffset(): int
	{
		return $this->getPageIndex() * $this->itemsPerPage;
	}


	/**
	 * Returns the absolute index of the first item on current page in countdown paging.
	 * @return int|NULL
	 */
	public function getCountdownOffset()
	{
		return $this->itemCount === NULL
			? NULL
			: max(0, $this->itemCount - ($this->getPageIndex() + 1) * $this->itemsPerPage);
	}


	/**
	 * Returns the number of items on current page.
	 * @return int|NULL
	 */
	public function getLength()
	{
		return $this->itemCount === NULL
			? $this->itemsPerPage
			: min($this->itemsPerPage, $this->itemCount - $this->getPageIndex() * $this->itemsPerPage);
	}

}
