<?php

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
