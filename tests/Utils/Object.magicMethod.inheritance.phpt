<?php

/**
 * Test: Nette\Object magic @methods inheritance.
 *
 * @author     David Grudl
 * @package    Nette
 */


require __DIR__ . '/../bootstrap.php';


/**
 * @method setA
 * @method getA
 * @method getC
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

$parent = new ParentClass;

$parent->setA('hello');
Assert::same( 'hello', $parent->getA() );

Assert::exception(function() use ($parent) {
	$parent->setC(123);
}, 'Nette\MemberAccessException', 'Call to undefined method ParentClass::setC().');


$child = new ChildClass;

$child->setA('hello');
Assert::same( 'hello', $child->getA() );

Assert::exception(function() use ($child) {
	$child->setB(123);
}, 'Nette\MemberAccessException', 'Magic method ChildClass::setB() has not corresponding property $b.');
