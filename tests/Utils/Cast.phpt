<?php

declare(strict_types=1);

use Nette\Utils\Cast;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// bool
Assert::true(Cast::bool(true));
Assert::true(Cast::bool(1));
Assert::true(Cast::bool(2));
Assert::true(Cast::bool(0.1));
Assert::true(Cast::bool('1'));
Assert::true(Cast::bool('0.0'));
Assert::false(Cast::bool(false));
Assert::false(Cast::bool(0));
Assert::false(Cast::bool(0.0));
Assert::false(Cast::bool(''));
Assert::false(Cast::bool('0'));
Assert::exception(
	fn() => Cast::bool([]),
	TypeError::class,
	'Cannot cast array to bool.',
);
Assert::exception(
	fn() => Cast::bool(null),
	TypeError::class,
	'Cannot cast null to bool.',
);


// int
Assert::same(0, Cast::int(false));
Assert::same(1, Cast::int(true));
Assert::same(0, Cast::int(0));
Assert::same(1, Cast::int(1));
Assert::exception(
	fn() => Cast::int(PHP_INT_MAX + 1),
	TypeError::class,
	'Cannot cast 9.2233720368548E+18 to int.',
);
Assert::same(0, Cast::int(0.0));
Assert::same(1, Cast::int(1.0));
Assert::exception(
	fn() => Cast::int(0.1),
	TypeError::class,
	'Cannot cast 0.1 to int.',
);
Assert::exception(
	fn() => Cast::int(''),
	TypeError::class,
	"Cannot cast '' to int.",
);
Assert::same(0, Cast::int('0'));
Assert::same(1, Cast::int('1'));
Assert::same(-1, Cast::int('-1.'));
Assert::same(1, Cast::int('1.0000'));
Assert::exception(
	fn() => Cast::int('0.1'),
	TypeError::class,
	"Cannot cast '0.1' to int.",
);
Assert::exception(
	fn() => Cast::int([]),
	TypeError::class,
	'Cannot cast array to int.',
);
Assert::exception(
	fn() => Cast::int(null),
	TypeError::class,
	'Cannot cast null to int.',
);


// float
Assert::same(0.0, Cast::float(false));
Assert::same(1.0, Cast::float(true));
Assert::same(0.0, Cast::float(0));
Assert::same(1.0, Cast::float(1));
Assert::same(0.0, Cast::float(0.0));
Assert::same(1.0, Cast::float(1.0));
Assert::same(0.1, Cast::float(0.1));
Assert::exception(
	fn() => Cast::float(''),
	TypeError::class,
	"Cannot cast '' to float.",
);
Assert::same(0.0, Cast::float('0'));
Assert::same(1.0, Cast::float('1'));
Assert::same(-1.0, Cast::float('-1.'));
Assert::same(1.0, Cast::float('1.0'));
Assert::same(0.1, Cast::float('0.1'));
Assert::exception(
	fn() => Cast::float([]),
	TypeError::class,
	'Cannot cast array to float.',
);
Assert::exception(
	fn() => Cast::float(null),
	TypeError::class,
	'Cannot cast null to float.',
);


// string
Assert::same('0', Cast::string(false)); // differs from PHP strict casting
Assert::same('1', Cast::string(true));
Assert::same('0', Cast::string(0));
Assert::same('1', Cast::string(1));
Assert::same('0.0', Cast::string(0.0)); // differs from PHP strict casting
Assert::same('1.0', Cast::string(1.0)); // differs from PHP strict casting
Assert::same('-0.1', Cast::string(-0.1));
Assert::same('9.2233720368548E+18', Cast::string(PHP_INT_MAX + 1));
Assert::same('', Cast::string(''));
Assert::same('x', Cast::string('x'));
Assert::exception(
	fn() => Cast::string([]),
	TypeError::class,
	'Cannot cast array to string.',
);
Assert::exception(
	fn() => Cast::string(null),
	TypeError::class,
	'Cannot cast null to string.',
);


// OrNull
Assert::true(Cast::boolOrNull(true));
Assert::null(Cast::boolOrNull(null));
Assert::same(0, Cast::intOrNull(0));
Assert::null(Cast::intOrNull(null));
Assert::same(0.0, Cast::floatOrNull(0));
Assert::null(Cast::floatOrNull(null));
Assert::same('0', Cast::stringOrNull(0));
Assert::null(Cast::stringOrNull(null));
