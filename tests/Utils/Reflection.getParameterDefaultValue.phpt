<?php

/**
 * Test: Nette\Utils\Reflection::getParameterDefaultValue()
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/fixtures.reflection/defaultValue.php';


Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'a')),
	ReflectionException::class,
);

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'b')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'c')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'd')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'e')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'f')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'g')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'h')));

Assert::same('xyz', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'p')));

Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'i')),
	ReflectionException::class,
	'Unable to resolve constant self::UNDEFINED used as default value of $i in NS\Foo::method().',
);

Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'j')),
	ReflectionException::class,
	'Unable to resolve constant NS\Foo::UNDEFINED used as default value of $j in NS\Foo::method().',
);

Assert::same(NS\Bar::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'k')));

Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'l')),
	ReflectionException::class,
	'Unable to resolve constant NS\Undefined::ANY used as default value of $l in NS\Foo::method().',
);

Assert::same(DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'm')));

Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'n')),
	ReflectionException::class,
	'Unable to resolve constant NS\UNDEFINED used as default value of $n in NS\Foo::method().',
);

Assert::same(NS\NS_DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'o')));
