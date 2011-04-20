<?php

/**
 * Test: Nette\Utils\Strings and RegexpException run-time error.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Utils\Strings;



require __DIR__ . '/../bootstrap.php';



try {
	Strings::split("0123456789\xFF", '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

try {
	Strings::match("0123456789\xFF", '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

try {
	Strings::matchAll("0123456789\xFF", '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

try {
	Strings::replace("0123456789\xFF", '#\d#u', 'x');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

function cb() { return 'x'; }

try {
	Strings::replace("0123456789\xFF", '#\d#u', callback('cb'));
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}
