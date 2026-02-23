<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Reflection::getDeclaringMethod
 */

use Nette\Utils\Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


trait A
{
	public function bar()
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

trait E
{
	public function baz()
	{
	}
}

class C
{
	use B;
	use E;

	public function own()
	{
	}
}

class D extends C
{
}


function get(ReflectionMethod $m)
{
	$res = Reflection::getMethodDeclaringMethod($m);
	return $res->getDeclaringClass()->name . '::' . $res->name;
}


// Method in trait
Assert::same('B::foo', get(new ReflectionMethod('D', 'foo')));

// Method in parent trait
Assert::same('A::bar', get(new ReflectionMethod('D', 'bar')));

// Method in class itself
Assert::same('C::own', get(new ReflectionMethod('D', 'own')));

// Method in second trait
Assert::same('E::baz', get(new ReflectionMethod('D', 'baz')));
