<?php

/**
 * Test: Nette\SmartObject magic @methods inheritance (deprecated)
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @method setA()
 * @method getA()
 * @method setC()
 * @method getC()
 */
class ParentClass
{
	use Nette\SmartObject;

	protected $a;
	private $b;
}

/**
 * @method setB()
 * @method getB()
 */
class ChildClass extends ParentClass
{
	public $c;
}

$obj = new ChildClass;

@$obj->setA('hello');
Assert::same('hello', @$obj->getA());

Assert::exception(function () use ($obj) {
	$obj->setC(123);
}, Nette\MemberAccessException::class, 'Call to undefined method ChildClass::setC(), did you mean setB()?');


Assert::exception(function () use ($obj) {
	$obj->setB(123);
}, Nette\MemberAccessException::class, 'Call to undefined method ChildClass::setB(), did you mean getB()?');
