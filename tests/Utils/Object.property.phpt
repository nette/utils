<?php

/**
 * Test: Object roperties.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';



$obj = new TestClass;
$obj->foo = 'hello';
dump( $obj->foo );
dump( $obj->Foo );

$obj->foo .= ' world';
dump( $obj->foo );


section('Undeclared property writing');
try {
	$obj->undeclared = 'value';
} catch (Exception $e) {
	dump( $e );
}


section('Undeclared property reading');
dump( isset($obj->S) ); // False
dump( isset($obj->s) ); // False
dump( isset($obj->undeclared) ); // False
try {
	$val = $obj->s;
} catch (Exception $e) {
	dump( $e );
}



section('Read-only property');
$obj = new TestClass('Hello', 'World');
dump( $obj->bar );
try {
	$obj->bar = 'value';
} catch (Exception $e) {
	dump( $e );
}



__halt_compiler();

------EXPECT------
string(5) "hello"

string(5) "hello"

string(11) "hello world"

==> Undeclared property writing

Exception MemberAccessException: Cannot assign to an undeclared property TestClass::$undeclared.

==> Undeclared property reading

bool(FALSE)

bool(FALSE)

bool(FALSE)

Exception MemberAccessException: Cannot read an undeclared property TestClass::$s.

==> Read-only property

string(5) "World"

Exception MemberAccessException: Cannot assign to a read-only property TestClass::$bar.
