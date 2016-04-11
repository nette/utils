<?php

/**
 * Test: Nette\SmartObject extension method (deprecated)
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\SmartObject;

	public $foo = 'Hello', $bar = 'World';
}


$func = function (TestClass $that, $separator) {
	return $that->foo . $separator . $that->bar;
};

Assert::error(function () use ($func) {
	TestClass::extensionMethod('join', $func);
}, E_USER_DEPRECATED, 'Extension methods such as TestClass::join() are deprecated in ' . __FILE__ . ':' . (__LINE__ - 1));

Assert::error(function () {
	$obj = new TestClass;
	$obj->join('*');
}, E_USER_DEPRECATED, 'Extension methods such as TestClass::join() are deprecated in ' . __FILE__ . ':' . (__LINE__ - 1));


$obj = new TestClass;
Assert::same('Hello*World', @$obj->join('*'));


Assert::same(
	['join' => $func],
	Nette\Utils\ObjectMixin::getExtensionMethods(TestClass::class)
);

Assert::same(
	[],
	Nette\Utils\ObjectMixin::getExtensionMethods(Nette\SmartObject::class)
);

Assert::exception(function () {
	$obj = new TestClass;
	$obj->joi();
}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::joi().');
