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

Assert::throws(function() {
	Strings::split('0123456789', '#.*\d#');
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)');

Assert::throws(function() {
	Strings::match('0123456789', '#.*\d#');
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)');

Assert::throws(function() {
	Strings::matchAll('0123456789', '#.*\d#');
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)');

Assert::throws(function() {
	Strings::replace('0123456789', '#.*\d#', 'x');
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)');

function cb() { return 'x'; }

Assert::throws(function() {
	Strings::replace('0123456789', '#.*\d#', callback('cb'));
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d#)');
