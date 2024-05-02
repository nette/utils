<?php

/**
 * @phpversion 8.2
 */

declare(strict_types=1);

use Nette\Utils\Type;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$type = Type::fromReflection(new ReflectionFunction(function (): string {}));

Assert::same(['string'], $type->getNames());
Assert::same('string', (string) $type);


$type = Type::fromReflection(new ReflectionFunction(function (): ?string {}));

Assert::same(['string', 'null'], $type->getNames());
Assert::same('?string', (string) $type);


$type = Type::fromReflection(new ReflectionFunction(function (): Foo {}));

Assert::same(['Foo'], $type->getNames());
Assert::same('Foo', (string) $type);


$type = Type::fromReflection(new ReflectionFunction(function (): Foo|string {}));

Assert::same(['Foo', 'string'], $type->getNames());
Assert::same('Foo|string', (string) $type);


$type = Type::fromReflection(new ReflectionFunction(function (): mixed {}));

Assert::same(['mixed'], $type->getNames());
Assert::same('mixed', (string) $type);


$type = Type::fromReflection(new ReflectionFunction(function (): Bar & Foo {}));

Assert::same(['Bar', 'Foo'], $type->getNames());
Assert::same('Bar&Foo', (string) $type);


// tentative type
$type = Type::fromReflection(new ReflectionMethod(ArrayObject::class, 'count'));
Assert::same('int', (string) $type);


// disjunctive normal form
$type = Type::fromReflection(new ReflectionFunction(function (): (Bar & Foo) | string | int | null {}));
Assert::same('(Bar&Foo)|string|int|null', (string) $type);
