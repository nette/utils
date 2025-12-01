<?php

/**
 * Test: Nette\Utils\Type::fromValue()
 */

declare(strict_types=1);

use Nette\Utils\Type;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Foo
{
}

interface Baz
{
}

Assert::same('int', (string) Type::fromValue(42));
Assert::same('float', (string) Type::fromValue(3.14));
Assert::same('string', (string) Type::fromValue('hello'));
Assert::same('bool', (string) Type::fromValue(true));
Assert::same('bool', (string) Type::fromValue(false));
Assert::same('null', (string) Type::fromValue(null));
Assert::same('array', (string) Type::fromValue([1, 2, 3]));
Assert::same('Foo', (string) Type::fromValue(new Foo));
Assert::same('stdClass', (string) Type::fromValue(new stdClass));
Assert::same('Closure', (string) Type::fromValue(fn() => null));

// Anonymous class
Assert::same('object', (string) Type::fromValue(new class {
}));

Assert::same('Foo', (string) Type::fromValue(new class extends Foo {
}));

Assert::same('Baz', (string) Type::fromValue(new class implements Baz {
}));

// Resource
Assert::same('mixed', (string) Type::fromValue(fopen('php://memory', 'r')));
