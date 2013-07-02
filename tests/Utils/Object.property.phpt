<?php

/**
 * Test: Nette\Object properties.
 *
 * @author     David Grudl
 * @package    Nette
 */


require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
	private $foo, $bar;

	function __construct($foo = NULL, $bar = NULL)
	{
		$this->foo = $foo;
		$this->bar = $bar;
	}

	public function foo()
	{ // method getter has lower priority than getter
	}

	public function getFoo()
	{
		return $this->foo;
	}

	public function setFoo($foo)
	{
		$this->foo = $foo;
	}

	public function getBar()
	{
		return $this->bar;
	}

	public function setBazz($value)
	{
		$this->bar = $value;
	}

	public function gets() // or setupXyz, settle...
	{
		echo __METHOD__;
		return 'ERROR';
	}

}


$obj = new TestClass;
$obj->foo = 'hello';
Assert::same( 'hello', $obj->foo );
Assert::same( 'hello', $obj->Foo );


$obj->foo .= ' world';
Assert::same( 'hello world', $obj->foo );


// Undeclared property writing
Assert::exception(function() use ($obj) {
	$obj->undeclared = 'value';
}, 'Nette\MemberAccessException', 'Cannot write to an undeclared property TestClass::$undeclared.');


// Undeclared property reading
Assert::false( isset($obj->S) );
Assert::false( isset($obj->s) );
Assert::false( isset($obj->undeclared) );

Assert::exception(function() use ($obj) {
	$val = $obj->s;
}, 'Nette\MemberAccessException', 'Cannot read an undeclared property TestClass::$s.');


// Read-only property
$obj = new TestClass('Hello', 'World');
Assert::true( isset($obj->bar) );
Assert::same( 'World', $obj->bar );

Assert::exception(function() use ($obj) {
	$obj->bar = 'value';
}, 'Nette\MemberAccessException', 'Cannot write to a read-only property TestClass::$bar.');


// write-only property
$obj = new TestClass;
Assert::false( isset($obj->bazz) );
$obj->bazz = 'World';
Assert::same( 'World', $obj->bar );

Assert::exception(function() use ($obj) {
	$val = $obj->bazz;
}, 'Nette\MemberAccessException', 'Cannot read a write-only property TestClass::$bazz.');
