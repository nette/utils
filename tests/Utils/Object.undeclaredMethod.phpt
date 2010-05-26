<?php

/**
 * Test: Nette\Object undeclared method.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Object;



require __DIR__ . '/../NetteTest/initialize.php';

require __DIR__ . '/Object.inc';



try {
	$obj = new TestClass;
	$obj->undeclared();

} catch (Exception $e) {
	dump( $e );
}



__halt_compiler() ?>

------EXPECT------
Exception MemberAccessException: Call to undefined method TestClass::undeclared().
