<?php

/**
 * Test: Nette\Utils\Reflection::getParameterDefaultValue()
 */

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


define('DEFINED', 123);

interface Bar
{
	const DEFINED = 'xyz';
}

class Foo
{
	const DEFINED = 'abc';

	function method(
		$a,
		$b = self::DEFINED,
		$c = Foo::DEFINED,
		$d = SELF::DEFINED,
		$e = bar::DEFINED,
		$f = self::UNDEFINED,
		$g = Undefined::ANY,
		$h = DEFINED,
		$i = UNDEFINED)
	{
	}
}


Assert::exception(function () {
	Reflection::getParameterDefaultValue(new ReflectionParameter(['Foo', 'method'], 'a'));
}, ReflectionException::class);

Assert::same(Foo::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['Foo', 'method'], 'b')));

Assert::same(Foo::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['Foo', 'method'], 'c')));

Assert::same(Foo::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['Foo', 'method'], 'd')));

Assert::same(Bar::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['Foo', 'method'], 'e')));

Assert::exception(function () {
	Reflection::getParameterDefaultValue(new ReflectionParameter(['Foo', 'method'], 'f'));
}, ReflectionException::class, 'Unable to resolve constant Foo::UNDEFINED used as default value of $f in Foo::method().');

Assert::exception(function () {
	Reflection::getParameterDefaultValue(new ReflectionParameter(['Foo', 'method'], 'g'));
}, ReflectionException::class, 'Unable to resolve constant Undefined::ANY used as default value of $g in Foo::method().');

Assert::same(DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['Foo', 'method'], 'h')));

Assert::exception(function () {
	Reflection::getParameterDefaultValue(new ReflectionParameter(['Foo', 'method'], 'i'));
}, ReflectionException::class, 'Unable to resolve constant UNDEFINED used as default value of $i in Foo::method().');
