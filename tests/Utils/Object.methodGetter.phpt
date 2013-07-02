<?php

/**
 * Test: Nette\Object closure properties.
 *
 * @author     David Grudl
 * @package    Nette
 */


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

}


$obj1 = new TestClass(1);
$method = $obj1->publicMethod;
Assert::same( "1 2 3", $method(2, 3) );


Assert::exception(function() {
	$obj = new TestClass;
	$method = $obj->protectedMethod;
}, 'Nette\MemberAccessException', 'Cannot read an undeclared property TestClass::$protectedMethod.');


Assert::exception(function() {
	$obj = new TestClass;
	$method = $obj->privateMethod;
}, 'Nette\MemberAccessException', 'Cannot read an undeclared property TestClass::$privateMethod.');
