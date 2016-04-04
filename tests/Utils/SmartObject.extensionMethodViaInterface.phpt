<?php

/**
 * Test: Nette\SmartObject extension method via interface (deprecated)
 */

use Nette\SmartObject;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


interface IFirst
{}

interface ISecond extends IFirst
{}

class TestClass implements ISecond
{
	use SmartObject;

	public $foo = 'Hello', $bar = 'World';
}


function IFirst_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}


function ISecond_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}


@SmartObject::extensionMethod('ISecond::join', 'ISecond_join');
@SmartObject::extensionMethod('IFirst::join', 'IFirst_join');

$obj = new TestClass;
Assert::same('ISecond_join says Hello*World', @$obj->join('*'));

Assert::same(
	['join' => 'ISecond_join'],
	Nette\Utils\ObjectMixin::getExtensionMethods(TestClass::class)
);

Assert::same(
	['join' => 'IFirst_join'],
	Nette\Utils\ObjectMixin::getExtensionMethods(IFirst::class)
);

Assert::exception(function () {
	$obj = new TestClass;
	$obj->joi();
}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::joi().');
