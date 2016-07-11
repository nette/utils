<?php

/**
 * Test: Nette\Object reference to property.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
	private $foo;

	public function getFoo()
	{
		return $this->foo;
	}

	public function setFoo($foo)
	{
		$this->foo = $foo;
	}

}


$obj = new TestClass;
$obj->foo = 'hello';
@$x = &$obj->foo;
$x = 'changed by reference';
Assert::same('hello', $obj->foo);
