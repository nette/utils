<?php

/**
 * Test: Nette\ArrayTools::grep()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayTools;



require __DIR__ . '/../initialize.php';



T::dump( ArrayTools::grep(array('a', '1', 'c'), '#\d#') );
T::dump( ArrayTools::grep(array('a', '1', 'c'), '#\d#', PREG_GREP_INVERT) );



__halt_compiler();

------EXPECT------
array(
	1 => "1"
)

array(
	0 => "a"
	2 => "c"
)
