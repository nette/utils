<?php

/**
 * Test: Nette\Object reflection.
 *
 * @author     David Grudl
 * @package    Nette
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass extends Nette\Object
{
}


$obj = new TestClass;
Assert::same( 'TestClass', $obj->getReflection()->getName() );
Assert::same( 'TestClass', $obj->Reflection->getName() );
