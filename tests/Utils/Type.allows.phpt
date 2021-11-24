<?php

/**
 * Test: Nette\Utils\Type::allows()
 */

declare(strict_types=1);

use Nette\Utils\Type;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Bar
{
}

class Foo
{
}

class FooChild extends Foo
{
}


$type = Type::fromString('string');
Assert::true($type->allows('string'));
Assert::false($type->allows('null'));
Assert::false($type->allows('string|null'));
Assert::false($type->allows('Foo'));
Assert::false($type->allows('FooChild'));
Assert::false($type->allows('Foo|FooChild'));
Assert::false($type->allows('Foo&Bar'));


$type = Type::fromString('string|null');
Assert::true($type->allows('string'));
Assert::true($type->allows('null'));
Assert::true($type->allows('string|null'));
Assert::false($type->allows('Foo'));
Assert::false($type->allows('FooChild'));
Assert::false($type->allows('Foo|FooChild'));
Assert::false($type->allows('Foo&Bar'));


$type = Type::fromString('string|Foo');
Assert::true($type->allows('string'));
Assert::false($type->allows('null'));
Assert::false($type->allows('string|null'));
Assert::true($type->allows('Foo'));
Assert::true($type->allows('FooChild'));
Assert::true($type->allows('Foo|FooChild'));
Assert::true($type->allows('Foo&Bar'));


$type = Type::fromString('mixed');
Assert::true($type->allows('string'));
Assert::true($type->allows('null'));
Assert::true($type->allows('string|null'));
Assert::true($type->allows('Foo'));
Assert::true($type->allows('FooChild'));
Assert::true($type->allows('Foo|FooChild'));
Assert::true($type->allows('Foo&Bar'));


$type = Type::fromString('Bar&Foo');
Assert::false($type->allows('string'));
Assert::false($type->allows('null'));
Assert::false($type->allows('Foo'));
Assert::false($type->allows('FooChild'));
Assert::true($type->allows('Foo&Bar'));
Assert::true($type->allows('FooChild&Bar'));
Assert::true($type->allows('Foo&Bar&Baz'));


$type = Type::fromString('Bar&FooChild');
Assert::false($type->allows('Foo&Bar'));
