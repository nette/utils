<?php

/**
 * Test: Nette\Utils\Validators::isListOfType()
 */

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
	Assert::true(Validators::isListOfType([1, 2, 3], 'int'));
	Assert::true(Validators::isListOfType(new ArrayIterator([1, 2, 3]), 'int'));

	$gen = function () {
		yield 1;
		yield 2;
		yield 3;
	};
	Assert::true(Validators::isListOfType($gen(), 'int'));
	Assert::false(Validators::isListOfType(1, 'int'));
	Assert::false(Validators::isListOfType(2.15, 'int'));

	$var = new stdClass;
	$var->a = 1;
	$var->b = 2;
	$var->c = 3;
	Assert::false(Validators::isListOfType($var, 'int'));
});


test(function () {
	Assert::true(Validators::isListOfType([1, 2, 3], 'int'));
	Assert::false(Validators::isListOfType([1, 2.15, 3], 'int'));
	Assert::true(Validators::isListOfType([1, 2.15, 3], 'int|float'));


	Assert::true(Validators::isListOfType(['ABCD', 'EFGH', 'IJKL'], 'string:4'));
	Assert::false(Validators::isListOfType(['ABCD', 'EFGH', 'IJKLM'], 'string:4'));
});


test(function () {
	Assert::true(Validators::isListOfType([], 'int'));
	Assert::true(Validators::isListOfType(new ArrayIterator([]), 'int'));
});