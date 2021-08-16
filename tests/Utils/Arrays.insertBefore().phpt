<?php

/**
 * Test: Nette\Utils\Arrays::insertBefore() & insertAfter()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
];


test('First item', function () use ($arr) {
	$dolly = $arr;
	Arrays::insertBefore($dolly, null, ['new' => 'value']);
	Assert::same([
		'new' => 'value',
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
	], $dolly);


	$dolly = $arr;
	Arrays::insertAfter($dolly, null, ['new' => 'value']);
	Assert::same([
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
		'new' => 'value',
	], $dolly);
});


test('Last item', function () use ($arr) {
	$dolly = $arr;
	Arrays::insertBefore($dolly, 7, ['new' => 'value']);
	Assert::same([
		'' => 'first',
		0 => 'second',
		1 => 'third',
		'new' => 'value',
		7 => 'fourth',
	], $dolly);


	$dolly = $arr;
	Arrays::insertAfter($dolly, 7, ['new' => 'value']);
	Assert::same([
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
		'new' => 'value',
	], $dolly);
});


test('Undefined item', function () use ($arr) {
	$dolly = $arr;
	Arrays::insertBefore($dolly, 'undefined', ['new' => 'value']);
	Assert::same([
		'new' => 'value',
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
	], $dolly);


	$dolly = $arr;
	Arrays::insertAfter($dolly, 'undefined', ['new' => 'value']);
	Assert::same([
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
		'new' => 'value',
	], $dolly);
});
