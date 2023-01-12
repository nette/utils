<?php

/**
 * Test: Nette\SmartObject properties and inheritance.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @property int $traitA
 */
trait TraitA
{
	public function getTraitA()
	{
		return __FUNCTION__;
	}
}

/**
 * @property int $traitB
 */
trait TraitB
{
	public function getTraitB()
	{
		return __FUNCTION__;
	}
}

/**
 * @property int $traitC
 */
trait TraitC
{
	use TraitB;

	public function getTraitC()
	{
		return __FUNCTION__;
	}
}

/**
 * @property int $classA
 */
class ParentClass
{
	use Nette\SmartObject;
	use TraitA;

	public function getClassA()
	{
		return __FUNCTION__;
	}
}

/**
 * @property int $classB
 */
class ChildClass extends ParentClass
{
	use TraitC;

	public function getClassB()
	{
		return __FUNCTION__;
	}
}


$obj = new ChildClass;

Assert::same('getTraitA', $obj->traitA);
Assert::same('getTraitB', $obj->traitB);
Assert::same('getTraitC', $obj->traitC);
Assert::same('getClassA', $obj->classA);
Assert::same('getClassB', $obj->classB);

Assert::exception(
	fn() => $obj->classBX,
	Nette\MemberAccessException::class,
	'Cannot read an undeclared property ChildClass::$classBX, did you mean $classB?',
);

Assert::exception(
	fn() => $obj->classAX,
	Nette\MemberAccessException::class,
	'Cannot read an undeclared property ChildClass::$classAX, did you mean $classA?',
);

Assert::exception(
	fn() => $obj->traitCX,
	Nette\MemberAccessException::class,
	'Cannot read an undeclared property ChildClass::$traitCX, did you mean $traitC?',
);

Assert::exception(
	fn() => $obj->traitBX,
	Nette\MemberAccessException::class,
	'Cannot read an undeclared property ChildClass::$traitBX, did you mean $traitB?',
);

Assert::exception(
	fn() => $obj->traitAX,
	Nette\MemberAccessException::class,
	'Cannot read an undeclared property ChildClass::$traitAX, did you mean $traitA?',
);
