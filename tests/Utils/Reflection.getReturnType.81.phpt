<?php

/**
 * Test: Nette\Utils\Reflection::getReturnType
 * @phpversion 8.1
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


	public function intersectionType(): AExt&A
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


function intersectionType(): AExt&A
{
}


Assert::null(Reflection::getReturnType(new \ReflectionMethod(A::class, 'noType')));

Assert::same('Test\B', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'classType')));

Assert::same('string', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'nativeType')));

Assert::same('A', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'selfType')));

Assert::same('A', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'staticType')));

Assert::same('?Test\B', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'nullableClassType')));

Assert::same('?string', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'nullableNativeType')));

Assert::same('?A', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'nullableSelfType')));

Assert::same('A|array', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'unionType')));

Assert::same('A|array|null', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'nullableUnionType')));

Assert::same('AExt&A', (string) Reflection::getReturnType(new \ReflectionMethod(A::class, 'intersectionType')));

Assert::same('A', (string) Reflection::getReturnType(new \ReflectionMethod(AExt::class, 'parentTypeExt')));

Assert::null(Reflection::getReturnType(new \ReflectionFunction('noType')));

Assert::same('Test\B', (string) Reflection::getReturnType(new \ReflectionFunction('classType')));

Assert::same('string', (string) Reflection::getReturnType(new \ReflectionFunction('nativeType')));

Assert::same('A|array', (string) Reflection::getReturnType(new \ReflectionFunction('unionType')));

Assert::same('AExt&A', (string) Reflection::getReturnType(new \ReflectionFunction('intersectionType')));


// tentative type
Assert::same('int', (string) Reflection::getReturnType(new \ReflectionMethod(\ArrayObject::class, 'count')));
