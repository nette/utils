<?php

/**
 * Test: Nette\Utils\Validators::assert()
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function () {
	Validators::assert(TRUE, 'int');
}, Nette\Utils\AssertionException::class, 'The variable expects to be int, boolean given.');

Assert::exception(function () {
	Validators::assert('1.0', 'int|float');
}, Nette\Utils\AssertionException::class, "The variable expects to be int or float, string '1.0' given.");

Assert::exception(function () {
	Validators::assert(1, 'string|integer:2..5', 'variable');
}, Nette\Utils\AssertionException::class, 'The variable expects to be string or integer in range 2..5, integer given.');
