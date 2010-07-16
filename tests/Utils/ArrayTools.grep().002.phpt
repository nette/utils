<?php

/**
 * Test: Nette\ArrayTools::grep() errors.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayTools;



require __DIR__ . '/../initialize.php';



try {
	ArrayTools::grep(array('a', '1', 'c'), '#*#');
} catch (Exception $e) {
	T::dump( $e );
}


try {
	ArrayTools::grep(array('a', "1\xFF", 'c'), '#\d#u');
} catch (Exception $e) {
	T::dump( $e );
}




__halt_compiler();

------EXPECT------
Exception %ns%RegexpException: preg_grep(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#

Exception %ns%RegexpException: #4 Malformed UTF-8 data (pattern: #\d#u)
