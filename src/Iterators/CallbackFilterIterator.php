<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette;

use Nette;



/**
 * Callback iterator filter.
 *
 * @author     David Grudl
 */
class CallbackFilterIterator extends \FilterIterator
{
	/** @var callback */
	private $callback;


	/**
	 * Constructs a filter around another iterator.
	 * @param
	 * @param  callback
	 */
	function __construct(\Iterator $iterator, $callback)
	{
		parent::__construct($iterator);
		$this->callback = $callback;
	}



	function accept()
	{
		return call_user_func($this->callback, $this);
	}

}
