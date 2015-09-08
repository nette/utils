<?php

/**
 * Test: Nette\Object extension method.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
	public $foo = 'Hello', $bar = 'World';
}


TestClass::extensionMethod('join', $func = function (TestClass $that, $separator) {
	return $that->foo . $separator . $that->bar;
});

$obj = new TestClass;
Assert::same('Hello*World', $obj->join('*'));


Assert::same(
	array('join' => $func),
	Nette\Utils\ObjectMixin::getExtensionMethods('TestClass')
);

Assert::same(
	array(),
	Nette\Utils\ObjectMixin::getExtensionMethods('Nette\Object')
);
