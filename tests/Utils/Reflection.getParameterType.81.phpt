<?php

/**
 * Test: Nette\Utils\Reflection::getParameterType
 * @phpversion 8.1
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Test\B; // for testing purposes
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class A
{
	public function method(
		Undeclared $undeclared,
		B $b,
		array $array,
		callable $callable,
		self $self,
		$none,
		?B $nullable,
		mixed $mixed,
		array|self $union,
		array|self|null $nullableUnion,
		AExt&A $intersection,
	) {
	}
}

class AExt extends A
{
	public function methodExt(parent $parent)
	{
	}
}

$method = new ReflectionMethod('A', 'method');
$params = $method->getParameters();

Assert::same('Undeclared', Reflection::getParameterType($params[0]));
Assert::same('Test\B', Reflection::getParameterType($params[1]));
Assert::same('array', Reflection::getParameterType($params[2]));
Assert::same('callable', Reflection::getParameterType($params[3]));
Assert::same('A', Reflection::getParameterType($params[4]));
Assert::null(Reflection::getParameterType($params[5]));
Assert::same('Test\B', Reflection::getParameterType($params[6]));
Assert::same(['Test\B', 'null'], Reflection::getParameterTypes($params[6]));
Assert::same('mixed', Reflection::getParameterType($params[7]));
Assert::same(['mixed'], Reflection::getParameterTypes($params[7]));
Assert::same(['A', 'array'], Reflection::getParameterTypes($params[8]));
Assert::same(['A', 'array', 'null'], Reflection::getParameterTypes($params[9]));

Assert::exception(function () use ($params) {
	Reflection::getParameterType($params[8]);
}, Nette\InvalidStateException::class, 'The $union in A::method() is not expected to have a union or intersection type.');

Assert::exception(function () use ($params) {
	Reflection::getParameterType($params[9]);
}, Nette\InvalidStateException::class, 'The $nullableUnion in A::method() is not expected to have a union or intersection type.');

Assert::exception(function () use ($params) {
	Reflection::getParameterType($params[10]);
}, Nette\InvalidStateException::class, 'The $intersection in A::method() is not expected to have a union or intersection type.');

$method = new ReflectionMethod('AExt', 'methodExt');
$params = $method->getParameters();

Assert::same('A', Reflection::getParameterType($params[0]));
