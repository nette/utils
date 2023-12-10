<?php

/**
 * Test: Nette\Iterators\MemoizingIterator
 */

declare(strict_types=1);

use Nette\Iterators\MemoizingIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function iterator(): Generator
{
	yield 'a' => 'apple';
	yield ['b'] => ['banana'];
	yield 'c' => 'cherry';
}


test('iteration', function () {
	$iterator = new MemoizingIterator(iterator());

	$pairs = [];
	foreach ($iterator as $key => $value) {
		$pairs[] = [$key, $value];
	}
	Assert::same(
		[
			['a', 'apple'],
			[['b'], ['banana']],
			['c', 'cherry'],
		],
		$pairs,
	);
});


test('re-iteration', function () {
	$iterator = new MemoizingIterator(iterator());

	foreach ($iterator as $value);

	$pairs = [];
	foreach ($iterator as $key => $value) {
		$pairs[] = [$key, $value];
	}
	Assert::same(
		[
			['a', 'apple'],
			[['b'], ['banana']],
			['c', 'cherry'],
		],
		$pairs,
	);
});


test('nested re-iteration', function () { // nefunguje
	$iterator = new MemoizingIterator(iterator());

	$pairs = [];
	foreach ($iterator as $key => $value) {
		$pairs[] = [$key, $value];
		foreach ($iterator as $value);
	}
	Assert::same(
		[
			['a', 'apple'],
			[['b'], ['banana']],
			['c', 'cherry'],
		],
		$pairs,
	);
});
