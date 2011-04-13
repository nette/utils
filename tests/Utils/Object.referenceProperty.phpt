<?php

/**
 * Test: Nette\Object reference to property.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */




require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Object.inc';



$obj = new TestClass;
$obj->foo = 'hello';
@$x = & $obj->foo;
$x = 'changed by reference';
Assert::same( 'hello', $obj->foo );
