<?php

/**
 * Test: Nette\Utils\Reflection::getReturnType
 * @phpversion 8.0
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

	Assert::same('NS\A', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'staticType')));

	Assert::same('Test\B', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'nullableClassType')));

	Assert::same('string', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'nullableNativeType')));

	Assert::same('NS\A', Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'nullableSelfType')));

	Assert::same(['NS\A', 'array'], Reflection::getReturnTypes(new \ReflectionMethod(NS\A::class, 'unionType')));

	Assert::same(['NS\A', 'array', 'null'], Reflection::getReturnTypes(new \ReflectionMethod(NS\A::class, 'nullableUnionType')));

	Assert::exception(function () {
		Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'unionType'));
	}, Nette\InvalidStateException::class, 'The NS\A::unionType() is not expected to have a union type.');

	Assert::exception(function () {
		Reflection::getReturnType(new \ReflectionMethod(NS\A::class, 'nullableUnionType'));
	}, Nette\InvalidStateException::class, 'The NS\A::nullableUnionType() is not expected to have a union type.');

	Assert::same('NS\A', Reflection::getReturnType(new \ReflectionMethod(NS\AExt::class, 'parentTypeExt')));

	Assert::null(Reflection::getReturnType(new \ReflectionFunction('NS\noType')));

	Assert::same('Test\B', Reflection::getReturnType(new \ReflectionFunction('NS\classType')));

	Assert::same('string', Reflection::getReturnType(new \ReflectionFunction('NS\nativeType')));

	Assert::same(['NS\A', 'array'], Reflection::getReturnTypes(new \ReflectionFunction('NS\unionType')));

	Assert::exception(function () {
		Reflection::getReturnType(new \ReflectionFunction('NS\unionType'));
	}, Nette\InvalidStateException::class, 'The NS\unionType() is not expected to have a union type.');
}
