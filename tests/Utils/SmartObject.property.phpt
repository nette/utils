<?php

/**
 * Test: Nette\SmartObject properties.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @property int $foo
 * @property int $bar
 * @property int $bazz
 * @property int $s
 * @property-deprecated int $depr
 */
class TestClass
{
	use Nette\SmartObject;

	public $declared;

	private $foo;

	private $bar;


	public function __construct($foo = null, $bar = null)
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


	protected function getBar()
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


	public function setDepr($value)
	{
	}


	public function getDepr()
	{
	}
}


$obj = new TestClass;
$obj->foo = 'hello';
Assert::same('hello', $obj->foo);

$obj->foo .= ' world';
Assert::same('hello world', $obj->foo);


// Undeclared property writing
Assert::exception(function () use ($obj) {
	$obj->undeclared = 'value';
}, Nette\MemberAccessException::class, 'Cannot write to an undeclared property TestClass::$undeclared, did you mean $declared?');


// Undeclared property reading
Assert::false(isset($obj->S));
Assert::false(isset($obj->s));
Assert::false(isset($obj->undeclared));

Assert::exception(function () use ($obj) {
	$val = $obj->undeclared;
}, Nette\MemberAccessException::class, 'Cannot read an undeclared property TestClass::$undeclared, did you mean $declared?');


// Read-only property
$obj = new TestClass('Hello', 'World');
Assert::true(isset($obj->bar));
Assert::same('World', $obj->bar);

Assert::exception(function () use ($obj) {
	$obj->bar = 'value';
}, Nette\MemberAccessException::class, 'Cannot write to a read-only property TestClass::$bar.');


// write-only property
$obj = new TestClass;
Assert::true(isset($obj->bazz));
$obj->bazz = 'World';
Assert::same('World', $obj->bar);

Assert::exception(function () use ($obj) {
	$val = $obj->bazz;
}, Nette\MemberAccessException::class, 'Cannot read a write-only property TestClass::$bazz.');


// deprecated property
Assert::error(function () {
	$obj = new TestClass;
	$obj->depr = 10;
}, E_USER_DEPRECATED, 'Property TestClass::$depr is deprecated, use TestClass::setDepr() method in %a%SmartObject.property.phpt on line %d%.');

Assert::error(function () {
	$obj = new TestClass;
	$val = $obj->depr;
}, E_USER_DEPRECATED, 'Property TestClass::$depr is deprecated, use TestClass::getDepr() method in %a%SmartObject.property.phpt on line %d%.');
