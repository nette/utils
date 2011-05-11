<?php

/**
 * Test: Nette\Utils\Strings and RegexpException compile error.
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @subpackage UnitTests
 */

use Nette\Utils\Strings;



require __DIR__ . '/../bootstrap.php';



try {
	Strings::split('0123456789', '#*#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'preg_split(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}

try {
	Strings::match('0123456789', '#*#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'preg_match(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}

try {
	Strings::matchAll('0123456789', '#*#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'preg_match_all(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}

try {
	Strings::replace('0123456789', '#*#', 'x');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'preg_replace(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}

function cb() { return 'x'; }

try {
	Strings::replace('0123456789', '#*#', callback('cb'));
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'preg_replace_callback(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}
