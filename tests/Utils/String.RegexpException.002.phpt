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



try {
	String::split("0123456789\xFF", '#\d#u');
} catch (Exception $e) {
	T::dump( $e );
}

try {
	String::match("0123456789\xFF", '#\d#u');
} catch (Exception $e) {
	T::dump( $e );
}

try {
	String::matchAll("0123456789\xFF", '#\d#u');
} catch (Exception $e) {
	T::dump( $e );
}

try {
	String::replace("0123456789\xFF", '#\d#u', 'x');
} catch (Exception $e) {
	T::dump( $e );
}



__halt_compiler();

------EXPECT------
Exception Nette\RegexpException: #4 Malformed UTF-8 data (pattern: #\d#u)

Exception Nette\RegexpException: #4 Malformed UTF-8 data (pattern: #\d#u)

Exception Nette\RegexpException: #4 Malformed UTF-8 data (pattern: #\d#u)

Exception Nette\RegexpException: #4 Malformed UTF-8 data (pattern: #\d#u)
