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

	Assert::same('NS\A', Reflection::getReturnType(new \ReflectionMethod(NS\AExt::class, 'parentTypeExt')));

	Assert::null(Reflection::getReturnType(new \ReflectionFunction('NS\noType')));

	Assert::same('Test\B', Reflection::getReturnType(new \ReflectionFunction('NS\classType')));

	Assert::same('string', Reflection::getReturnType(new \ReflectionFunction('NS\nativeType')));

}
