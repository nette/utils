<?php

/**
 * Test: Nette\Utils\Strings and RegexpException run-time error.
 */

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


ini_set('pcre.backtrack_limit', 3); // forces PREG_BACKTRACK_LIMIT_ERROR

Assert::exception(function () {
	Strings::split('0123456789', '#.*\d\d#');
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d\d#)');

Assert::exception(function () {
	Strings::match('0123456789', '#.*\d\d#');
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d\d#)');

Assert::exception(function () {
	Strings::matchAll('0123456789', '#.*\d\d#');
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d\d#)');

Assert::exception(function () {
	Strings::replace('0123456789', '#.*\d\d#', 'x');
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d\d#)');

function cb() {
	return 'x';
}

Assert::exception(function () {
	Strings::replace('0123456789', '#.*\d\d#', Nette\Utils\Callback::closure('cb'));
}, 'Nette\Utils\RegexpException', 'Backtrack limit was exhausted (pattern: #.*\d\d#)');
