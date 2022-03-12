<?php

/**
 * Test: Nette\Utils\Strings and RegexpException run-time error.
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(
	fn() => Strings::split("0123456789\xFF", '#\d#u'),
	Nette\Utils\RegexpException::class,
	'Malformed UTF-8 data (pattern: #\d#u)',
);

Assert::exception(
	fn() => Strings::match("0123456789\xFF", '#\d#u'),
	Nette\Utils\RegexpException::class,
	'Malformed UTF-8 data (pattern: #\d#u)',
);

Assert::exception(
	fn() => Strings::matchAll("0123456789\xFF", '#\d#u'),
	Nette\Utils\RegexpException::class,
	'Malformed UTF-8 data (pattern: #\d#u)',
);

Assert::exception(
	fn() => Strings::replace("0123456789\xFF", '#\d#u', 'x'),
	Nette\Utils\RegexpException::class,
	'Malformed UTF-8 data (pattern: #\d#u)',
);


function cb()
{
	return 'x';
}


Assert::exception(
	fn() => Strings::replace("0123456789\xFF", '#\d#u', Closure::fromCallable('cb')),
	Nette\Utils\RegexpException::class,
	'Malformed UTF-8 data (pattern: #\d#u)',
);
