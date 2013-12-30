<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Iterators;

use Nette;


/**
 * CallbackFilterIterator for PHP < 5.4.
 *
 * @author     David Grudl
 */
class Filter extends \FilterIterator
{
	/** @var callable */
	protected $callback;


	public function __construct(\Iterator $iterator, $callback)
	{
		parent::__construct($iterator);
		$this->callback = Nette\Utils\Callback::check($callback);
	}


	public function accept()
	{
		return call_user_func($this->callback, $this->current(), $this->key(), $this);
	}

}
