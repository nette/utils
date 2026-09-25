<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\ObjectHelpers::getMagicProperties()
 */

use Nette\Utils\ObjectHelpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


/**
 * @property int $getter
 * @property int $getter2 by ref
 * @property int $setter
 * @property A\B $both with typehint
 * @property array<string, string> $generic with space in type
 * @property array<int, array<string, mixed>> $nested
 * @property array{a: int, b?: string} $shape
 * @property array{
 *     multi: string,
 *     line: int,
 * } $multiline
 * @property array<string,
 *     int> $multilineGeneric
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
 * @property array{
 *     unterminated: string,
 * @property-read int $bridged
 */
class TestClass
{
	public function getGetter()
	{
	}


	public function &isGetter2()
	{
	}


	public function setSetter()
	{
	}


	public function getBoth()
	{
	}


	public function setBoth()
	{
	}


	public function getGeneric()
	{
	}


	public function getNested()
	{
	}


	public function getShape()
	{
	}


	public function getMultiline()
	{
	}


	public function getMultilineGeneric()
	{
	}


	public function getBridged()
	{
	}


	public function setBridged()
	{
	}


	public function getUpper()
	{
	}


	public function getCase()
	{
	}


	private function getPrivate()
	{
	}


	protected function getProtected()
	{
	}


	public static function getStatic()
	{
	}


	public function getRead()
	{
	}


	public function setRead()
	{
	}


	public function getWrite()
	{
	}


	public function setWrite()
	{
	}


	public function getInvalid()
	{
	}


	public function getInvalid2()
	{
	}
}



Assert::same([], ObjectHelpers::getMagicProperties('stdClass'));
Assert::same([
	'getter' => 0b0011,
	'getter2' => 0b0101,
	'setter' => 0b1000,
	'both' => 0b1011,
	'generic' => 0b0011,
	'nested' => 0b0011,
	'shape' => 0b0011,
	'multiline' => 0b0011,
	'multilineGeneric' => 0b0011,
	'Upper' => 0b0011,
	'protected' => 0b0011,
	'read' => 0b0011,
	'write' => 0b1000,
	'bridged' => 0b0011,
], ObjectHelpers::getMagicProperties('TestClass'));



/**
 * @property int $bar
 */
class ParentClass
{
	public function getFoo()
	{
	}
}

/**
 * @property int $foo
 */
class ChildClass extends ParentClass
{
	public function getBar()
	{
	}
}

Assert::same([], ObjectHelpers::getMagicProperties('ParentClass'));
Assert::same(['foo' => 0b0011], ObjectHelpers::getMagicProperties('ChildClass'));
