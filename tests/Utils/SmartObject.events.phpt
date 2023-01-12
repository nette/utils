<?php

/**
 * Test: Nette\SmartObject event handlers.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Nette\SmartObject;

	public $onPublic;

	public static $onPublicStatic;

	public array $onEvent;

	protected $onProtected;

	private $onPrivate;
}


function handler($obj)
{
	$obj->counter++;
}


class Handler
{
	public function __invoke($obj)
	{
		$obj->counter++;
	}
}


$obj = new TestClass;
$obj->onPublic();

$var = new stdClass;
$var->counter = 0;

$obj->onPublic[] = 'handler';

$obj->onPublic($var);
Assert::same(1, $var->counter);


$obj->onPublic[] = new Handler;

$obj->onPublic($var);
Assert::same(3, $var->counter);


Assert::exception(
	fn() => $obj->onPublicStatic(123),
	Nette\MemberAccessException::class,
	'Call to undefined method TestClass::onPublicStatic().',
);


Assert::exception(
	fn() => $obj->onProtected(123),
	Nette\MemberAccessException::class,
	'Call to undefined method TestClass::onProtected().',
);


Assert::exception(
	fn() => $obj->onPrivate(123),
	Nette\MemberAccessException::class,
	'Call to undefined method TestClass::onPrivate().',
);


Assert::exception(
	fn() => $obj->onUndefined(123),
	Nette\MemberAccessException::class,
	'Call to undefined method TestClass::onUndefined().',
);

Assert::exception(function () use ($obj) {
	$obj->onPublic = 'string';
	$obj->onPublic();
}, Nette\UnexpectedValueException::class, 'Property TestClass::$onPublic must be iterable or null, string given.');

$obj = new TestClass;
Assert::noError(fn() => $obj->onEvent());
