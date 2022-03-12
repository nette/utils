<?php

/**
 * Test: Nette\Utils\Reflection::getReturnType
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Test\B;
use Tester\Assert; // for testing purposes

require __DIR__ . '/../bootstrap.php';


class A
{
	public function noType()
	{
	}


	public function classType(): B
	{
	}


	public function nativeType(): String
	{
	}


	public function selfType(): self
	{
	}


	public function staticType(): static
	{
	}


	public function nullableClassType(): ?B
	{
	}


	public function nullableNativeType(): ?string
	{
	}


	public function nullableSelfType(): ?self
	{
	}


	public function unionType(): array|self
	{
	}


	public function nullableUnionType(): array|self|null
	{
	}
}

class AExt extends A
{
	public function parentTypeExt(): parent
	{
	}
}


function noType()
{
}


function classType(): B
{
}


function nativeType(): String
{
}


function unionType(): array|A
{
}


Assert::null(Reflection::getReturnType(new ReflectionMethod(A::class, 'noType')));

Assert::same('Test\B', Reflection::getReturnType(new ReflectionMethod(A::class, 'classType')));

Assert::same('string', Reflection::getReturnType(new ReflectionMethod(A::class, 'nativeType')));

Assert::same('A', Reflection::getReturnType(new ReflectionMethod(A::class, 'selfType')));

Assert::same('A', Reflection::getReturnType(new ReflectionMethod(A::class, 'staticType')));

Assert::same('Test\B', Reflection::getReturnType(new ReflectionMethod(A::class, 'nullableClassType')));

Assert::same('string', Reflection::getReturnType(new ReflectionMethod(A::class, 'nullableNativeType')));

Assert::same('A', Reflection::getReturnType(new ReflectionMethod(A::class, 'nullableSelfType')));

Assert::exception(
	fn() => Reflection::getReturnType(new ReflectionMethod(A::class, 'unionType')),
	Nette\InvalidStateException::class,
	'The A::unionType() is not expected to have a union or intersection type.',
);

Assert::exception(
	fn() => Reflection::getReturnType(new ReflectionMethod(A::class, 'nullableUnionType')),
	Nette\InvalidStateException::class,
	'The A::nullableUnionType() is not expected to have a union or intersection type.',
);

Assert::same('A', Reflection::getReturnType(new ReflectionMethod(AExt::class, 'parentTypeExt')));

Assert::null(Reflection::getReturnType(new ReflectionFunction('noType')));

Assert::same('Test\B', Reflection::getReturnType(new ReflectionFunction('classType')));

Assert::same('string', Reflection::getReturnType(new ReflectionFunction('nativeType')));

Assert::exception(
	fn() => Reflection::getReturnType(new ReflectionFunction('unionType')),
	Nette\InvalidStateException::class,
	'The unionType() is not expected to have a union or intersection type.',
);
