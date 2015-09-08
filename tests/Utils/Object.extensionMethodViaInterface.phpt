<?php

/**
 * Test: Nette\Object extension method via interface.
 */

use Nette\Object;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


interface IFirst
{}

interface ISecond extends IFirst
{}

class TestClass extends Object implements ISecond
{
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


Object::extensionMethod('ISecond::join', 'ISecond_join');
Object::extensionMethod('IFirst::join', 'IFirst_join');

$obj = new TestClass;
Assert::same('ISecond_join says Hello*World', $obj->join('*'));

Assert::same(
	['join' => 'ISecond_join'],
	Nette\Utils\ObjectMixin::getExtensionMethods(TestClass::class)
);

Assert::same(
	['join' => 'IFirst_join'],
	Nette\Utils\ObjectMixin::getExtensionMethods(IFirst::class)
);
