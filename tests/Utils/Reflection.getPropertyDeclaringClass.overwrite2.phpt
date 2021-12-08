<?php

/**
 * Test: Nette\Utils\Reflection::getPropertyDeclaringClass + doccomment workaround
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


trait A
{
	/** a */
	protected $foo;
}

trait B
{
	use A;

	/** b */
	protected $foo;
}

class C
{
	use B;

	/** c */
	protected $foo;
}

class D extends C
{
	/** d */
	protected $foo;
}


// Property in class
Assert::same('D', Reflection::getPropertyDeclaringClass(new ReflectionProperty('D', 'foo'))->getName());

// Property in class
Assert::same('C', Reflection::getPropertyDeclaringClass(new ReflectionProperty('C', 'foo'))->getName());

// Property in trait
Assert::same('B', Reflection::getPropertyDeclaringClass(new ReflectionProperty('B', 'foo'))->getName());

// Property in trait
Assert::same('A', Reflection::getPropertyDeclaringClass(new ReflectionProperty('A', 'foo'))->getName());
