<?php

/**
 * Test: Nette\Object magic @methods errors.
 *
 * @author     David Grudl
 * @package    Nette
 */


require __DIR__ . '/../bootstrap.php';


/**
 * @method self addItem()
 * @method self setItems()
 * @method getItems
 * @method getAbc
 */
class TestClass extends Nette\Object
{
	protected $items = array();
}

// Undeclared method access
Assert::exception(function() {
	$obj = new TestClass;
	$obj->setAbc();
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::setAbc().');


// Wrong parameters count
Assert::exception(function() {
	$obj = new TestClass;
	$obj->setItems();
}, 'Nette\InvalidArgumentException', 'TestClass::setItems() expects 1 argument, 0 given.');

Assert::exception(function() {
	$obj = new TestClass;
	$obj->setItems(1, 2);
}, 'Nette\InvalidArgumentException', 'TestClass::setItems() expects 1 argument, 2 given.');

Assert::exception(function() {
	$obj = new TestClass;
	$obj->getItems(1);
}, 'Nette\InvalidArgumentException', 'TestClass::getItems() expects no argument, 1 given.');

Assert::exception(function() {
	$obj = new TestClass;
	$obj->addItem();
}, 'Nette\InvalidArgumentException', 'TestClass::addItem() expects 1 argument, 0 given.');
