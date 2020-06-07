<?php

/**
 * Test: Nette\Utils\Validators::isEmail()
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Validators::isEmail('admin@nette.org'));
Assert::true(Validators::isEmail('admin@localhost'));

Assert::false(Validators::isEmail('abcd'));
