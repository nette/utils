<?php

/**
 * Test: Nette\Utils\Strings and RegexpException run-time error.
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function () {
	Strings::split("0123456789\xFF", '#\d#u');
}, Nette\Utils\RegexpException::class, 'Malformed UTF-8 data (pattern: #\d#u)');

Assert::exception(function () {
	Strings::match("0123456789\xFF", '#\d#u');
}, Nette\Utils\RegexpException::class, 'Malformed UTF-8 data (pattern: #\d#u)');

Assert::exception(function () {
	Strings::matchAll("0123456789\xFF", '#\d#u');
}, Nette\Utils\RegexpException::class, 'Malformed UTF-8 data (pattern: #\d#u)');

Assert::exception(function () {
	Strings::replace("0123456789\xFF", '#\d#u', 'x');
}, Nette\Utils\RegexpException::class, 'Malformed UTF-8 data (pattern: #\d#u)');

function cb() {
	return 'x';
}

Assert::exception(function () {
	Strings::replace("0123456789\xFF", '#\d#u', Nette\Utils\Callback::closure('cb'));
}, Nette\Utils\RegexpException::class, 'Malformed UTF-8 data (pattern: #\d#u)');
