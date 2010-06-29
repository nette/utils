<?php

/**
 * Test: Nette\String::split()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



T::dump( String::split('a, b, c', '#(,)\s*#') );
T::dump( String::split('a, b, c', '#(,)\s*#', PREG_SPLIT_NO_EMPTY) );



__halt_compiler();

------EXPECT------
array(5) {
	0 => string(1) "a"
	1 => string(1) ","
	2 => string(1) "b"
	3 => string(1) ","
	4 => string(1) "c"
}

array(5) {
	0 => string(1) "a"
	1 => string(1) ","
	2 => string(1) "b"
	3 => string(1) ","
	4 => string(1) "c"
}
