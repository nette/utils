<?php

/**
 * Test: Object Class53
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 * @phpversion 5.3
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';



dump( TestClass::getClass() );



__halt_compiler();

------EXPECT------
string(9) "TestClass"
