<?php

/**
 * Test: Nette\Utils\ObjectMixin: strictness
 */

use Tester\Assert;
use Nette\Utils\ObjectMixin;
use Nette\MemberAccessException;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	public $public;

	protected $protected;

	public static $publicStatic;

	public function publicMethod()
	{}

	public static function publicMethodStatic()
	{}

	protected function protectedMethod()
	{}

	protected static function protectedMethodS()
	{}
}

class TestChild extends TestClass
{
	public function callParent()
	{
		parent::callParent();
	}
}


// calling
Assert::exception(function () {
	ObjectMixin::strictCall('TestClass', 'undeclared');
}, MemberAccessException::class, 'Call to undefined method TestClass::undeclared().');

Assert::exception(function () {
	ObjectMixin::strictStaticCall('TestClass', 'undeclared');
}, MemberAccessException::class, 'Call to undefined static method TestClass::undeclared().');

Assert::exception(function () {
	ObjectMixin::strictCall('TestChild', 'callParent');
}, MemberAccessException::class, 'Call to undefined method parent::callParent().');

Assert::exception(function () {
	ObjectMixin::strictCall('TestClass', 'publicMethodX');
}, MemberAccessException::class, 'Call to undefined method TestClass::publicMethodX(), did you mean publicMethod()?');

Assert::exception(function () { // suggest static method
	ObjectMixin::strictCall('TestClass', 'publicMethodStaticX');
}, MemberAccessException::class, 'Call to undefined method TestClass::publicMethodStaticX(), did you mean publicMethodStatic()?');

Assert::exception(function () { // suggest static method
	ObjectMixin::strictStaticCall('TestClass', 'publicMethodStaticX');
}, MemberAccessException::class, 'Call to undefined static method TestClass::publicMethodStaticX(), did you mean publicMethodStatic()?');

Assert::exception(function () { // suggest only public method
	ObjectMixin::strictCall('TestClass', 'protectedMethodX');
}, MemberAccessException::class, 'Call to undefined method TestClass::protectedMethodX().');


// writing
Assert::exception(function () {
	ObjectMixin::strictSet('TestClass', 'undeclared');
}, MemberAccessException::class, 'Cannot write to an undeclared property TestClass::$undeclared.');

Assert::exception(function () {
	ObjectMixin::strictSet('TestClass', 'publicX');
}, MemberAccessException::class, 'Cannot write to an undeclared property TestClass::$publicX, did you mean $public?');

Assert::exception(function () {
	ObjectMixin::strictSet('TestClass', 'publicStaticX');
}, MemberAccessException::class, 'Cannot write to an undeclared property TestClass::$publicStaticX, did you mean $publicStatic?');

Assert::exception(function () { // suggest only public property
	ObjectMixin::strictSet('TestClass', 'protectedX');
}, MemberAccessException::class, 'Cannot write to an undeclared property TestClass::$protectedX.');


// reading
Assert::exception(function () {
	ObjectMixin::strictGet('TestClass', 'undeclared');
}, MemberAccessException::class, 'Cannot read an undeclared property TestClass::$undeclared.');

Assert::exception(function () {
	ObjectMixin::strictGet('TestClass', 'publicX');
}, MemberAccessException::class, 'Cannot read an undeclared property TestClass::$publicX, did you mean $public?');

Assert::exception(function () {
	ObjectMixin::strictGet('TestClass', 'publicStaticX');
}, MemberAccessException::class, 'Cannot read an undeclared property TestClass::$publicStaticX, did you mean $publicStatic?');

Assert::exception(function () { // suggest only public property
	ObjectMixin::strictGet('TestClass', 'protectedX');
}, MemberAccessException::class, 'Cannot read an undeclared property TestClass::$protectedX.');
