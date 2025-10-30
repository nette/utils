<?php

/**
 * Test: Nette\Utils\Strings and error in callback.
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::error(fn() => Strings::replace('hello', '#.+#', function ($m) {
	$a++; // E_NOTICE
	return strtoupper($m[0]);
}), E_WARNING, 'Undefined variable $a');


Assert::same('HELLO', Strings::replace('hello', '#.+#', function ($m) {
	preg_match('#\d#u', "0123456789\xFF"); // Malformed UTF-8 data
	return strtoupper($m[0]);
}));
