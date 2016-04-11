<?php

/**
 * Test: Nette\SmartObject closure properties (deprecated)
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\SmartObject;

	private $id;

	function __construct($id = NULL)
	{
		$this->id = $id;
	}

	public function publicMethod($a, $b)
	{
		return "$this->id $a $b";
	}

	protected function protectedMethod()
	{
	}

	private function privateMethod()
	{
	}

	public function getWithoutParameters()
	{}

}


// deprecated
Assert::error(function () {
	$obj = new TestClass;
	$obj->publicMethod;
}, E_USER_DEPRECATED, 'Accessing methods as properties via $obj->publicMethod is deprecated in ' . __FILE__ . ':' . (__LINE__ - 1));


$obj1 = new TestClass(1);
$method = @$obj1->publicMethod;
Assert::same("1 2 3", $method(2, 3));

$rm = new ReflectionFunction($method);
Assert::same($obj1, $rm->getClosureThis());
Assert::same('publicMethod', $rm->getName());
Assert::same(2, $rm->getNumberOfParameters());


Assert::exception(function () {
	$obj = new TestClass;
	$method = $obj->protectedMethod;
}, Nette\MemberAccessException::class, 'Cannot read an undeclared property TestClass::$protectedMethod.');


Assert::exception(function () {
	$obj = new TestClass;
	$method = $obj->privateMethod;
}, Nette\MemberAccessException::class, 'Cannot read an undeclared property TestClass::$privateMethod.');
