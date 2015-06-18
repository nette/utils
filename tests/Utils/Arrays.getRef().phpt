<?php

/**
 * Test: Nette\Utils\Arrays::getRef()
 */

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	NULL => 'first',
	1 => 'second',
	7 => [
		'item' => 'third',
	],
];

test(function () use ($arr) { // Single item

	$dolly = $arr;
	$item = & Arrays::getRef($dolly, NULL);
	$item = 'changed';
	Assert::same([
		'' => 'changed',
		1 => 'second',
		7 => [
			'item' => 'third',
		],
	], $dolly);


	$dolly = $arr;
	$item = & Arrays::getRef($dolly, 'undefined');
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


test(function () use ($arr) { // Traversing

	$dolly = $arr;
	$item = & Arrays::getRef($dolly, []);
	$item = 'changed';
	Assert::same('changed', $dolly);


	$dolly = $arr;
	$item = & Arrays::getRef($dolly, [7, 'item']);
	$item = 'changed';
	Assert::same([
		'' => 'first',
		1 => 'second',
		7 => [
			'item' => 'changed',
		],
	], $dolly);
});


test(function () use ($arr) { // Error

	Assert::exception(function () use ($arr) {
		$dolly = $arr;
		$item = & Arrays::getRef($dolly, [7, 'item', 3]);
	}, 'InvalidArgumentException', 'Traversed item is not an array.');
});
