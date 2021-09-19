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

Assert::same('Undeclared', (string) Reflection::getParameterType($params[0]));
Assert::same('Test\B', (string) Reflection::getParameterType($params[1]));
Assert::same('array', (string) Reflection::getParameterType($params[2]));
Assert::same('callable', (string) Reflection::getParameterType($params[3]));
Assert::same('A', (string) Reflection::getParameterType($params[4]));
Assert::null(Reflection::getParameterType($params[5]));
Assert::same('?Test\B', (string) Reflection::getParameterType($params[6]));
Assert::same('mixed', (string) Reflection::getParameterType($params[7]));
Assert::same('A|array', (string) Reflection::getParameterType($params[8], false));
Assert::same('A|array|null', (string) Reflection::getParameterType($params[9], false));
$method = new ReflectionMethod('AExt', 'methodExt');
$params = $method->getParameters();

Assert::same('A', (string) Reflection::getParameterType($params[0]));
