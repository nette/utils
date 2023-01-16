<?php

/**
 * Test: Nette\Utils\Type
 */

declare(strict_types=1);

use Nette\Utils\Type;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::true(Type::isValid('string'));
Assert::true(Type::isValid('?string'));
Assert::true(Type::isValid('string|null'));
Assert::true(Type::isValid('(A&B&C)'));
Assert::true(Type::isValid('A&B&C'));
Assert::true(Type::isValid('(A&C)|(C&D)|true'));

Assert::false(Type::isValid('?string|null'));
Assert::false(Type::isValid('?'));
Assert::false(Type::isValid(''));
Assert::false(Type::isValid('|foo'));
Assert::false(Type::isValid('(A|B)'));
Assert::false(Type::isValid('A&B|C'));
