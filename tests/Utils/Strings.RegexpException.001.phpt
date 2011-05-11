<?php

/**
 * Test: Nette\Utils\Strings and RegexpException run-time error.
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @subpackage UnitTests
 */

use Nette\Utils\Strings;



require __DIR__ . '/../bootstrap.php';



ini_set('pcre.backtrack_limit', 3); // forces PREG_BACKTRACK_LIMIT_ERROR

try {
	Strings::split('0123456789', '#.*\d#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception( 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)', $e );
}

try {
	Strings::match('0123456789', '#.*\d#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception( 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)', $e );
}

try {
	Strings::matchAll('0123456789', '#.*\d#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception( 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)', $e );
}

try {
	Strings::replace('0123456789', '#.*\d#', 'x');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception( 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)', $e );
}

function cb() { return 'x'; }

try {
	Strings::replace('0123456789', '#.*\d#', callback('cb'));
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception( 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)', $e );
}
