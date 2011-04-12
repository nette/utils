<?php

/**
 * Test: Nette\String and RegexpException compile error.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../bootstrap.php';



try {
	String::split('0123456789', '#*#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'preg_split(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}

try {
	String::match('0123456789', '#*#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'preg_match(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}

try {
	String::matchAll('0123456789', '#*#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'preg_match_all(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}

try {
	String::replace('0123456789', '#*#', 'x');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'preg_replace(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}

function cb() { return 'x'; }

try {
	String::replace('0123456789', '#*#', callback('cb'));
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'preg_replace_callback(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}
