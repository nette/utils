<?php

/**
 * Test: Nette\Utils\Arrays::renameKey()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('successfully renames existing key', function () {
	$arr = [
		'' => 'first',
		0 => 'second',
		7 => 'fourth',
		1 => 'third',
	];

	Assert::true(Arrays::renameKey($arr, '1', 'new1'));
	Assert::same([
		'' => 'first',
		0 => 'second',
		7 => 'fourth',
		'new1' => 'third',
	], $arr);
});


test('renames numeric key to string key', function () {
	$arr = [
		'' => 'first',
		0 => 'second',
		7 => 'fourth',
		1 => 'third',
	];

	Arrays::renameKey($arr, 0, 'new2');
	Assert::same([
		'' => 'first',
		'new2' => 'second',
		7 => 'fourth',
		1 => 'third',
	], $arr);
});


test('renames empty string key', function () {
	$arr = [
		'' => 'first',
		'a' => 'second',
	];

	Arrays::renameKey($arr, '', 'new');
	Assert::same([
		'new' => 'first',
		'a' => 'second',
	], $arr);
});


test('returns false when key does not exist', function () {
	$arr = ['a' => 'first', 'b' => 'second'];

	Assert::false(Arrays::renameKey($arr, 'nonexistent', 'new'));
	Assert::same(['a' => 'first', 'b' => 'second'], $arr);
});


test('renaming to existing key overwrites it', function () {
	$arr = [
		'new3' => 'first',
		'new2' => 'second',
		7 => 'fourth',
		'new1' => 'third',
	];

	Arrays::renameKey($arr, 'new2', 'new3');
	Assert::same([
		'new3' => 'second',
		7 => 'fourth',
		'new1' => 'third',
	], $arr);
});


test('renaming to existing key - second case', function () {
	$arr = [
		'new3' => 'second',
		7 => 'fourth',
		'new1' => 'third',
	];

	Arrays::renameKey($arr, 'new3', 'new1');
	Assert::same([
		'new1' => 'second',
		7 => 'fourth',
	], $arr);
});


test('renaming key to itself returns true and preserves array', function () {
	$arr = ['key' => 'value', 'other' => 'data'];

	Assert::true(Arrays::renameKey($arr, 'key', 'key'));
	Assert::same(['key' => 'value', 'other' => 'data'], $arr);
});


test('preserves array order when renaming', function () {
	$arr = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];

	Arrays::renameKey($arr, 'b', 'new');
	Assert::same(['a' => 1, 'new' => 2, 'c' => 3, 'd' => 4], $arr);

	// Verify order is preserved
	Assert::same(['a', 'new', 'c', 'd'], array_keys($arr));
});


test('works with single element array', function () {
	$arr = ['only' => 'one'];

	Assert::true(Arrays::renameKey($arr, 'only', 'renamed'));
	Assert::same(['renamed' => 'one'], $arr);
});


test('handles numeric key conversions', function () {
	$arr = [0 => 'zero', 1 => 'one', 2 => 'two'];

	Arrays::renameKey($arr, 0, 'first');
	Assert::same(['first' => 'zero', 1 => 'one', 2 => 'two'], $arr);

	Arrays::renameKey($arr, 'first', 10);
	Assert::same([10 => 'zero', 1 => 'one', 2 => 'two'], $arr);
});


test('handles mixed key types', function () {
	$arr = ['str' => 'string', 5 => 'int', '' => 'empty'];

	Assert::true(Arrays::renameKey($arr, 5, 'five'));
	Assert::same(['str' => 'string', 'five' => 'int', '' => 'empty'], $arr);

	Assert::true(Arrays::renameKey($arr, 'str', 0));
	Assert::same([0 => 'string', 'five' => 'int', '' => 'empty'], $arr);
});


test('works with complex values', function () {
	$obj = (object) ['prop' => 'value'];
	$arr = ['a' => [1, 2, 3], 'b' => $obj, 'c' => null];

	Arrays::renameKey($arr, 'a', 'array');
	Assert::same(['array' => [1, 2, 3], 'b' => $obj, 'c' => null], $arr);
});


test('handles consecutive renames', function () {
	$arr = ['original' => 'value'];

	Arrays::renameKey($arr, 'original', 'temp');
	Arrays::renameKey($arr, 'temp', 'final');

	Assert::same(['final' => 'value'], $arr);
});


test('returns false for empty array', function () {
	$arr = [];

	Assert::false(Arrays::renameKey($arr, 'any', 'key'));
	Assert::same([], $arr);
});


test('handles string numeric keys correctly', function () {
	$arr = ['1' => 'one', '2' => 'two', '10' => 'ten'];

	// String '1' should match numeric key 1
	Assert::true(Arrays::renameKey($arr, '1', 'first'));
	Assert::same(['first' => 'one', '2' => 'two', '10' => 'ten'], $arr);
});


test('renaming preserves position in associative array', function () {
	$arr = [
		'first' => 1,
		'second' => 2,
		'third' => 3,
		'fourth' => 4,
	];

	Arrays::renameKey($arr, 'second', 'TWO');

	$keys = array_keys($arr);
	Assert::same('first', $keys[0]);
	Assert::same('TWO', $keys[1]);
	Assert::same('third', $keys[2]);
	Assert::same('fourth', $keys[3]);
});
