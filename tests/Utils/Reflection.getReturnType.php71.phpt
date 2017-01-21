<?php

/**
 * Test: Nette\Utils\Reflection::getReturnType
 */

declare(strict_types=1);

namespace NS
{
	use Test\B;

	class A
	{
		public function noType()
		{
		}


		public function classType(): B
		{
		}


		public function nativeType(): string
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
}

namespace
{
	use Nette\Utils\Reflection;
	use Tester\Assert;

	require __DIR__ . '/../bootstrap.php';


	Assert::null(Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'noType')));

	Assert::same('Test\B', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'classType')));

	Assert::same('string', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'nativeType')));

	Assert::same('NS\A', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'selfType')));

	Assert::same('Test\B', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'nullableClassType')));

	Assert::same('string', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'nullableNativeType')));

	Assert::same('NS\A', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'nullableSelfType')));
}
