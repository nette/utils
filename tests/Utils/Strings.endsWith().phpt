<?php

/**
 * Test: Nette\Utils\Strings::endsWith()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(
	fn() => Strings::endsWith('123', null),
	TypeError::class,
);
Assert::true(Strings::endsWith('123', ''), "endsWith('123', '')");
Assert::true(Strings::endsWith('123', '3'), "endsWith('123', '3')");
Assert::false(Strings::endsWith('123', '2'), "endsWith('123', '2')");
Assert::true(Strings::endsWith('123', '123'), "endsWith('123', '123')");
Assert::false(Strings::endsWith('123', '1234'), "endsWith('123', '1234')");
