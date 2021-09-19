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

Assert::same('Undeclared', Reflection::getPropertyType($props[0]));
Assert::same('Test\B', Reflection::getPropertyType($props[1]));
Assert::same('array', Reflection::getPropertyType($props[2]));
Assert::same('A', Reflection::getPropertyType($props[3]));
Assert::null(Reflection::getPropertyType($props[4]));
Assert::same('Test\B', Reflection::getPropertyType($props[5]));
Assert::same(['Test\B', 'null'], Reflection::getPropertyTypes($props[5]));
Assert::same('mixed', Reflection::getPropertyType($props[6]));
Assert::same(['mixed'], Reflection::getPropertyTypes($props[6]));
Assert::same(['A', 'array'], Reflection::getPropertyTypes($props[7]));
Assert::same(['A', 'array', 'null'], Reflection::getPropertyTypes($props[8]));

Assert::exception(function () use ($props) {
	Reflection::getPropertyType($props[7]);
}, Nette\InvalidStateException::class, 'The A::$union is not expected to have a union or intersection type.');

Assert::exception(function () use ($props) {
	Reflection::getPropertyType($props[8]);
}, Nette\InvalidStateException::class, 'The A::$nullableUnion is not expected to have a union or intersection type.');

Assert::exception(function () use ($props) {
	Reflection::getPropertyType($props[9]);
}, Nette\InvalidStateException::class, 'The A::$intersection is not expected to have a union or intersection type.');

$class = new ReflectionClass('AExt');
$props = $class->getProperties();

Assert::same('A', Reflection::getPropertyType($props[0]));
