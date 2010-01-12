<?php

/**
 * Test: Nette\Callback tests.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Callback;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



class Test
{
	function add($a, $b)
	{
		return $a + $b;
	}
}


dump( (string) new Callback(new Test, 'add') );

dump( (string) new Callback('Test', 'add') );

dump( (string) new Callback('Test::add') );

dump( (string) new Callback('undefined') );


$cb = new Callback(new Test, 'add');

dump( $cb/**/->__invoke/**/(3, 5) );

dump( $cb->invokeArgs(array(3, 5)) );

dump( $cb->getNative() );

dump( $cb->isCallable() );

dump( callback($cb) );

try {
	callback('undefined')->check('undefined');
} catch (Exception $e) {
	dump( $e );
}

try {
	callback(NULL)->check('invalid');
} catch (Exception $e) {
	dump( $e );
}



__halt_compiler();

------EXPECT------
string(9) "Test::add"

string(9) "Test::add"

string(9) "Test::add"

string(9) "undefined"

int(8)

int(8)

array(2) {
	0 => object(Test) (0) {}
	1 => string(3) "add"
}

bool(TRUE)

object(Callback) (1) {
	"cb" private => array(2) {
		0 => object(Test) (0) {}
		1 => string(3) "add"
	}
}

Exception InvalidStateException: undefined 'undefined' is not callable.

Exception InvalidStateException: invalid '' is not valid.
