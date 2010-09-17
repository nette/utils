<?php

/**
 * Test: Nette\Object reflection.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Object;



require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Object.inc';



$obj = new TestClass;
Assert::same( 'TestClass', $obj->getReflection()->getName() );
Assert::same( 'TestClass', $obj->Reflection->getName() );
