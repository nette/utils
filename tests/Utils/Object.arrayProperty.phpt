<?php

/**
 * Test: Nette\Object array property.
 *
 * @author     David Grudl
 * @package    Nette
 */


require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
	private $items = array();

	public function & getItems()
	{
		return $this->items;
	}

	public function setItems(array $value)
	{
		$this->items = $value;
	}

}


$obj = new TestClass;
$obj->items[] = 'test';
Assert::same( array('test'), $obj->items );
