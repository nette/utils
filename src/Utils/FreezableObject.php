<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 */

/*namespace Nette;*/



/**
 * Defines an object that has a modifiable state and a read-only (frozen) state.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette
 *
 * @property-read bool $frozen
 */
abstract class FreezableObject extends Object
{
	/** @var bool */
	private $frozen = FALSE;



	/**
	 * Makes the object unmodifiable.
	 * @return void
	 */
	public function freeze()
	{
		$this->frozen = TRUE;
	}



	/**
	 * Is the object unmodifiable?
	 * @return bool
	 */
	final public function isFrozen()
	{
		return $this->frozen;
	}



	/**
	 * Creates a modifiable clone of the object.
	 * @return void
	 */
	public function __clone()
	{
		$this->frozen = FALSE;
	}



	/**
	 * @return void
	 */
	protected function updating()
	{
		if ($this->frozen) {
			throw new /*\*/InvalidStateException("Cannot modify a frozen object {$this->reflection->name}.");
		}
	}

}
