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


	public function nullableClassType(): ?B
	{
	}


	public function nullableNativeType(): ?string
	{
	}


	public function nullableSelfType(): ?self
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


Assert::null(Reflection::getReturnType(new \ReflectionMethod(A::class, 'noType')));

Assert::same('Test\B', Reflection::getReturnType(new \ReflectionMethod(A::class, 'classType')));

Assert::same('string', Reflection::getReturnType(new \ReflectionMethod(A::class, 'nativeType')));

Assert::same('A', Reflection::getReturnType(new \ReflectionMethod(A::class, 'selfType')));

Assert::same('Test\B', Reflection::getReturnType(new \ReflectionMethod(A::class, 'nullableClassType')));

Assert::same('string', Reflection::getReturnType(new \ReflectionMethod(A::class, 'nullableNativeType')));

Assert::same('A', Reflection::getReturnType(new \ReflectionMethod(A::class, 'nullableSelfType')));

Assert::same('A', Reflection::getReturnType(new \ReflectionMethod(AExt::class, 'parentTypeExt')));

Assert::null(Reflection::getReturnType(new \ReflectionFunction('noType')));

Assert::same('Test\B', Reflection::getReturnType(new \ReflectionFunction('classType')));

Assert::same('string', Reflection::getReturnType(new \ReflectionFunction('nativeType')));
