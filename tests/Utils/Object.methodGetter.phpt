<?php

/**
 * Test: Nette\Object closure properties.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
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


$obj1 = new TestClass(1);
$method = $obj1->publicMethod;
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


Assert::error(function () {
	$obj = new TestClass;
	$method = $obj->getWithoutParameters;
}, E_USER_WARNING, 'Did you forgot parentheses after getWithoutParameters in ' . __FILE__ . ':' . (__LINE__ - 1) . '?');
