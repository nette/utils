<?php

/**
 * Test: Nette\Utils\Reflection::getParameterDefaultValue()
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/fixtures.reflection/defaultValue.php';


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
