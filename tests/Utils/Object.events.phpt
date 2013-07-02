<?php

/**
 * Test: Nette\Object event handlers.
 *
 * @author     David Grudl
 * @package    Nette
 */


require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
	public $onPublic;

	static public $onPublicStatic;

	protected $onProtected;

	private $onPrivate;

}


function handler($obj)
{
	$obj->counter++;
}


class Handler
{
	function __invoke($obj)
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
Assert::same( 1, $var->counter );


$obj->onPublic[] = new Handler;

$obj->onPublic($var);
Assert::same( 3, $var->counter );


Assert::exception(function() use ($obj) {
	$obj->onPublicStatic(123);
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::onPublicStatic().');


Assert::exception(function() use ($obj) {
	$obj->onProtected(123);
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::onProtected().');


Assert::exception(function() use ($obj) {
	$obj->onPrivate(123);
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::onPrivate().');


Assert::exception(function() use ($obj) {
	$obj->onUndefined(123);
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::onUndefined().');

Assert::exception(function() use ($obj) {
	$obj->onPublic = 'string';
	$obj->onPublic();
}, 'Nette\UnexpectedValueException', 'Property TestClass::$onPublic must be array or NULL, string given.');
