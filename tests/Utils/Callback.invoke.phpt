<?php

/**
 * Test: Nette\Utils\Callback::invoke() and invokeArgs()
 */

use Nette\Utils\Callback,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test
{

	public function fun($a)
	{
		return __METHOD__ . $a;
	}

	public function ref(& $a)
	{
		$a = __METHOD__;
		return $a;
	}

}


$cb = array(new Test, 'fun');
Assert::same( 'Test::fun*', Callback::invoke($cb, '*') );
Assert::same( 'Test::fun*', Callback::invokeArgs($cb, array('*')) );


$cb = array(new Test, 'ref');
Assert::same( 'Test::ref', Callback::invokeArgs($cb, array(& $ref)) );
Assert::same( 'Test::ref', $ref );


Assert::exception(function() {
	Callback::invoke('undefined');
}, 'Nette\InvalidArgumentException', "Callback 'undefined' is not callable.");


Assert::exception(function() {
	Callback::invokeArgs('undefined');
}, 'Nette\InvalidArgumentException', "Callback 'undefined' is not callable.");
