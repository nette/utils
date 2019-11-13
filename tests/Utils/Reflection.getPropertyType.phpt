<?php

/**
 * Test: Nette\Utils\Reflection::getPropertyType
 * @phpversion 7.4
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Test\B; // for testing purposes
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class A
{
	public Undeclared $undeclared;
	public B $b;
	public array $array;
	public self $self;
	public $none;
	public ?B $nullable;
}

class AExt extends A
{
	public parent $parent;
}

$class = new ReflectionClass('A');
$props = $class->getProperties();

Assert::same('Undeclared', Reflection::getPropertyType($props[0]));
Assert::same('Test\B', Reflection::getPropertyType($props[1]));
Assert::same('array', Reflection::getPropertyType($props[2]));
Assert::same('A', Reflection::getPropertyType($props[3]));
Assert::null(Reflection::getPropertyType($props[4]));
Assert::same('Test\B', Reflection::getPropertyType($props[5]));

$class = new ReflectionClass('AExt');
$props = $class->getProperties();

Assert::same('A', Reflection::getPropertyType($props[0]));
