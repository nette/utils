<?php

/**
 * Test: Nette\SmartObject error messages for undeclared method.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class ParentClass
{
	use Nette\SmartObject;

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
}, Nette\MemberAccessException::class, 'Call to undefined method ParentClass::undef().');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->undef();
}, Nette\MemberAccessException::class, 'Call to undefined method ChildClass::undef().');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callParents();
}, Nette\MemberAccessException::class, 'Call to undefined method ParentClass::callParents().');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callMissingParent();
}, Nette\MemberAccessException::class, 'Call to undefined method InterClass::callMissingParent().');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callMissingParentStatic();
}, Nette\MemberAccessException::class, 'Call to undefined static method InterClass::callMissingParentStatic().');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj::callMissingParentStatic();
}, Nette\MemberAccessException::class, 'Call to undefined static method InterClass::callMissingParentStatic().');

Assert::exception(
	function () {
		$obj = new ChildClass;
		$obj->callPrivateParent();
	},
	Nette\MemberAccessException::class,
	PHP_VERSION_ID < 70400
		? 'Call to private method InterClass::callPrivateParent() from scope ChildClass.'
		: (PHP_VERSION_ID < 80100
			? 'Call to undefined static method InterClass::callPrivateParent().' // differs from native error message
			: 'Call to undefined method InterClass::callPrivateParent().')
);

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->protectedMethod();
}, Nette\MemberAccessException::class, 'Call to protected method ChildClass::protectedMethod() from global scope.');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->protectedStaticMethod();
}, Nette\MemberAccessException::class, 'Call to protected method ChildClass::protectedStaticMethod() from global scope.');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj::protectedStaticMethod();
}, Nette\MemberAccessException::class, 'Call to protected method ChildClass::protectedStaticMethod() from global scope.');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callPrivate();
}, Nette\MemberAccessException::class, 'Call to private method ChildClass::privateMethod() from scope ParentClass.');

Assert::exception(function () {
	$obj = new ChildClass;
	$obj->callPrivateStatic();
}, Nette\MemberAccessException::class, 'Call to private method ChildClass::privateStaticMethod() from scope ParentClass.');
