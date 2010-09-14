<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2010 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

namespace Nette;

use Nette;



/**
 * Instance iterator filter.
 *
 * @author     David Grudl
 */
class InstanceFilterIterator extends \FilterIterator implements \Countable
{
	/** @var string */
	private $type;


	/**
	 * Constructs a filter around another iterator.
	 * @param  Iterator
	 * @param  string  class/interface name
	 */
	public function __construct(\Iterator $iterator, $type)
	{
		$this->type = $type;
		parent::__construct($iterator);
	}



	/**
	 * Expose the current element of the inner iterator?
	 * @return bool
	 */
	public function accept()
	{
		return $this->current() instanceof $this->type;
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
