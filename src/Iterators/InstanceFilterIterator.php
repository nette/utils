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
 * Instance iterator filter.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette
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
