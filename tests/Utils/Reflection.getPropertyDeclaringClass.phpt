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
	protected $bar;
}

trait B
{
	use A;

	protected $foo;
}

trait E
{
	protected $baz;
}

class C
{
	use B;
	use E;

	protected $own;
}

class D extends C
{
}


// Property in trait
Assert::same('B', Reflection::getPropertyDeclaringClass(new ReflectionProperty('D', 'foo'))->getName());

// Property in parent trait
Assert::same('A', Reflection::getPropertyDeclaringClass(new ReflectionProperty('D', 'bar'))->getName());

// Property in class itself
Assert::same('C', Reflection::getPropertyDeclaringClass(new ReflectionProperty('D', 'own'))->getName());

// Property in second trait
Assert::same('E', Reflection::getPropertyDeclaringClass(new ReflectionProperty('D', 'baz'))->getName());
