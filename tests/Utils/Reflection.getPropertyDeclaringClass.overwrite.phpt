<?php

/**
 * Test: Nette\Utils\Reflection::getPropertyDeclaringClass
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


trait A
{
	protected $foo;
}

trait B
{
	use A;

	protected $foo;
}

class C
{
	use B;

	protected $foo;
}

class D extends C
{
	protected $foo;
}


// Property in class
Assert::same('D', Reflection::getPropertyDeclaringClass(new ReflectionProperty('D', 'foo'))->getName());

// Property in class - wrong, but impossible to solve in PHP https://github.com/nette/di/issues/169
Assert::same('A', Reflection::getPropertyDeclaringClass(new ReflectionProperty('C', 'foo'))->getName());

// Property in trait - wrong, but impossible to solve in PHP https://github.com/nette/di/issues/169
Assert::same('A', Reflection::getPropertyDeclaringClass(new ReflectionProperty('B', 'foo'))->getName());

// Property in trait
Assert::same('A', Reflection::getPropertyDeclaringClass(new ReflectionProperty('A', 'foo'))->getName());
