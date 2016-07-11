<?php

/**
 * Test: Nette\Utils\Reflection::getParameterType
 * @phpversion 7.1
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


use Test\B; // for testing purposes

class A
{
	function method(Undeclared $undeclared, B $b, array $array, callable $callable, $none, ?B $nullable)
	{}
}

$method = new \ReflectionMethod('A', 'method');
$params = $method->getParameters();

Assert::same('Undeclared', Reflection::getParameterType($params[0]));
Assert::same('Test\B', Reflection::getParameterType($params[1]));
Assert::same('array', Reflection::getParameterType($params[2]));
Assert::same('callable', Reflection::getParameterType($params[3]));
Assert::null(Reflection::getParameterType($params[4]));
Assert::same('Test\B', Reflection::getParameterType($params[5]));
