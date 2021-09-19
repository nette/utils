<?php

declare(strict_types=1);

use Nette\Utils\Type;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$type = Type::fromReflection((new ReflectionObject(new class {
	public function foo(): string
	{
	}
}))->getMethod('foo'));

Assert::same(['string'], $type->getNames());
Assert::same('string', (string) $type);


$type = Type::fromReflection((new ReflectionObject(new class {
	public function foo(): ?string
	{
	}
}))->getMethod('foo'));

Assert::same(['string', 'null'], $type->getNames());
Assert::same('?string', (string) $type);


$class = new class {
	public function foo(): self
	{
	}
};
$type = Type::fromReflection((new ReflectionObject($class))->getMethod('foo'));

Assert::same([$class::class], $type->getNames());
Assert::same($class::class, (string) $type);
