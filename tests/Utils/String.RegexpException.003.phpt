<?php

/**
 * Test: Nette\String and RegexpException compile error.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



try {
	String::split('0123456789', '#*#');
} catch (Exception $e) {
	T::dump( $e );
}

try {
	String::match('0123456789', '#*#');
} catch (Exception $e) {
	T::dump( $e );
}

try {
	String::matchAll('0123456789', '#*#');
} catch (Exception $e) {
	T::dump( $e );
}

try {
	String::replace('0123456789', '#*#', 'x');
} catch (Exception $e) {
	T::dump( $e );
}



__halt_compiler();

------EXPECT------

Warning: preg_split(): Compilation failed: nothing to repeat at offset 0 in %a% on line %d%
Exception %ns%RegexpException: preg_split(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#


Warning: preg_match(): Compilation failed: nothing to repeat at offset 0 in %a% on line %d%
Exception %ns%RegexpException: preg_match(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#


Warning: preg_match_all(): Compilation failed: nothing to repeat at offset 0 in %a% on line %d%
Exception %ns%RegexpException: preg_match_all(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#


Warning: preg_replace(): Compilation failed: nothing to repeat at offset 0 in %a% on line %d%
Exception %ns%RegexpException: preg_replace(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#
