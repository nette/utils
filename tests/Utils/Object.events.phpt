<?php

/**
 * Test: Nette\Object event handlers.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */




require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Object.inc';



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
$var = (object) NULL;

$obj->onPublic[] = 'handler';

$obj->onPublic($var);
Assert::same( 1, $var->counter );



$obj->onPublic[] = new Handler;

$obj->onPublic($var);
Assert::same( 3, $var->counter );



Assert::throws(function() use ($obj) {
	$obj->onPrivate(123);
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::onPrivate().');


Assert::throws(function() use ($obj) {
	$obj->onUndefined(123);
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::onUndefined().');
