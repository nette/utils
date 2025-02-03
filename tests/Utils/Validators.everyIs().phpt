<?php

/**
 * Test: Nette\Utils\Validators::everyIs()
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('ensures all elements conform to a type specification', function () {
	class Abc
	{
	}

	Assert::true(Validators::everyIs([], 'int'));
	Assert::true(Validators::everyIs(new ArrayIterator([]), 'int'));

	Assert::true(Validators::everyIs([1, 2, 3], 'int'));
	Assert::false(Validators::everyIs([1, 2.15, 3], 'int'));
	Assert::true(Validators::everyIs([1, 2.15, 3], 'int|float'));

	Assert::true(Validators::everyIs(new ArrayIterator([1, 2, 3]), 'int'));
	Assert::false(Validators::everyIs(new ArrayIterator([1, 2.15, 3]), 'int'));

	Assert::true(Validators::everyIs([new Abc], 'Abc'));
	Assert::false(Validators::everyIs([new Abc, new stdClass], 'Abc'));

	Assert::true(Validators::everyIs(['ABCD', 'EFGH', 'IJKL'], 'string:4'));
	Assert::false(Validators::everyIs(['ABCD', 'EFGH', 'IJKLM'], 'string:4'));

	Assert::false(Validators::everyIs([1, 2, 3], 'int[]'));
	Assert::true(Validators::everyIs([[1], [2, 3]], 'int[]'));
	Assert::true(Validators::everyIs([['ABCD', 'EFGH'], ['IJKL']], 'string:4[]'));
	Assert::false(Validators::everyIs([['ABCD', 'EFGH'], ['IJKLM']], 'string:4[]'));
});


test('validates generator yields elements of the specified type', function () {
	$gen = function () {
		yield 1;
		yield 2;
		yield 3;
	};
	Assert::true(Validators::everyIs($gen(), 'int'));
});


Assert::exception(function () {
	$var = new stdClass;
	$var->a = 1;
	Validators::everyIs($var, 'int');
}, TypeError::class);
