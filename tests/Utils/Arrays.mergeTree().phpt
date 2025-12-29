<?php

/**
 * Test: Nette\Utils\Arrays::mergeTree()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('basic merge with no key collisions', function () {
	$arr1 = ['a' => 1, 'b' => 2];
	$arr2 = ['c' => 3, 'd' => 4];
	Assert::same(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4], Arrays::mergeTree($arr1, $arr2));
});


test('key collision - prefers value from first array', function () {
	$arr1 = ['a' => 1, 'b' => 2];
	$arr2 = ['a' => 99, 'c' => 3];
	Assert::same(['a' => 1, 'b' => 2, 'c' => 3], Arrays::mergeTree($arr1, $arr2));
});


test('recursive merge of nested arrays', function () {
	$arr1 = ['a' => ['b' => 1, 'c' => 2]];
	$arr2 = ['a' => ['d' => 3, 'e' => 4]];
	Assert::same(['a' => ['b' => 1, 'c' => 2, 'd' => 3, 'e' => 4]], Arrays::mergeTree($arr1, $arr2));
});


test('recursive merge with key collision in nested arrays', function () {
	$arr1 = ['a' => ['b' => 1, 'c' => 2]];
	$arr2 = ['a' => ['b' => 99, 'd' => 3]];
	Assert::same(['a' => ['b' => 1, 'c' => 2, 'd' => 3]], Arrays::mergeTree($arr1, $arr2));
});


test('deep nesting - three levels', function () {
	$arr1 = ['a' => ['b' => ['c' => 1, 'd' => 2]]];
	$arr2 = ['a' => ['b' => ['e' => 3]]];
	Assert::same(['a' => ['b' => ['c' => 1, 'd' => 2, 'e' => 3]]], Arrays::mergeTree($arr1, $arr2));
});


test('mix of array and scalar values - scalar in first array', function () {
	$arr1 = ['a' => 1];
	$arr2 = ['a' => ['b' => 2]];
	Assert::same(['a' => 1], Arrays::mergeTree($arr1, $arr2));
});


test('mix of array and scalar values - scalar in second array', function () {
	$arr1 = ['a' => ['b' => 1]];
	$arr2 = ['a' => 99];
	Assert::same(['a' => ['b' => 1]], Arrays::mergeTree($arr1, $arr2));
});


test('empty first array', function () {
	$arr1 = [];
	$arr2 = ['a' => 1, 'b' => 2];
	Assert::same(['a' => 1, 'b' => 2], Arrays::mergeTree($arr1, $arr2));
});


test('empty second array', function () {
	$arr1 = ['a' => 1, 'b' => 2];
	$arr2 = [];
	Assert::same(['a' => 1, 'b' => 2], Arrays::mergeTree($arr1, $arr2));
});


test('both arrays empty', function () {
	Assert::same([], Arrays::mergeTree([], []));
});


test('numeric keys are preserved', function () {
	$arr1 = [0 => 'a', 1 => 'b'];
	$arr2 = [0 => 'x', 2 => 'c'];
	Assert::same([0 => 'a', 1 => 'b', 2 => 'c'], Arrays::mergeTree($arr1, $arr2));
});


test('nested arrays with numeric keys', function () {
	$arr1 = ['items' => [0 => 'a', 1 => 'b']];
	$arr2 = ['items' => [0 => 'x', 2 => 'c']];
	Assert::same(['items' => [0 => 'a', 1 => 'b', 2 => 'c']], Arrays::mergeTree($arr1, $arr2));
});


test('preserves null values', function () {
	$arr1 = ['a' => null, 'b' => ['c' => null]];
	$arr2 = ['a' => 1, 'b' => ['c' => 2, 'd' => null]];
	Assert::same(['a' => null, 'b' => ['c' => null, 'd' => null]], Arrays::mergeTree($arr1, $arr2));
});


test('handles boolean and other scalar types', function () {
	$arr1 = ['bool' => true, 'float' => 1.5, 'string' => 'test'];
	$arr2 = ['bool' => false, 'int' => 42];
	Assert::same(['bool' => true, 'float' => 1.5, 'string' => 'test', 'int' => 42], Arrays::mergeTree($arr1, $arr2));
});
