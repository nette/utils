<?php

/**
 * Test: Nette\Utils\Strings::startsWith()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(
	fn() => Strings::startsWith('123', null),
	TypeError::class,
);
Assert::true(Strings::startsWith('123', ''), "startsWith('123', '')");
Assert::true(Strings::startsWith('123', '1'), "startsWith('123', '1')");
Assert::false(Strings::startsWith('123', '2'), "startsWith('123', '2')");
Assert::true(Strings::startsWith('123', '123'), "startsWith('123', '123')");
Assert::false(Strings::startsWith('123', '1234'), "startsWith('123', '1234')");
