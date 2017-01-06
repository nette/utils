<?php

/**
 * Test: Nette\SmartObject extension method using \Nette\Utils\IExtensibleMethods
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClassExtensible implements \Nette\Utils\IExtensibleMethods
{
	use Nette\SmartObject;

	public $foo = 'Hello', $bar = 'World';
}


$func = function (TestClassExtensible $that, $separator) {
	return $that->foo . $separator . $that->bar;
};

Nette\Utils\ObjectMixin::setExtensionMethod(TestClassExtensible::class, 'join', $func);

$obj = new TestClassExtensible;
Assert::same('Hello*World', $obj->join('*'));


Assert::same(
	['join' => $func],
	Nette\Utils\ObjectMixin::getExtensionMethods(TestClassExtensible::class)
);

Assert::same(
	[],
	Nette\Utils\ObjectMixin::getExtensionMethods(Nette\SmartObject::class)
);

Assert::exception(function () {
	$obj = new TestClassExtensible;
	$obj->joi();
}, Nette\MemberAccessException::class, 'Call to undefined method TestClassExtensible::joi().');
