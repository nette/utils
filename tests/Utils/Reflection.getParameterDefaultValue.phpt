<?php

/**
 * Test: Nette\Utils\Reflection::getParameterDefaultValue()
 */

declare(strict_types=1);

namespace NS {
	define('DEFINED', 123);
	define('NS_DEFINED', 'xxx');
	const NS_DEFINED = 456;

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
			$i = UNDEFINED,
			$j = NS_DEFINED
		) {
		}
	}
}


namespace {
	use Nette\Utils\Reflection;
	use Tester\Assert;

	require __DIR__ . '/../bootstrap.php';


	Assert::exception(function () {
		Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'a'));
	}, ReflectionException::class);

	Assert::same(NS\Foo::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'b')));

	Assert::same(NS\Foo::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'c')));

	Assert::same(NS\Foo::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'd')));

	Assert::same(NS\Bar::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'e')));

	Assert::exception(function () {
		Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'f'));
	}, ReflectionException::class, 'Unable to resolve constant self::UNDEFINED used as default value of $f in NS\Foo::method().');

	Assert::exception(function () {
		Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'g'));
	}, ReflectionException::class, 'Unable to resolve constant NS\Undefined::ANY used as default value of $g in NS\Foo::method().');

	Assert::same(DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'h')));

	Assert::exception(function () {
		Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'i'));
	}, ReflectionException::class, 'Unable to resolve constant NS\UNDEFINED used as default value of $i in NS\Foo::method().');

	Assert::same(NS\NS_DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'j')));
}
