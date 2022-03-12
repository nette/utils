<?php

/**
 * Test: Nette\Utils\Strings and error in callback.
 */

declare(strict_types=1);

use Nette\InvalidStateException;
use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::error(fn() => Strings::replace('hello', '#.+#', function ($m) {
	$a++; // E_NOTICE
	return strtoupper($m[0]);
}), ...(PHP_VERSION_ID < 80000 ? [E_NOTICE, 'Undefined variable: a'] : [E_WARNING, 'Undefined variable $a']));


Assert::same('HELLO', Strings::replace('hello', '#.+#', function ($m) {
	preg_match('#\d#u', "0123456789\xFF"); // Malformed UTF-8 data
	return strtoupper($m[0]);
}));


Assert::exception(
	fn() => Strings::replace('hello', '#.+#', [stdClass::class, 'foobar']),
	InvalidStateException::class,
	"Callback 'stdClass::foobar' is not callable.",
);
