<?php

/**
 * Test: Nette\Utils\Validators::assertField()
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = ['first' => true];

Assert::exception(function () use ($arr) {
	Validators::assertField(null, 'foo', 'foo');
}, TypeError::class);

Assert::exception(function () use ($arr) {
	Validators::assertField($arr, 'second', 'int');
}, Nette\Utils\AssertionException::class, "Missing item 'second' in array.");

Validators::assertField($arr, 'first');

Assert::exception(function () use ($arr) {
	Validators::assertField($arr, 'first', 'int');
}, Nette\Utils\AssertionException::class, "The item 'first' in array expects to be int, bool given.");
