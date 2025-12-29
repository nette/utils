<?php

/**
 * Test: Nette\Utils\Arrays::normalize()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('normalizes numeric keys to their string values with null filling', function () {
	Assert::same(
		[
			'first' => null,
			'a' => 'second',
			'd' => ['third'],
			'fourth' => null,
		],
		Arrays::normalize([
			1 => 'first',
			'a' => 'second',
			'd' => ['third'],
			7 => 'fourth',
		]),
	);
});


test('uses custom filling value for normalized keys', function () {
	Assert::same(
		[
			'first' => true,
			'' => 'second',
		],
		Arrays::normalize([
			1 => 'first',
			'' => 'second',
		], filling: true),
	);
});


test('handles empty array', function () {
	Assert::same([], Arrays::normalize([]));
});


test('keeps associative array unchanged', function () {
	Assert::same(
		['a' => 'x', 'b' => 'y'],
		Arrays::normalize(['a' => 'x', 'b' => 'y']),
	);
});


test('handles mixed numeric and string keys', function () {
	Assert::same(
		['a' => 'x', 'b' => null, 'c' => 'z'],
		Arrays::normalize(['a' => 'x', 0 => 'b', 'c' => 'z']),
	);
});
