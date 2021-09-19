<?php

/**
 * Test: Nette\Utils\Reflection::getPropertyType
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
	public mixed $mixed;
	public array|self $union;
	public array|self|null $nullableUnion;
}

class AExt extends A
{
	public parent $parent;
}

$class = new ReflectionClass('A');
$props = $class->getProperties();

Assert::same('Undeclared', (string) Reflection::getPropertyType($props[0]));
Assert::same('Test\B', (string) Reflection::getPropertyType($props[1]));
Assert::same('array', (string) Reflection::getPropertyType($props[2]));
Assert::same('A', (string) Reflection::getPropertyType($props[3]));
Assert::null(Reflection::getPropertyType($props[4]));
Assert::same('?Test\B', (string) Reflection::getPropertyType($props[5]));
Assert::same('mixed', (string) Reflection::getPropertyType($props[6]));
$class = new ReflectionClass('AExt');
$props = $class->getProperties();

Assert::same('A', (string) Reflection::getPropertyType($props[0]));
