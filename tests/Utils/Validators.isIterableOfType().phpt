<?php

/**
 * Test: Nette\Utils\Validators::isIterableOfType()
 */

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
	Assert::true(Validators::isIterableOfType([1, 2, 3], 'int'));
	Assert::true(Validators::isIterableOfType(new ArrayIterator([1, 2, 3]), 'int'));

	$gen = function () {
		yield 1;
		yield 2;
		yield 3;
	};
	Assert::true(Validators::isIterableOfType($gen(), 'int'));
	Assert::false(Validators::isIterableOfType(1, 'int'));
	Assert::false(Validators::isIterableOfType(2.15, 'int'));

	$var = new stdClass;
	$var->a = 1;
	$var->b = 2;
	$var->c = 3;
	Assert::false(Validators::isIterableOfType($var, 'int'));
});


test(function () {
	Assert::true(Validators::isIterableOfType([1, 2, 3], 'int'));
	Assert::false(Validators::isIterableOfType([1, 2.15, 3], 'int'));
	Assert::true(Validators::isIterableOfType([1, 2.15, 3], 'int|float'));


	Assert::true(Validators::isIterableOfType(['ABCD', 'EFGH', 'IJKL'], 'string:4'));
	Assert::false(Validators::isIterableOfType(['ABCD', 'EFGH', 'IJKLM'], 'string:4'));
});


test(function () {
	Assert::true(Validators::isIterableOfType([], 'int'));
	Assert::true(Validators::isIterableOfType(new ArrayIterator([]), 'int'));
});

test(function () {
	class A {}

	Assert::true(Validators::isIterableOfType([new A], A::class));
	Assert::false(Validators::isIterableOfType([new A, new stdClass], A::class));
});
