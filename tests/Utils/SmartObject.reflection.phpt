<?php

/**
 * Test: Nette\SmartObject reflection (deprecated)
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\SmartObject;

}


Assert::error(function () {
	$obj = new TestClass;
	$obj->getReflection();
}, E_USER_DEPRECATED, 'TestClass::getReflection() is deprecated in ' . __FILE__ . ':' . (__LINE__ - 1));


$obj = new TestClass;
Assert::same('TestClass', @$obj->getReflection()->getName());
Assert::same('TestClass', @$obj->Reflection->getName());
