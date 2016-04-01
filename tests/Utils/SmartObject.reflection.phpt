<?php

/**
 * Test: Nette\SmartObject reflection.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\SmartObject;

}


$obj = new TestClass;
Assert::same('TestClass', $obj->getReflection()->getName());
Assert::same('TestClass', $obj->Reflection->getName());
