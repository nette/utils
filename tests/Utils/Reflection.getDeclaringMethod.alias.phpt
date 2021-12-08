<?php

/**
 * Test: Nette\Utils\Reflection::getDeclaringMethod
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


trait A
{
	public function foo()
	{
	}
}

trait B
{
	use A {
		A::foo as foo2;
	}
}

trait B2
{
	use A {
		A::foo as foo2;
	}

	public function foo2()
	{
	}
}

class E1
{
	use B {
		B::foo2 as alias;
	}
}

class E2
{
	use B {
		B::foo2 as alias;
	}

	public function foo2()
	{
	}


	public function alias()
	{
	}
}

class E3
{
	use B2 {
		B2::foo as foo3;
	}
}


function get(ReflectionMethod $m)
{
	$res = Reflection::getMethodDeclaringMethod($m);
	return $res->getDeclaringClass()->name . '::' . $res->name;
}


// new ReflectionMethod and getMethod returns different method names, PHP #79636

// Method in trait
Assert::same('A::foo', get((new ReflectionClass('E3'))->getMethod('foo3')));
Assert::same('A::foo', get(new ReflectionMethod('E3', 'foo3')));

Assert::same('B2::foo2', get((new ReflectionClass('E3'))->getMethod('foo2')));
Assert::same('B2::foo2', get(new ReflectionMethod('E3', 'foo2')));

Assert::same('A::foo', get((new ReflectionClass('E3'))->getMethod('foo')));
Assert::same('A::foo', get(new ReflectionMethod('E3', 'foo')));

// Method in class
Assert::same('E2::alias', get((new ReflectionClass('E2'))->getMethod('alias')));
Assert::same('E2::alias', get(new ReflectionMethod('E2', 'alias')));

Assert::same('E2::foo2', get((new ReflectionClass('E2'))->getMethod('foo2')));
Assert::same('E2::foo2', get(new ReflectionMethod('E2', 'foo2')));

// Method in trait
Assert::same('A::foo', get((new ReflectionClass('E1'))->getMethod('alias')));
Assert::same('A::foo', get(new ReflectionMethod('E1', 'alias')));

// Method in trait
Assert::same('B2::foo2', get((new ReflectionClass('B2'))->getMethod('foo2')));
Assert::same('B2::foo2', get(new ReflectionMethod('B2', 'foo2')));

Assert::same('A::foo', get((new ReflectionClass('B'))->getMethod('foo2')));
Assert::same('A::foo', get(new ReflectionMethod('B', 'foo2')));

Assert::same('A::foo', get((new ReflectionClass('A'))->getMethod('foo')));
Assert::same('A::foo', get(new ReflectionMethod('A', 'foo')));
