<?php

/**
 * Test: Nette\Object undeclared method.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
}


Assert::exception(function () {
	$obj = new TestClass;
	$obj->undeclared();
}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::undeclared().');
