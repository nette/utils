<?php

/**
 * Test: Nette\Utils\Validators::assert()
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(
	fn() => Validators::assert(true, 'int'),
	Nette\Utils\AssertionException::class,
	'The variable expects to be int, bool given.',
);

Assert::exception(
	fn() => Validators::assert('', 'int'),
	Nette\Utils\AssertionException::class,
	"The variable expects to be int, string '' given.",
);

Assert::exception(
	fn() => Validators::assert(str_repeat('x', 1000), 'int'),
	Nette\Utils\AssertionException::class,
	'The variable expects to be int, string given.',
);

Assert::exception(
	fn() => Validators::assert('1.0', 'int|float'),
	Nette\Utils\AssertionException::class,
	"The variable expects to be int or float, string '1.0' given.",
);

Assert::exception(
	fn() => Validators::assert(null, 'int'),
	Nette\Utils\AssertionException::class,
	'The variable expects to be int, null given.',
);

Assert::exception(
	fn() => Validators::assert(1.0, 'int'),
	Nette\Utils\AssertionException::class,
	'The variable expects to be int, float 1.0 given.',
);

Assert::exception(
	fn() => Validators::assert(1, 'float'),
	Nette\Utils\AssertionException::class,
	'The variable expects to be float, int 1 given.',
);

Assert::exception(
	fn() => Validators::assert([], 'int'),
	Nette\Utils\AssertionException::class,
	'The variable expects to be int, array given.',
);

Assert::exception(
	fn() => Validators::assert(new stdClass, 'int'),
	Nette\Utils\AssertionException::class,
	'The variable expects to be int, object stdClass given.',
);

Assert::exception(
	fn() => Validators::assert(1, 'string|integer:2..5', 'variable'),
	Nette\Utils\AssertionException::class,
	'The variable expects to be string or integer in range 2..5, int 1 given.',
);

Assert::exception(
	fn() => Validators::assert('x', '?int'),
	Nette\Utils\AssertionException::class,
	"The variable expects to be ?int, string 'x' given.",
);
