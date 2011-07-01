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



Assert::throws(function() {
	Strings::split("0123456789\xFF", '#\d#u');
}, 'Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)');

Assert::throws(function() {
	Strings::match("0123456789\xFF", '#\d#u');
}, 'Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)');

Assert::throws(function() {
	Strings::matchAll("0123456789\xFF", '#\d#u');
}, 'Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)');

Assert::throws(function() {
	Strings::replace("0123456789\xFF", '#\d#u', 'x');
}, 'Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)');

function cb() { return 'x'; }

Assert::throws(function() {
	Strings::replace("0123456789\xFF", '#\d#u', callback('cb'));
}, 'Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)');
