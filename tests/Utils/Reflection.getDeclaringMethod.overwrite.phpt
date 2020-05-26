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
	use A;

	public function foo()
	{
	}
}

class C
{
	use B;

	public function foo()
	{
	}
}

class D extends C
{
	public function foo()
	{
	}
}


function get(ReflectionMethod $m)
{
	$res = Reflection::getMethodDeclaringMethod($m);
	return $res->getDeclaringClass()->name . '::' . $res->name;
}


// Method in class
Assert::same('D::foo', get(new ReflectionMethod('D', 'foo')));

// Method in class - uses doccomment & file-line workaround
Assert::same('C::foo', get(new ReflectionMethod('C', 'foo')));

// Method in trait - uses doccomment & file-line workaround
Assert::same('B::foo', get(new ReflectionMethod('B', 'foo')));

// Method in trait
Assert::same('A::foo', get(new ReflectionMethod('A', 'foo')));
