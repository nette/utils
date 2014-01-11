<?php

/**
 * Test: Nette\Object magic @methods inheritance.
 *
 * @author     David Grudl
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @method setA
 * @method getA
 * @method setC
 * @method getC
 */
class ParentClass extends Nette\Object
{
	protected $a;
	private $b;
}

/**
 * @method setB
 * @method getB
 */
class ChildClass extends ParentClass
{
	public $c;
}

$obj = new ChildClass;

$obj->setA('hello');
Assert::same( 'hello', $obj->getA() );

Assert::exception(function() use ($obj) {
	$obj->setC(123);
}, 'Nette\MemberAccessException', 'Call to undefined method ChildClass::setC().');


Assert::exception(function() use ($obj) {
	$obj->setB(123);
}, 'Nette\MemberAccessException', 'Call to undefined method ChildClass::setB().');
