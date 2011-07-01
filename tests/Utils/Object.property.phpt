<?php

/**
 * Test: Nette\Object properties.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */




require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Object.inc';



$obj = new TestClass;
$obj->foo = 'hello';
Assert::same( 'hello', $obj->foo );
Assert::same( 'hello', $obj->Foo );


$obj->foo .= ' world';
Assert::same( 'hello world', $obj->foo );



// Undeclared property writing
Assert::throws(function() use ($obj) {
	$obj->undeclared = 'value';
}, 'Nette\MemberAccessException', 'Cannot write to an undeclared property TestClass::$undeclared.');


// Undeclared property reading
Assert::false( isset($obj->S) );
Assert::false( isset($obj->s) );
Assert::false( isset($obj->undeclared) );

Assert::throws(function() use ($obj) {
	$val = $obj->s;
}, 'Nette\MemberAccessException', 'Cannot read an undeclared property TestClass::$s.');



// Read-only property
$obj = new TestClass('Hello', 'World');
Assert::true( isset($obj->bar) );
Assert::same( 'World', $obj->bar );

Assert::throws(function() use ($obj) {
	$obj->bar = 'value';
}, 'Nette\MemberAccessException', 'Cannot write to a read-only property TestClass::$bar.');



// write-only property
$obj = new TestClass;
Assert::false( isset($obj->bazz) );
$obj->bazz = 'World';
Assert::same( 'World', $obj->bar );

Assert::throws(function() use ($obj) {
	$val = $obj->bazz;
}, 'Nette\MemberAccessException', 'Cannot read a write-only property TestClass::$bazz.');
