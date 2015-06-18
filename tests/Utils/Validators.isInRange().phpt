<?php

/**
 * Test: Nette\Utils\Validators::isInRange()
 */

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
Assert::true(Validators::isInRange(1, [-1, '']));
