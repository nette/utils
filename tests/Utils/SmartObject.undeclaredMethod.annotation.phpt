<?php

/**
 * Test: Nette\SmartObject undeclared method and annotation @method.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @method traitA()
 */
trait TraitA
{
}

/**
 * @method traitB()
 */
trait TraitB
{
}

/**
 * @method traitC()
 */
trait TraitC
{
	use TraitB;
}

/**
 * @method classA()
 */
class ParentClass
{
	use Nette\SmartObject;
	use TraitA;
}

/**
 * @method classB()
 */
class ChildClass extends ParentClass
{
	use TraitC;
}


$obj = new ChildClass;

Assert::exception(function () use ($obj) {
	$obj->classBX();
}, Nette\MemberAccessException::class, 'Call to undefined method ChildClass::classBX(), did you mean classB()?');

Assert::exception(function () use ($obj) {
	$obj->classAX();
}, Nette\MemberAccessException::class, 'Call to undefined method ChildClass::classAX(), did you mean classA()?');

Assert::exception(function () use ($obj) {
	$obj->traitCX();
}, Nette\MemberAccessException::class, 'Call to undefined method ChildClass::traitCX(), did you mean traitC()?');

Assert::exception(function () use ($obj) {
	$obj->traitBX();
}, Nette\MemberAccessException::class, 'Call to undefined method ChildClass::traitBX(), did you mean traitB()?');

Assert::exception(function () use ($obj) {
	$obj->traitAX();
}, Nette\MemberAccessException::class, 'Call to undefined method ChildClass::traitAX(), did you mean traitA()?');
