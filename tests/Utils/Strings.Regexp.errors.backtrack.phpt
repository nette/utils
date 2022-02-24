<?php

/**
 * Test: Nette\Utils\Strings and RegexpException run-time error.
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


ini_set('pcre.backtrack_limit', '3'); // forces PREG_BACKTRACK_LIMIT_ERROR
ini_set('pcre.jit', '0');

Assert::exception(
	fn() => Strings::split('0123456789', '#.*\d\d#'),
	Nette\Utils\RegexpException::class,
	'Backtrack limit exhausted (pattern: #.*\d\d#)',
);

Assert::exception(
	fn() => Strings::match('0123456789', '#.*\d\d#'),
	Nette\Utils\RegexpException::class,
	'Backtrack limit exhausted (pattern: #.*\d\d#)',
);

Assert::exception(
	fn() => Strings::matchAll('0123456789', '#.*\d\d#'),
	Nette\Utils\RegexpException::class,
	'Backtrack limit exhausted (pattern: #.*\d\d#)',
);

Assert::exception(
	fn() => Strings::replace('0123456789', '#.*\d\d#', 'x'),
	Nette\Utils\RegexpException::class,
	'Backtrack limit exhausted (pattern: #.*\d\d#)',
);


function cb()
{
	return 'x';
}


Assert::exception(
	fn() => Strings::replace('0123456789', '#.*\d\d#', Closure::fromCallable('cb')),
	Nette\Utils\RegexpException::class,
	'Backtrack limit exhausted (pattern: #.*\d\d#)',
);
