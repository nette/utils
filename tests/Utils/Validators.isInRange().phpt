<?php

/**
 * Test: Nette\Utils\Validators::isInRange()
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Validators::isInRange(1, [0, 2]));
Assert::false(Validators::isInRange(-1, [0, 2]));
Assert::true(Validators::isInRange(-1, [-1, 1]));
Assert::true(Validators::isInRange(1, [-1, 1]));
Assert::true(Validators::isInRange(0.1, [-0.5, 0.5]));
Assert::false(Validators::isInRange(2, [-1, 1]));
Assert::false(Validators::isInRange(2.5, [-1, 1]));

Assert::true(Validators::isInRange('a', ['a', 'z']));
Assert::false(Validators::isInRange('A', ['a', 'z']));

Assert::true(Validators::isInRange(-1, [NULL, 2]));
Assert::true(Validators::isInRange(-1, ['', 2]));

Assert::true(Validators::isInRange(1, [-1, NULL]));
Assert::false(Validators::isInRange(1, [-1, '']));

Assert::false(Validators::isInRange(NULL, [0, 1]));
Assert::false(Validators::isInRange(NULL, ['0', 'b']));

Assert::true(Validators::isInRange('', ['', '']));
Assert::true(Validators::isInRange('', ['', 'b']));
Assert::false(Validators::isInRange('', ['a', 'b']));

Assert::false(Validators::isInRange('', [0, 1]));
Assert::false(Validators::isInRange('', [0, 1]));
Assert::false(Validators::isInRange('a', [1, NULL]));
Assert::false(Validators::isInRange('a', [NULL, 9]));
Assert::true(Validators::isInRange('1', [NULL, 9]));
Assert::false(Validators::isInRange(10, ['a', NULL]));
Assert::true(Validators::isInRange(10, [NULL, 'a']));
