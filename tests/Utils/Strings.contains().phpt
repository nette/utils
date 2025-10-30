<?php

/**
 * Test: Nette\Utils\Strings::contains()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Strings::contains('foo', 'f'));
Assert::true(Strings::contains('foo', 'fo'));
Assert::true(Strings::contains('foo', 'foo'));
Assert::true(Strings::contains('123', '123'));
Assert::true(Strings::contains('123', '1'));
Assert::false(Strings::contains('', 'foo'));
Assert::true(Strings::contains('', ''));
