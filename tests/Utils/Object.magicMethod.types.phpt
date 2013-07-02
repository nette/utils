<?php

/**
 * Test: Nette\Object magic @methods and types.
 *
 * @author     David Grudl
 * @package    Nette
 */

namespace Test;

use Assert, Nette, stdClass;


require __DIR__ . '/../bootstrap.php';


/**
 * @method void setName(string $var)
 * @method getName()
 * @method addItem()
 * @method self setItems()
 * @method getItems
 * @method setEnabled ( bool)
 */
class TestClass extends Nette\Object
{
	public $name;

	public $enabled;

	/** @var TestClass[] */
	public $items = array();
}


$obj = new TestClass;

$obj->setName(123);
Assert::same( '123', $obj->name );


$obj->setEnabled(1);
Assert::same( true, $obj->enabled );

Assert::exception(function() use ($obj) {
	$obj->setEnabled(new stdClass);
}, 'Nette\InvalidArgumentException', 'Argument passed to Test\TestClass::setEnabled() must be bool, object given.');


$obj->setItems(array(new TestClass));
Assert::equal( array(new TestClass), $obj->items );

Assert::exception(function() use ($obj) {
	$obj->setItems(array(1));
}, 'Nette\InvalidArgumentException', 'Argument passed to Test\TestClass::setItems() must be Test\TestClass[], array given.');


$obj->addItem(new TestClass);
Assert::equal( array(new TestClass, new TestClass), $obj->items );

Assert::exception(function() use ($obj) {
	$obj->addItem(1);
}, 'Nette\InvalidArgumentException', 'Argument passed to Test\TestClass::addItem() must be Test\TestClass, integer given.');
