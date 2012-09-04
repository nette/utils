<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette\Iterators;

use Nette;



/**
 * RecursiveCallbackFilterIterator for PHP < 5.4.
 *
 * @author     David Grudl
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
