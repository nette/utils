<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Nette\Iterators;


/**
 * RecursiveCallbackFilterIterator for PHP < 5.4.
 */
class RecursiveFilter extends Filter implements \RecursiveIterator
{

	public function __construct(\RecursiveIterator $iterator, $callback)
	{
		parent::__construct($iterator, $callback);
	}


	public function hasChildren()
	{
		return $this->getInnerIterator()->hasChildren();
	}


	public function getChildren()
	{
		return new static($this->getInnerIterator()->getChildren(), $this->callback);
	}

}
