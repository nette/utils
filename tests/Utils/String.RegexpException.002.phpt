<?php

/**
 * Test: Nette\String and RegexpException run-time error.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



try {
	String::split("0123456789\xFF", '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

try {
	String::match("0123456789\xFF", '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

try {
	String::matchAll("0123456789\xFF", '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}

try {
	String::replace("0123456789\xFF", '#\d#u', 'x');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}
