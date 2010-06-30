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
array(
	"a"
	","
	"b"
	","
	"c"
)

array(
	"a"
	","
	"b"
	","
	"c"
)
