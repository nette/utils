<?php

/**
 * Test: Nette\SmartObject magic @methods (deprecated)
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @method void setName($var)
 * @method getName()
 * @method self addItem()
 * @method self setItems()
 * @method getItems()
 * @method setEnabled($enabled)
 * @method isEnabled() comment
 */
class TestClass
{
	use Nette\SmartObject;

	public $name;

	protected $enabled;

	private $items = [];
}


// deprecated
Assert::error(function () {
	$obj = new TestClass;
	$obj->setName('hello');
}, E_USER_DEPRECATED, 'Magic methods such as TestClass::setName() are deprecated in ' . __FILE__ . ':' . (__LINE__ - 1));


$obj = new TestClass;

// public
Assert::same($obj, @$obj->setName('hello'));
Assert::same('hello', @$obj->name);
Assert::same('hello', @$obj->getName());

// protected
Assert::same($obj, @$obj->setEnabled(11));
Assert::same(11, @$obj->isEnabled());

// magic accessors for magic methods
@$obj->enabled = 22;
Assert::same(22, @$obj->enabled);

// adder
Assert::same($obj, @$obj->addItem('world'));
Assert::same(['world'], @$obj->items);
Assert::same(['world'], @$obj->getItems());

Assert::same($obj, @$obj->setItems([]));
Assert::same([], @$obj->items);
Assert::same([], @$obj->getItems());
