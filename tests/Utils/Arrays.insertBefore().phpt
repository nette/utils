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


test('insertBefore/After with null key - beginning/end', function () use ($arr) {
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


test('insertBefore/After last item', function () use ($arr) {
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


test('insertBefore/After undefined key', function () use ($arr) {
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


test('insertBefore/After middle item', function () use ($arr) {
	$dolly = $arr;
	Arrays::insertBefore($dolly, 1, ['new' => 'value']);
	Assert::same([
		'' => 'first',
		0 => 'second',
		'new' => 'value',
		1 => 'third',
		7 => 'fourth',
	], $dolly);

	$dolly = $arr;
	Arrays::insertAfter($dolly, 0, ['new' => 'value']);
	Assert::same([
		'' => 'first',
		0 => 'second',
		'new' => 'value',
		1 => 'third',
		7 => 'fourth',
	], $dolly);
});


test('insertBefore/After with empty array', function () {
	$arr = [];
	Arrays::insertBefore($arr, null, ['new' => 'value']);
	Assert::same(['new' => 'value'], $arr);

	$arr = [];
	Arrays::insertAfter($arr, null, ['new' => 'value']);
	Assert::same(['new' => 'value'], $arr);
});


test('insertBefore/After with empty insertion', function () use ($arr) {
	$dolly = $arr;
	Arrays::insertBefore($dolly, 1, []);
	Assert::same($arr, $dolly);

	$dolly = $arr;
	Arrays::insertAfter($dolly, 1, []);
	Assert::same($arr, $dolly);
});


test('insertBefore/After multiple items', function () use ($arr) {
	$dolly = $arr;
	Arrays::insertBefore($dolly, 1, ['new1' => 'value1', 'new2' => 'value2', 'new3' => 'value3']);
	Assert::same([
		'' => 'first',
		0 => 'second',
		'new1' => 'value1',
		'new2' => 'value2',
		'new3' => 'value3',
		1 => 'third',
		7 => 'fourth',
	], $dolly);

	$dolly = $arr;
	Arrays::insertAfter($dolly, 0, ['new1' => 'value1', 'new2' => 'value2']);
	Assert::same([
		'' => 'first',
		0 => 'second',
		'new1' => 'value1',
		'new2' => 'value2',
		1 => 'third',
		7 => 'fourth',
	], $dolly);
});


test('insertBefore/After with numeric array', function () {
	$arr = ['a', 'b', 'c', 'd'];

	$dolly = $arr;
	Arrays::insertBefore($dolly, 2, [99 => 'x']);
	Assert::same([0 => 'a', 1 => 'b', 99 => 'x', 2 => 'c', 3 => 'd'], $dolly);

	$dolly = $arr;
	Arrays::insertAfter($dolly, 1, [99 => 'x']);
	Assert::same([0 => 'a', 1 => 'b', 99 => 'x', 2 => 'c', 3 => 'd'], $dolly);
});


test('insertBefore/After preserves key types', function () {
	$arr = ['str' => 'string', 10 => 'int', '' => 'empty'];

	$dolly = $arr;
	Arrays::insertBefore($dolly, 10, ['new' => 'value']);
	Assert::same(['str' => 'string', 'new' => 'value', 10 => 'int', '' => 'empty'], $dolly);

	$dolly = $arr;
	Arrays::insertAfter($dolly, 'str', [20 => 'numeric']);
	Assert::same(['str' => 'string', 20 => 'numeric', 10 => 'int', '' => 'empty'], $dolly);
});


test('insertBefore/After first item by key', function () use ($arr) {
	$dolly = $arr;
	Arrays::insertBefore($dolly, '', ['new' => 'value']);
	Assert::same([
		'new' => 'value',
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
	], $dolly);

	$dolly = $arr;
	Arrays::insertAfter($dolly, '', ['new' => 'value']);
	Assert::same([
		'' => 'first',
		'new' => 'value',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
	], $dolly);
});


test('insertBefore/After with single element array', function () {
	$arr = ['only' => 'one'];

	$dolly = $arr;
	Arrays::insertBefore($dolly, 'only', ['new' => 'value']);
	Assert::same(['new' => 'value', 'only' => 'one'], $dolly);

	$dolly = $arr;
	Arrays::insertAfter($dolly, 'only', ['new' => 'value']);
	Assert::same(['only' => 'one', 'new' => 'value'], $dolly);
});


test('insertBefore/After with duplicate values', function () {
	$arr = ['a' => 'same', 'b' => 'same', 'c' => 'different'];

	$dolly = $arr;
	Arrays::insertBefore($dolly, 'b', ['new' => 'value']);
	Assert::same(['a' => 'same', 'new' => 'value', 'b' => 'same', 'c' => 'different'], $dolly);

	$dolly = $arr;
	Arrays::insertAfter($dolly, 'a', ['new' => 'value']);
	Assert::same(['a' => 'same', 'new' => 'value', 'b' => 'same', 'c' => 'different'], $dolly);
});
