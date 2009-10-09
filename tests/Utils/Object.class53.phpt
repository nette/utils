<?php

/**
 * Test: Nette\Object class name 5.3
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';



if (/*Nette\*/Framework::PACKAGE !== 'PHP 5.3') {
	NetteTestHelpers::skip('Requires Nette Framework package >= PHP 5.3.');
}



dump( TestClass::getClass() );



__halt_compiler();

------EXPECT------
string(9) "TestClass"
