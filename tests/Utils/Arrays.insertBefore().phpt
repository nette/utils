<?php

/**
 * Test: Nette\Utils\Arrays::insertBefore() & insertAfter()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	null => 'first',
	false => 'second',
	1 => 'third',
	7 => 'fourth',
];

Assert::same([
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
], $arr);


test(function () use ($arr) { // First item
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
		'new' => 'value',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
	], $dolly);
});


test(function () use ($arr) { // Last item
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


test(function () use ($arr) { // Undefined item
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
