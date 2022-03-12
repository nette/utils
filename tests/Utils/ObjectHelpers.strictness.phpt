<?php

/**
 * Test: Nette\Utils\ObjectHelpers: strictness
 */

declare(strict_types=1);

use Nette\MemberAccessException;
use Nette\Utils\ObjectHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	public $public;

	public static $publicStatic;

	protected $protected;


	public function publicMethod()
	{
	}


	public static function publicMethodStatic()
	{
	}


	protected function protectedMethod()
	{
	}


	protected static function protectedMethodS()
	{
	}
}

class TestChild extends TestClass
{
	public function callParent()
	{
		parent::callParent();
	}
}


// calling
Assert::exception(
	fn() => ObjectHelpers::strictCall('TestClass', 'undeclared'),
	MemberAccessException::class,
	'Call to undefined method TestClass::undeclared().',
);

Assert::exception(
	fn() => ObjectHelpers::strictStaticCall('TestClass', 'undeclared'),
	MemberAccessException::class,
	'Call to undefined static method TestClass::undeclared().',
);

Assert::exception(
	fn() => ObjectHelpers::strictCall('TestChild', 'callParent'),
	MemberAccessException::class,
	'Call to method TestChild::callParent() from global scope.',
);

Assert::exception(
	fn() => ObjectHelpers::strictCall('TestClass', 'publicMethodX'),
	MemberAccessException::class,
	'Call to undefined method TestClass::publicMethodX(), did you mean publicMethod()?',
);

Assert::exception(
	fn() => ObjectHelpers::strictCall('TestClass', 'publicMethodStaticX'),
	MemberAccessException::class,
	'Call to undefined method TestClass::publicMethodStaticX(), did you mean publicMethodStatic()?',
);

Assert::exception(
	fn() => ObjectHelpers::strictStaticCall('TestClass', 'publicMethodStaticX'),
	MemberAccessException::class,
	'Call to undefined static method TestClass::publicMethodStaticX(), did you mean publicMethodStatic()?',
);

Assert::exception(
	fn() => ObjectHelpers::strictCall('TestClass', 'protectedMethodX'),
	MemberAccessException::class,
	'Call to undefined method TestClass::protectedMethodX().',
);


// writing
Assert::exception(
	fn() => ObjectHelpers::strictSet('TestClass', 'undeclared'),
	MemberAccessException::class,
	'Cannot write to an undeclared property TestClass::$undeclared.',
);

Assert::exception(
	fn() => ObjectHelpers::strictSet('TestClass', 'publicX'),
	MemberAccessException::class,
	'Cannot write to an undeclared property TestClass::$publicX, did you mean $public?',
);

Assert::exception(
	fn() => ObjectHelpers::strictSet('TestClass', 'publicStaticX'),
	MemberAccessException::class,
	'Cannot write to an undeclared property TestClass::$publicStaticX.',
);

Assert::exception(
	fn() => ObjectHelpers::strictSet('TestClass', 'protectedX'),
	MemberAccessException::class,
	'Cannot write to an undeclared property TestClass::$protectedX.',
);


// reading
Assert::exception(
	fn() => ObjectHelpers::strictGet('TestClass', 'undeclared'),
	MemberAccessException::class,
	'Cannot read an undeclared property TestClass::$undeclared.',
);

Assert::exception(
	fn() => ObjectHelpers::strictGet('TestClass', 'publicX'),
	MemberAccessException::class,
	'Cannot read an undeclared property TestClass::$publicX, did you mean $public?',
);

Assert::exception(
	fn() => ObjectHelpers::strictGet('TestClass', 'publicStaticX'),
	MemberAccessException::class,
	'Cannot read an undeclared property TestClass::$publicStaticX.',
);

Assert::exception(
	fn() => ObjectHelpers::strictGet('TestClass', 'protectedX'),
	MemberAccessException::class,
	'Cannot read an undeclared property TestClass::$protectedX.',
);
