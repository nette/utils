<?php

/**
 * Test: Nette\Utils\Callback tests.
 *
 * @author     David Grudl
 * @package    Nette
 */

use Nette\Utils\Callback;


require __DIR__ . '/../bootstrap.php';


class Test
{
	static function add($a, $b)
	{
		return $a + $b;
	}

	function __invoke()
	{
	}

}

test(function() {
	Assert::same( 'undefined', Callback::unwrap(Callback::closure('undefined')) );
	Assert::same( 'undefined', Callback::toString('undefined') );
	Assert::exception(function() {
		Callback::toReflection('undefined');
	}, 'ReflectionException');

	Assert::same( 'trim', Callback::unwrap(Callback::closure('trim')) );
	Assert::same( 'trim', Callback::toString('trim') );
	Assert::same( 'trim()', (string) Callback::toReflection('trim') );

	Assert::same( array('Test', 'add'), Callback::unwrap(Callback::closure('Test', 'add')) );
	Assert::same( 'Test::add', Callback::toString(array('Test', 'add')) );
	Assert::same( 'Test::add()', (string) Callback::toReflection(array('Test', 'add')) );

	Assert::same( 'Test::add', Callback::unwrap(Callback::closure('Test::add')) );
	Assert::same( 'Test::add', Callback::toString('Test::add') );
	Assert::same( 'Test::add()', (string) Callback::toReflection('Test::add') );

	$test = new Test;
	Assert::same( array($test, 'add'), Callback::unwrap(Callback::closure($test, 'add')) );
	Assert::same( 'Test::add', Callback::toString(array($test, 'add')) );
	Assert::same( 'Test::add()', (string) Callback::toReflection(array($test, 'add')) );

	Assert::same( $test, Callback::unwrap(Callback::closure($test)) );
	Assert::same( 'Test::__invoke', Callback::toString($test) );
	Assert::same( 'Test::__invoke()', (string) Callback::toReflection($test) );

	$closure = function() {};
	Assert::same( $closure, Callback::closure($closure) );
	Assert::same( '{closure}', Callback::toString($closure) );
	Assert::same( '{closure}()', (string) Callback::toReflection($closure) );

	Assert::same( '{closure Test::add}', Callback::toString(Callback::closure($test, 'add')) );
	Assert::same( 'Test::add()', (string) Callback::toReflection(Callback::closure($test, 'add')) );
});


test(function() {
	$cb = array(new Test, 'add');

	Assert::same( 8, Callback::invoke($cb, 3, 5) );
	Assert::same( 8, Callback::invokeArgs($cb, array(3, 5)) );

	Assert::exception(function() {
		Callback::invoke('undefined');
	}, 'Nette\InvalidArgumentException', "Callback 'undefined' is not callable.");

	Assert::exception(function() {
		Callback::invokeArgs('undefined');
	}, 'Nette\InvalidArgumentException', "Callback 'undefined' is not callable.");
});


test(function() {
	$cb = array(new Test, 'add');

	Assert::same( $cb, Callback::check($cb) );
	Callback::check('undefined', TRUE);

	Assert::exception(function() {
		Callback::check(123, TRUE);
	}, 'Nette\InvalidArgumentException', 'Given value is not a callable type.');

	Assert::exception(function() {
		Callback::check('undefined');
	}, 'Nette\InvalidArgumentException', "Callback 'undefined' is not callable.");
});
