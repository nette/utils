<?php

/**
 * Test: Nette\Utils\Strings and RegexpException compile error.
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(
	fn() => Strings::split('0123456789', '#*#'),
	Nette\Utils\RegexpException::class,
	'Compilation failed: %a% in pattern: #*#',
);

Assert::exception(
	fn() => Strings::match('0123456789', '#*#'),
	Nette\Utils\RegexpException::class,
	'Compilation failed: %a% in pattern: #*#',
);

Assert::exception(
	fn() => Strings::matchAll('0123456789', '#*#'),
	Nette\Utils\RegexpException::class,
	'Compilation failed: %a% in pattern: #*#',
);

Assert::exception(
	fn() => Strings::replace('0123456789', '#*#', 'x'),
	Nette\Utils\RegexpException::class,
	'Compilation failed: %a% in pattern: #*#',
);

Assert::exception(
	fn() => Strings::replace('0123456789', ['##', '#*#'], 'x'),
	Nette\Utils\RegexpException::class,
	'Compilation failed: %a% in pattern: ## or #*#',
);


function cb()
{
	return 'x';
}


Assert::exception(
	fn() => Strings::replace('0123456789', '#*#', Closure::fromCallable('cb')),
	Nette\Utils\RegexpException::class,
	'Compilation failed: %a% in pattern: #*#',
);

Assert::exception(
	fn() => Strings::replace('0123456789', ['##', '#*#'], Closure::fromCallable('cb')),
	Nette\Utils\RegexpException::class,
	'Compilation failed: %a% in pattern: ## or #*#',
);
