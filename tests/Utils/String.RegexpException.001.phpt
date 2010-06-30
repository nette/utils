<?php

/**
 * Test: Nette\String and RegexpException run-time error.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



ini_set('pcre.backtrack_limit', 3); // forces PREG_BACKTRACK_LIMIT_ERROR

try {
	String::split('0123456789', '#.*\d#');
} catch (Exception $e) {
	T::dump( $e );
}

try {
	String::match('0123456789', '#.*\d#');
} catch (Exception $e) {
	T::dump( $e );
}

try {
	String::matchAll('0123456789', '#.*\d#');
} catch (Exception $e) {
	T::dump( $e );
}

try {
	String::replace('0123456789', '#.*\d#', 'x');
} catch (Exception $e) {
	T::dump( $e );
}



__halt_compiler();

------EXPECT------
Exception %ns%RegexpException: #2 Backtrack limit was exhausted (pattern: #.*\d#)

Exception %ns%RegexpException: #2 Backtrack limit was exhausted (pattern: #.*\d#)

Exception %ns%RegexpException: #2 Backtrack limit was exhausted (pattern: #.*\d#)

Exception %ns%RegexpException: #2 Backtrack limit was exhausted (pattern: #.*\d#)
