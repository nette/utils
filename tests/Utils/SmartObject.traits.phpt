<?php

/**
 * Test: Nette\SmartObject and magic properties/methods in traits
 */

use Nette\MemberAccessException;
use Nette\SmartObject;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @property-read string $foo
 * @property string $bar
 * @method string getLorem()
 * @method string setLorem(string $value)
 */
trait TestTrait
{

	private $bar;

	private $lorem = 'ipsum';


	public function getFoo()
	{
		return 'hello';
	}


	public function setBar($bar)
	{
		$this->bar = $bar;
	}


	public function getBar()
	{
		return $this->bar;
	}

}

class TestClass
{
	use SmartObject;
	use TestTrait;
}


$obj = new TestClass;
Assert::same('hello', $obj->foo);
$obj->bar = 'world';
Assert::same('world', $obj->bar);
Assert::exception(function () use ($obj) {
	$obj->fooo;

}, MemberAccessException::class, 'Cannot read an undeclared property TestClass::$fooo, did you mean $foo?');

Assert::same('ipsum', @$obj->getLorem());
@$obj->setLorem('bar');
Assert::same('bar', @$obj->getLorem());

Assert::error(function () use ($obj) {
	$obj->getLorem();
}, E_USER_DEPRECATED, 'Magic methods such as TestClass::getLorem() are deprecated in %a%');
