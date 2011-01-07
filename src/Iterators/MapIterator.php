<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

namespace Nette;

use Nette;



/**
 * Applies the callback to the elements of the inner iterator.
 *
 * @author     David Grudl
 */
class MapIterator extends \IteratorIterator
{
	/** @var callback */
	private $callback;


	/**
	 * Constructs a filter around another iterator.
	 * @param
	 * @param  callback
	 */
	function __construct(\Traversable $iterator, $callback)
	{
		parent::__construct($iterator);
		$this->callback = $callback;
	}



	function current()
	{
		return call_user_func($this->callback, parent::current(), parent::key());
	}

}
