<?php

/**
 * Test: Nette\Utils\Reflection::getParameterDefaultValue()
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/fixtures.reflection/defaultValue.71.php';


Assert::exception(function () {
	Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'a'));
}, ReflectionException::class);

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'b')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'c')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'd')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'e')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'f')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'g')));

Assert::same('abc', Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'h')));

Assert::exception(function () {
	Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'i'));
}, ReflectionException::class, 'Unable to resolve constant self::UNDEFINED used as default value of $i in NS\Foo::method().');

Assert::exception(function () {
	Reflection::getParameterDefaultValue(new ReflectionParameter(['NS\Foo', 'method'], 'j'));
}, ReflectionException::class, 'Unable to resolve constant NS\\Foo::UNDEFINED used as default value of $j in NS\Foo::method().');
