<?php

/**
 * Test: PHP native error messages for undeclared method.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class ParentClass
{
	public function callPrivate()
	{
		$this->privateMethod();
	}


	public function callPrivateStatic()
	{
		static::privateStaticMethod();
	}


	private function callPrivateParent()
	{
	}
}


class InterClass extends ParentClass
{
	public function callParents()
	{
		parent::callParents();
	}
}


class ChildClass extends InterClass
{
	public function callParents()
	{
		parent::callParents();
	}


	public function callMissingParent()
	{
		parent::callMissingParent();
	}


	public static function callMissingParentStatic()
	{
		parent::callMissingParentStatic();
	}


	public function callPrivateParent()
	{
		parent::callPrivateParent();
	}


	protected function protectedMethod()
	{
	}


	protected static function protectedStaticMethod()
	{
	}


	private function privateMethod()
	{
	}


	private static function privateStaticMethod()
	{
	}
}



Assert::exception(function () {
	$obj = new ParentClass;
	$obj->undef();
}, Error::class, 'Call to undefined method ParentClass::undef()');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->undef();
}, Error::class, 'Call to undefined method ChildClass::undef()');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callParents();
}, Error::class, 'Call to undefined method ParentClass::callParents()');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callMissingParent();
}, Error::class, 'Call to undefined method InterClass::callMissingParent()');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callMissingParentStatic();
}, Error::class, 'Call to undefined method InterClass::callMissingParentStatic()');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj::callMissingParentStatic();
}, Error::class, 'Call to undefined method InterClass::callMissingParentStatic()');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callPrivateParent();
}, Error::class, 'Call to private method ParentClass::callPrivateParent() from scope ChildClass');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->protectedMethod();
}, Error::class, 'Call to protected method ChildClass::protectedMethod() from global scope');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->protectedStaticMethod();
}, Error::class, 'Call to protected method ChildClass::protectedStaticMethod() from global scope');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj::protectedStaticMethod();
}, Error::class, 'Call to protected method ChildClass::protectedStaticMethod() from global scope');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callPrivate();
}, Error::class, 'Call to private method ChildClass::privateMethod() from scope ParentClass');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callPrivateStatic();
}, Error::class, 'Call to private method ChildClass::privateStaticMethod() from scope ParentClass');
