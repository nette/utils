<?php

/**
 * Test: Nette\Utils\Arrays::renameKey() - preserves references
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('preserves references when renaming key', function () {
	$arr = [
		1 => 'a',
		2 => 'b',
	];

	$arr2 = [
		1 => &$arr[1],
		2 => &$arr[2],
	];

	Arrays::renameKey($arr, '1', 'new1');

	// Modify via reference
	$arr2[1] = 'A';
	$arr2[2] = 'B';

	// Should reflect in renamed array
	Assert::same('A', $arr['new1']);
	Assert::same('B', $arr[2]);
});


test('preserves references when renaming to existing numeric key', function () {
	$arr = [
		1 => 'a',
		2 => 'b',
	];

	$arr2 = [
		1 => &$arr[1],
		2 => &$arr[2],
	];

	Arrays::renameKey($arr, '1', 'new1');
	Arrays::renameKey($arr, 'new1', 2);

	// Modify via reference
	$arr2[1] = 'AA';
	$arr2[2] = 'BB';

	// The value at key 2 should now be the renamed value with preserved reference
	Assert::same('AA', $arr[2]);
});


test('maintains reference through multiple renames', function () {
	$value = 'original';
	$arr = ['key' => &$value];

	Arrays::renameKey($arr, 'key', 'temp');
	Arrays::renameKey($arr, 'temp', 'final');

	// Modify original variable
	$value = 'modified';

	// Should reflect in array with renamed key
	Assert::same('modified', $arr['final']);
});


test('reference is preserved when renaming to same key', function () {
	$value = 'test';
	$arr = ['key' => &$value];

	Arrays::renameKey($arr, 'key', 'key');

	$value = 'changed';

	Assert::same('changed', $arr['key']);
});


test('complex reference scenario with nested values', function () {
	$shared = ['shared' => 'data'];
	$arr = [
		'a' => &$shared,
		'b' => ['nested' => 'value'],
	];

	Arrays::renameKey($arr, 'a', 'shared_ref');

	// Modify shared reference
	$shared['shared'] = 'modified';

	// Should be reflected in renamed key
	Assert::same(['shared' => 'modified'], $arr['shared_ref']);
});
