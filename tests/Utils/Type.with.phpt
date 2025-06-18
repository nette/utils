<?php

/**
 * Test: Nette\Utils\Type::with()
 */

declare(strict_types=1);

use Nette\Utils\Type;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Foo
{
}

class FooChild extends Foo
{
}


function testTypeHint(string $expected, Type $type): void
{
	Assert::same($expected, (string) $type);
	eval("function($expected \$_) {};");
}


testTypeHint('string|int', Type::fromString('string')->with('int'));
testTypeHint('string|int|null', Type::fromString('string')->with('?int'));
testTypeHint('string|int|null', Type::fromString('?string')->with('int'));
testTypeHint('string|int|null', Type::fromString('?string')->with('?int'));
testTypeHint('string|int|bool', Type::fromString('string|int')->with('bool'));
testTypeHint('string|int|bool', Type::fromString('string|int')->with('bool|int'));
testTypeHint('(Foo&Bar)|string', Type::fromString('Foo&Bar')->with('string'));
testTypeHint('Foo', Type::fromString('Foo&Bar')->with('Foo'));
testTypeHint('string|(Foo&Bar)', Type::fromString('string')->with('Foo&Bar'));
testTypeHint('(Foo&Bar)|(Foo&FooChild)', Type::fromString('Foo&Bar')->with('Foo&FooChild'));
testTypeHint('(Foo&Bar)|string|int', Type::fromString('(Foo&Bar)|string')->with('int'));

// with Type object
testTypeHint('string|int', Type::fromString('string')->with(Type::fromString('int')));

// mixed
testTypeHint('mixed', Type::fromString('string')->with('mixed'));
testTypeHint('mixed', Type::fromString('mixed')->with('null'));

// Already allows - returns same instance
$type = Type::fromString('string');
Assert::same($type, $type->with('string'));

$type = Type::fromString('string|int|bool');
Assert::same($type, $type->with('int'));

$type = Type::fromString('?string');
Assert::same($type, $type->with('string'));
Assert::same($type, $type->with('null'));

$with = Type::fromString('mixed');
Assert::same($with, Type::fromString('string')->with($with));

$type = Type::fromString('Foo|Bar');
Assert::same($type, $type->with('FooChild'));

$with = Type::fromString('Foo');
Assert::same($with, Type::fromString('Foo&Bar')->with($with));
