<?php

/**
 * Test: Nette\StringUtils and RegexpException run-time error.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



try {
	StringUtils::split("0123456789\xFF", '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

try {
	StringUtils::match("0123456789\xFF", '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

try {
	StringUtils::matchAll("0123456789\xFF", '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

try {
	StringUtils::replace("0123456789\xFF", '#\d#u', 'x');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

function cb() { return 'x'; }

try {
	StringUtils::replace("0123456789\xFF", '#\d#u', callback('cb'));
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}
