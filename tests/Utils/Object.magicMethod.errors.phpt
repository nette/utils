<?php

/**
 * Test: Nette\Object magic @methods errors.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @method self addItem()
 * @method self setItems()
 * @method getItems()
 * @method getAbc()
 */
class TestClass extends Nette\Object
{
	protected $items = [];

	public function abc()
	{
		parent::abc();
	}
}

// Undeclared method access
Assert::exception(function () {
	$obj = new TestClass;
	$obj->setAbc();
}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::setAbc(), did you mean getAbc()?');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->abc();
}, Nette\MemberAccessException::class, 'Call to undefined method parent::abc().');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->adItem();
}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::adItem(), did you mean addItem()?');


// Wrong parameters count
Assert::exception(function () {
	$obj = new TestClass;
	$obj->setItems();
}, Nette\InvalidArgumentException::class, 'TestClass::setItems() expects 1 argument, 0 given.');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->setItems(1, 2);
}, Nette\InvalidArgumentException::class, 'TestClass::setItems() expects 1 argument, 2 given.');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->getItems(1);
}, Nette\InvalidArgumentException::class, 'TestClass::getItems() expects no argument, 1 given.');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->addItem();
}, Nette\InvalidArgumentException::class, 'TestClass::addItem() expects 1 argument, 0 given.');
