<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Iterators;

use Nette;


/**
 * Applies the callback to the elements of the inner iterator.
 *
 * @author     David Grudl
 */
class Mapper extends \IteratorIterator
{
	/** @var callable */
	private $callback;


	public function __construct(\Traversable $iterator, $callback)
	{
		parent::__construct($iterator);
		$this->callback = Nette\Utils\Callback::check($callback);
	}


	public function current()
	{
		return call_user_func($this->callback, parent::current(), parent::key());
	}

}
