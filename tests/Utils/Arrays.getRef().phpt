<?php

/**
 * Test: Nette\Utils\Arrays::getRef()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	'' => 'first',
	1 => 'second',
	7 => [
		'item' => 'third',
	],
];

test('reference update and auto-add missing key', function () use ($arr) {
	$dolly = $arr;
	$item = &Arrays::getRef($dolly, '');
	$item = 'changed';
	Assert::same([
		'' => 'changed',
		1 => 'second',
		7 => [
			'item' => 'third',
		],
	], $dolly);


	$dolly = $arr;
	$item = &Arrays::getRef($dolly, 'undefined');
	$item = 'changed';
	Assert::same([
		'' => 'first',
		1 => 'second',
		7 => [
			'item' => 'third',
		],
		'undefined' => 'changed',
	], $dolly);
});


test('nested reference assignment and full array override', function () use ($arr) {
	$dolly = $arr;
	$item = &Arrays::getRef($dolly, []);
	$item = 'changed';
	Assert::same('changed', $dolly);


	$dolly = $arr;
	$item = &Arrays::getRef($dolly, [7, 'item']);
	$item = 'changed';
	Assert::same([
		'' => 'first',
		1 => 'second',
		7 => [
			'item' => 'changed',
		],
	], $dolly);
});


test('exception on invalid nested reference', function () use ($arr) {
	$dolly = $arr;
	Assert::exception(
		fn() => $item = &Arrays::getRef($dolly, [7, 'item', 3]),
		InvalidArgumentException::class,
		'Traversed item is not an array.',
	);
});
