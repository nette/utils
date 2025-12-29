<?php

/**
 * Test: Nette\Utils\Arrays::associate()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Nette\Utils\DateTime;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	['name' => 'John', 'age' => 11],
	['name' => 'John', 'age' => 22],
	['name' => 'Mary', 'age' => null],
	['name' => 'Paul', 'age' => 44],
];


test('basic key association', function () use ($arr) {
	Assert::same(
		[
			'John' => ['name' => 'John', 'age' => 11],
			'Mary' => ['name' => 'Mary', 'age' => null],
			'Paul' => ['name' => 'Paul', 'age' => 44],
		],
		Arrays::associate($arr, 'name'),
	);
});


test('empty array', function () {
	Assert::same([], Arrays::associate([], 'name'));
});


test('key association with whole row as value', function () use ($arr) {
	Assert::same(
		[
			'John' => ['name' => 'John', 'age' => 11],
			'Mary' => ['name' => 'Mary', 'age' => null],
			'Paul' => ['name' => 'Paul', 'age' => 44],
		],
		Arrays::associate($arr, 'name='),
	);
});


test('key-value pair association', function () use ($arr) {
	Assert::same(
		['John' => 22, 'Mary' => null, 'Paul' => 44],
		Arrays::associate($arr, 'name=age'),
	);
});


test('path as array', function () use ($arr) {
	Assert::same(
		['John' => 22, 'Mary' => null, 'Paul' => 44],
		Arrays::associate($arr, ['name', '=', 'age']),
	);
});


test('object result with key-based access', function () use ($arr) {
	Assert::equal(
		[
			'John' => (object) ['name' => 'John', 'age' => 11],
			'Mary' => (object) ['name' => 'Mary', 'age' => null],
			'Paul' => (object) ['name' => 'Paul', 'age' => 44],
		],
		Arrays::associate($arr, 'name->'),
	);
});


test('nested object with property-based keys', function () use ($arr) {
	Assert::equal(
		[
			11 => (object) ['John' => ['name' => 'John', 'age' => 11]],
			22 => (object) ['John' => ['name' => 'John', 'age' => 22]],
			'' => (object) ['Mary' => ['name' => 'Mary', 'age' => null]],
			44 => (object) ['Paul' => ['name' => 'Paul', 'age' => 44]],
		],
		Arrays::associate($arr, 'age->name'),
	);
});


test('object as root result', function () use ($arr) {
	Assert::equal(
		(object) [
			'John' => ['name' => 'John', 'age' => 11],
			'Mary' => ['name' => 'Mary', 'age' => null],
			'Paul' => ['name' => 'Paul', 'age' => 44],
		],
		Arrays::associate($arr, '->name'),
	);

	Assert::equal(
		(object) [],
		Arrays::associate([], '->name'),
	);
});


test('grouping with pipe operator', function () use ($arr) {
	Assert::same(
		[
			'John' => [
				11 => ['name' => 'John', 'age' => 11],
				22 => ['name' => 'John', 'age' => 22],
			],
			'Mary' => [
				'' => ['name' => 'Mary', 'age' => null],
			],
			'Paul' => [
				44 => ['name' => 'Paul', 'age' => 44],
			],
		],
		Arrays::associate($arr, 'name|age'),
	);
});


test('grouping with pipe - last value wins on collision', function () use ($arr) {
	Assert::same(
		[
			'John' => ['name' => 'John', 'age' => 11],
			'Mary' => ['name' => 'Mary', 'age' => null],
			'Paul' => ['name' => 'Paul', 'age' => 44],
		],
		Arrays::associate($arr, 'name|'),
	);
});


test('array grouping with brackets', function () use ($arr) {
	Assert::same(
		[
			'John' => [
				['name' => 'John', 'age' => 11],
				['name' => 'John', 'age' => 22],
			],
			'Mary' => [
				['name' => 'Mary', 'age' => null],
			],
			'Paul' => [
				['name' => 'Paul', 'age' => 44],
			],
		],
		Arrays::associate($arr, 'name[]'),
	);
});


test('prefix array with keyed items', function () use ($arr) {
	Assert::same(
		[
			['John' => ['name' => 'John', 'age' => 11]],
			['John' => ['name' => 'John', 'age' => 22]],
			['Mary' => ['name' => 'Mary', 'age' => null]],
			['Paul' => ['name' => 'Paul', 'age' => 44]],
		],
		Arrays::associate($arr, '[]name'),
	);
});


test('flat array with extracted values', function () use ($arr) {
	Assert::same(
		['John', 'John', 'Mary', 'Paul'],
		Arrays::associate($arr, '[]=name'),
	);
});


test('complex combination with nested arrays', function () use ($arr) {
	Assert::same(
		[
			'John' => [
				[11 => ['name' => 'John', 'age' => 11]],
				[22 => ['name' => 'John', 'age' => 22]],
			],
			'Mary' => [
				['' => ['name' => 'Mary', 'age' => null]],
			],
			'Paul' => [
				[44 => ['name' => 'Paul', 'age' => 44]],
			],
		],
		Arrays::associate($arr, 'name[]age'),
	);
});


test('identity transformation with empty brackets', function () use ($arr) {
	Assert::same($arr, Arrays::associate($arr, '[]'));
});


test('converts objects to arrays in input', function () {
	$arr = [
		(object) ['name' => 'John', 'age' => 11],
		(object) ['name' => 'John', 'age' => 22],
		(object) ['name' => 'Mary', 'age' => null],
		(object) ['name' => 'Paul', 'age' => 44],
	];

	Assert::same(
		[
			['name' => 'John', 'age' => 11],
			['name' => 'John', 'age' => 22],
			['name' => 'Mary', 'age' => null],
			['name' => 'Paul', 'age' => 44],
		],
		Arrays::associate($arr, '[]'),
	);
});


test('allows objects as keys and values', function () {
	$arr = [['date' => new DateTime('2014-02-05')]];

	Assert::equal(
		['2014-02-05 00:00:00' => new DateTime('2014-02-05')],
		Arrays::associate($arr, 'date=date'),
	);

	Assert::equal(
		(object) ['2014-02-05 00:00:00' => new DateTime('2014-02-05')],
		Arrays::associate($arr, '->date=date'),
	);
});
