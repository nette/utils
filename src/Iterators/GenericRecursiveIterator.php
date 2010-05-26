<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 * @link       http://nette.org
 * @category   Nette
 * @package    Nette
 */

namespace Nette;

use Nette;



/**
 * Generic recursive iterator.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette
 */
class GenericRecursiveIterator extends \IteratorIterator implements \RecursiveIterator, \Countable
{

	/**
	 * Has the current element has children?
	 * @return bool
	 */
	public function hasChildren()
	{
		$obj = $this->current();
		return ($obj instanceof \IteratorAggregate && $obj->getIterator() instanceof \RecursiveIterator) || $obj instanceof \RecursiveIterator;
	}



	/**
	 * The sub-iterator for the current element.
	 * @return \RecursiveIterator
	 */
	public function getChildren()
	{
		$obj = $this->current();
		return $obj instanceof \IteratorAggregate ? $obj->getIterator() : $obj;
	}



	/**
	 * Returns the count of elements.
	 * @return int
	 */
	public function count()
	{
		return iterator_count($this);
	}

}
