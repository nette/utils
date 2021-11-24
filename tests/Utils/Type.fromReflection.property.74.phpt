<?php

/**
 * @phpversion 7.4
 */

declare(strict_types=1);

use Nette\Utils\Type;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$type = Type::fromReflection((new ReflectionObject(new class {
	public string $foo;
}))->getProperty('foo'));

Assert::same(['string'], $type->getNames());
Assert::same('string', (string) $type);


$type = Type::fromReflection((new ReflectionObject(new class {
	public ?string $foo;
}))->getProperty('foo'));

Assert::same(['string', 'null'], $type->getNames());
Assert::same('?string', (string) $type);


$type = Type::fromReflection((new ReflectionObject(new class {
	public Foo $foo;
}))->getProperty('foo'));

Assert::same(['Foo'], $type->getNames());
Assert::same('Foo', (string) $type);
