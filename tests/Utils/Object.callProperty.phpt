<?php

/**
 * Test: Nette\Object call properties.
 *
 * @author     David Grudl
 * @package    Nette
 */




require __DIR__ . '/../bootstrap.php';



class TestClass
{
	public $items = array();
	public $name;

	public function __call($name, $args)
	{
		return Nette\ObjectMixin::callProperty($this, $name, $args);
	}

}

$obj = new TestClass;
Assert::same( $obj, $obj->setName('hello') );
Assert::same( 'hello', $obj->name );
Assert::same( 'hello', $obj->getName() );

Assert::same( $obj, $obj->addItem('world') );
Assert::same( array('world'), $obj->items );
Assert::same( array('world'), $obj->getItems() );

Assert::same( $obj, $obj->setItems(array()) );
Assert::same( array(), $obj->items );
Assert::same( array(), $obj->getItems() );


// Undeclared method access
Assert::exception(function() {
	$obj = new TestClass;
	$obj->setItem();
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::setItem().');
