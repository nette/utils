<?php

/**
 * Test: Nette\Object array property.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Object;



require __DIR__ . '/../initialize.php';

require __DIR__ . '/Object.inc';



$obj = new TestClass;
$obj->items[] = 'test';
Assert::same( 'test', $obj->items[0] );


$obj->items = array();
$obj->items[] = 'one';
$obj->items[] = 'two';
Assert::same( 'one', $obj->items[0] );

Assert::same( 'two', $obj->items[1] );
