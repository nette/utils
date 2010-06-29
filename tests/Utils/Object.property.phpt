<?php

/**
 * Test: Nette\Object properties.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Object;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Object.inc';



$obj = new TestClass;
$obj->foo = 'hello';
T::dump( $obj->foo );
T::dump( $obj->Foo );

$obj->foo .= ' world';
T::dump( $obj->foo );


T::note('Undeclared property writing');
try {
	$obj->undeclared = 'value';
} catch (Exception $e) {
	T::dump( $e );
}


T::note('Undeclared property reading');
T::dump( isset($obj->S) ); // False
T::dump( isset($obj->s) ); // False
T::dump( isset($obj->undeclared) ); // False
try {
	$val = $obj->s;
} catch (Exception $e) {
	T::dump( $e );
}



T::note('Read-only property');
$obj = new TestClass('Hello', 'World');
T::dump( $obj->bar );
try {
	$obj->bar = 'value';
} catch (Exception $e) {
	T::dump( $e );
}



__halt_compiler() ?>

------EXPECT------
string(5) "hello"

string(5) "hello"

string(11) "hello world"

Undeclared property writing

Exception MemberAccessException: Cannot write to an undeclared property TestClass::$undeclared.

Undeclared property reading

bool(FALSE)

bool(FALSE)

bool(FALSE)

Exception MemberAccessException: Cannot read an undeclared property TestClass::$s.

Read-only property

string(5) "World"

Exception MemberAccessException: Cannot write to a read-only property TestClass::$bar.
