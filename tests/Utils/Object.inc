<?php

/**
 * Common code for Nette\Object test cases.
 */

use Nette\Object;



interface IFirst {}

interface ISecond extends IFirst {}

class TestClass extends Object implements ISecond
{

	private $foo, $bar;

	private $items;

	private $onPrivate;

	protected $onProtected;

	public $onPublic;

	public $onPublic2 = 'nonevent';

	static public $onPublicStatic;



	function __construct($foo = NULL, $bar = NULL)
	{
		$this->foo = $foo;
		$this->bar = $bar;
		$this->items = new ArrayObject;
	}



	public function getFoo()
	{
		return $this->foo;
	}



	public function setFoo($foo)
	{
		$this->foo = $foo;
	}



	public function getBar()
	{
		return $this->bar;
	}



	public function setBazz($value)
	{
		$this->bar = $value;
	}



	public function getItems()
	{
		return $this->items;
	}



	public function setItems(array $value)
	{
		$this->items = new ArrayObject($value);
	}



	public function gets() // or setupXyz, settle...
	{
		echo __METHOD__;
		return 'ERROR';
	}

}
