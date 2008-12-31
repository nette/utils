<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 * @version    $Id$
 */

/*namespace Nette;*/



/**
 * Paginating math.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette
 */
class Paginator extends Object
{
	/** @var int */
	private $base = 1;

	/** @var int */
	private $itemsPerPage;

	/** @var int */
	private $page;

	/** @var int */
	private $itemCount = 0;



	/**
	 * Sets current page number.
	 * @param  int
	 * @return void
	 */
	public function setPage($page)
	{
		$this->page = (int) $page;
	}



	/**
	 * Returns current page number.
	 * @return int
	 */
	public function getPage()
	{
		return $this->base + $this->getPageIndex();
	}



	/**
	 * Sets first page (base) number.
	 * @param  int
	 * @return void
	 */
	public function setBase($base)
	{
		$this->base = (int) $base;
	}



	/**
	 * Returns first page (base) number.
	 * @return int
	 */
	public function getBase()
	{
		return $this->base;
	}



	/**
	 * Returns zero-based page number.
	 * @return int
	 */
	protected function getPageIndex()
	{
		return min(max(0, $this->page - $this->base), max(0, $this->getPageCount() - 1));
	}



	/**
	 * Is the current page the first one?
	 * @return bool
	 */
	public function isFirst()
	{
		return $this->getPageIndex() === 0;
	}



	/**
	 * Is the current page the last one?
	 * @return bool
	 */
	public function isLast()
	{
		return $this->getPageIndex() === $this->getPageCount() - 1;
	}



	/**
	 * Returns the total number of pages.
	 * @return int
	 */
	public function getPageCount()
	{
		return (int) ceil($this->itemCount / $this->itemsPerPage);
	}



	/**
	 * Sets the number of items to display on a single page.
	 * @param  int
	 * @return void
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = max(1, (int) $itemsPerPage);
	}



	/**
	 * Returns the number of items to display on a single page.
	 * @return int
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}



	/**
	 * Sets the total number of items.
	 * @param  int
	 * @return void
	 */
	public function setItemCount($itemCount)
	{
		$this->itemCount = max(0, (int) $itemCount);
	}



	/**
	 * Returns the total number of items.
	 * @return int
	 */
	public function getItemCount()
	{
		return $this->itemCount;
	}



	/**
	 * Returns the absolute index of the first item on current page.
	 * @return int
	 */
	public function getOffset()
	{
		return $this->getPageIndex() * $this->itemsPerPage;
	}



	/**
	 * Returns the absolute index of the first item on current page in countdown paging.
	 * @return int
	 */
	public function getCountdownOffset()
	{
		return max(0, $this->itemCount - ($this->getPageIndex() + 1) * $this->itemsPerPage);
	}



	/**
	 * Returns the number of items on current page.
	 * @return int
	 */
	public function getLength()
	{
		return min($this->itemsPerPage, $this->itemCount - $this->getPageIndex() * $this->itemsPerPage);
	}



	/**
	 * Generates list of pages used for visual control. (experimental)
	 * @return array
	 */
	public function getSteps($steps = 5, $surround = 3)
	{
		$lastPage = $this->getPageCount() - 1;
		$page = $this->getPageIndex();
		if ($lastPage < 1) return array($page + $this->base);

		$surround = max(0, $surround);
		$arr = range(max(0, $page - $surround) + $this->base, min($lastPage, $page + $surround) + $this->base);

		$steps = max(1, $steps - 1);
		for ($i = 0; $i <= $steps; $i++) $arr[] = round($lastPage / $steps * $i) + $this->base;
		sort($arr);
		return array_values(array_unique($arr));
	}

}
