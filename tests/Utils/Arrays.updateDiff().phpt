<?php

/**
 * Test: Nette\Utils\Arrays::updateDiff()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('Basics', function () {
	Assert::same([], Arrays::updateDiff([], []));
	Assert::same([], Arrays::updateDiff(['a' => ''], []));
});


test('New keys', function () {
	$to = [
		'a' => null,
		'b' => false,
		'c' => '',
		'd' => 0,
	];
	Assert::same($to, Arrays::updateDiff([], $to));
});


test('To falsy values', function () {
	$from = [
		'a' => null,
		'b' => false,
		'c' => '',
		'd' => 0,
	];

	$toNull = ['a' => null, 'b' => null, 'c' => null, 'd' => null];
	Assert::same([
		'b' => null,
		'c' => null,
		'd' => null,
	], Arrays::updateDiff($from, $toNull));


	$toFalse = ['a' => false, 'b' => false, 'c' => false, 'd' => false];
	Assert::same([
		'a' => false,
		'c' => false,
		'd' => false,
	], Arrays::updateDiff($from, $toFalse));


	$toEmpty = ['a' => '', 'b' => '', 'c' => '', 'd' => ''];
	Assert::same([
		'a' => '',
		'b' => '',
		'd' => '',
	], Arrays::updateDiff($from, $toEmpty));


	$toZero = ['a' => 0, 'b' => 0, 'c' => 0, 'd' => 0];
	Assert::same([
		'a' => 0,
		'b' => 0,
		'c' => 0,
	], Arrays::updateDiff($from, $toZero));
});
