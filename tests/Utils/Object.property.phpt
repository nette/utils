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
Assert::same( 'hello', $obj->foo );
Assert::same( 'hello', $obj->Foo );


$obj->foo .= ' world';
Assert::same( 'hello world', $obj->foo );



// Undeclared property writing
try {
	$obj->undeclared = 'value';
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('MemberAccessException', 'Cannot write to an undeclared property TestClass::$undeclared.', $e );
}


// Undeclared property reading
Assert::false( isset($obj->S) );
Assert::false( isset($obj->s) );
Assert::false( isset($obj->undeclared) );

try {
	$val = $obj->s;
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('MemberAccessException', 'Cannot read an undeclared property TestClass::$s.', $e );
}



// Read-only property
$obj = new TestClass('Hello', 'World');
Assert::same( 'World', $obj->bar );

try {
	$obj->bar = 'value';
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('MemberAccessException', 'Cannot write to a read-only property TestClass::$bar.', $e );
}
