<?php

/**
 * Test: Nette\StaticClass
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\StaticClass;

	public static function method()
	{}

}

Assert::exception(function () {
	new TestClass;
}, LogicException::class, 'Class TestClass is static and cannot be instantiated.');

Assert::exception(function () {
	TestClass::methodA();
}, Nette\MemberAccessException::class, 'Call to undefined static method TestClass::methodA(), did you mean method()?');
