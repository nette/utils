<?php

/**
 * Test: Nette\Utils\ObjectMixin::getMagicProperties()
 */

use Nette\Utils\ObjectMixin;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


/**
 * @property int $getter
 * @property int $getter2 by ref
 * @property int $setter
 * @property A\B $both with typehint
 * @property int $missing
 * @property int $Upper
 * @property int $cAse
 * @property int $private
 * @property int $protected
 * @property int $static
 * @property-read int $read
 * @property-write int $write
 * @property-x int $invalid1
 * @property $invalid2
 * abc @property int $invalid3
 */
class TestClass
{
	function getGetter()
	{}

	function & isGetter2()
	{}

	function setSetter()
	{}

	function getBoth()
	{}

	function setBoth()
	{}

	function getUpper()
	{}

	function getCase()
	{}

	private function getPrivate()
	{}

	protected function getProtected()
	{}

	static function getStatic()
	{}

	function getRead()
	{}

	function setRead()
	{}

	function getWrite()
	{}

	function setWrite()
	{}

	function getInvalid()
	{}

	function getInvalid2()
	{}

}



Assert::same([], ObjectMixin::getMagicProperties('stdClass'));
Assert::same([
	'getter' => 0b0011,
	'getter2' => 0b0101,
	'setter' => 0b1000,
	'both' => 0b1011,
	'Upper' => 0b0011,
	'protected' => 0b0011,
	'read' => 0b0011,
	'write' => 0b1000,
], ObjectMixin::getMagicProperties('TestClass'));



/**
* @property int $bar
*/
class ParentClass
{
	function getFoo()
	{}
}

/**
* @property int $foo
*/
class ChildClass extends ParentClass
{
	function getBar()
	{}
}

Assert::same([], ObjectMixin::getMagicProperties('ParentClass'));
Assert::same(['foo' => 0b0011], ObjectMixin::getMagicProperties('ChildClass'));
