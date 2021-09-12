<?php

/**
 * Test: Nette\Utils\Reflection::getPropertyType
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class A
{
	public $none;
}

$class = new ReflectionClass('A');
$props = $class->getProperties();

Assert::null(Reflection::getPropertyType($props[0]));
Assert::same([], Reflection::getPropertyTypes($props[0]));
