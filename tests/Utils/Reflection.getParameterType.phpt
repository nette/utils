<?php

/**
 * Test: Nette\Utils\Reflection::getParameterType
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


use Test\B; // for testing purposes

class A
{
	function method(Undeclared $undeclared, B $b, array $array, callable $callable, self $self, $none)
	{}
}

class AExt extends A
{
	function methodExt(parent $parent)
	{}
}

$method = new ReflectionMethod('A', 'method');
$params = $method->getParameters();

Assert::same('Undeclared', Reflection::getParameterType($params[0]));
Assert::same('Test\B', Reflection::getParameterType($params[1]));
Assert::same('array', Reflection::getParameterType($params[2]));
Assert::same('callable', Reflection::getParameterType($params[3]));
Assert::same('A', Reflection::getParameterType($params[4]));
Assert::null(Reflection::getParameterType($params[5]));


$method = new ReflectionMethod('AExt', 'methodExt');
$params = $method->getParameters();

Assert::same('A', Reflection::getParameterType($params[0]));
