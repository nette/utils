<?php

/**
 * Test: Nette\Utils\Reflection::toString()
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Foo
{
	public $var;


	public function method($param)
	{
	}
}


function func($param)
{
}


Assert::same('Foo', Reflection::toString(new ReflectionClass('Foo')));
Assert::same('Foo::method()', Reflection::toString(new ReflectionMethod('Foo', 'method')));
Assert::same('$param in Foo::method()', Reflection::toString(new ReflectionParameter(['Foo', 'method'], 'param')));
Assert::same('Foo::$var', Reflection::toString(new ReflectionProperty('Foo', 'var')));
Assert::same('func()', Reflection::toString(new ReflectionFunction('func')));
Assert::same('$param in func()', Reflection::toString(new ReflectionParameter('func', 'param')));
Assert::same('$param in {closure}()', Reflection::toString((new ReflectionFunction(function ($param) {}))->getParameters()[0]));
