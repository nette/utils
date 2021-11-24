<?php

/**
 * Test: Nette\Utils\Reflection::getPropertyType
 * @phpversion 8.1
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
	public AExt&A $intersection;
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
Assert::same('A|array', (string) Reflection::getPropertyType($props[7], false));
Assert::same('A|array|null', (string) Reflection::getPropertyType($props[8], false));
$class = new ReflectionClass('AExt');
$props = $class->getProperties();

Assert::same('A', (string) Reflection::getPropertyType($props[0]));
