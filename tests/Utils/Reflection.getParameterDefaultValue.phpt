<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Reflection::getParameterDefaultValue()
 */

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/fixtures.reflection/defaultValue.php';


Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'a')),
	ReflectionException::class,
);

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'b')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'c')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'd')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'e')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'f')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'g')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'h')));

Assert::same('xyz', Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'p')));

Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'i')),
	ReflectionException::class,
	'Unable to resolve constant self::UNDEFINED used as default value of $i in NS\Foo::method().',
);

Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'j')),
	ReflectionException::class,
	'Unable to resolve constant NS\Foo::UNDEFINED used as default value of $j in NS\Foo::method().',
);

Assert::same(NS\Bar::DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'k')));

Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'l')),
	ReflectionException::class,
	'Unable to resolve constant NS\Undefined::ANY used as default value of $l in NS\Foo::method().',
);

Assert::same(DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'm')));

Assert::exception(
	fn() => Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'n')),
	ReflectionException::class,
	'Unable to resolve constant NS\UNDEFINED used as default value of $n in NS\Foo::method().',
);

Assert::same(NS\NS_DEFINED, Reflection::getParameterDefaultValue(new ReflectionParameter([NS\Foo::class, 'method'], 'o')));
