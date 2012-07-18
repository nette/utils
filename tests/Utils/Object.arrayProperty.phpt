<?php

/**
 * Test: Nette\Object array property.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */




require __DIR__ . '/../bootstrap.php';



class TestClass extends Nette\Object
{
	private $items;

	function __construct()
	{
		$this->items = new ArrayObject;
	}

	public function getItems()
	{
		return $this->items;
	}

	public function setItems(array $value)
	{
		$this->items = new ArrayObject($value);
	}

}


$obj = new TestClass;
$obj->items[] = 'test';
Assert::same( 'test', $obj->items[0] );


$obj->items = array();
$obj->items[] = 'one';
$obj->items[] = 'two';
Assert::same( 'one', $obj->items[0] );

Assert::same( 'two', $obj->items[1] );
