<?php

/**
 * Test: Nette\Utils\Iterables::toIterator()
 */

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('converts array into iterator', function () {
	$arr = ['Nette', 'Framework'];
	$tmp = [];
	foreach (Iterables::toIterator($arr) as $k => $v) {
		$tmp[] = "$k => $v";
	}

	Assert::same([
		'0 => Nette',
		'1 => Framework',
	], $tmp);
});


test('preserves ArrayIterator instance', function () {
	$arr = new ArrayIterator(['Nette', 'Framework']);
	$tmp = [];
	foreach (Iterables::toIterator($arr) as $k => $v) {
		$tmp[] = "$k => $v";
	}

	Assert::same([
		'0 => Nette',
		'1 => Framework',
	], $tmp);
});


test('converts ArrayObject to ArrayIterator', function () {
	$arr = new ArrayObject(['Nette', 'Framework']);
	Assert::type(ArrayIterator::class, Iterables::toIterator($arr));

	$tmp = [];
	foreach (Iterables::toIterator($arr) as $k => $v) {
		$tmp[] = "$k => $v";
	}

	Assert::same([
		'0 => Nette',
		'1 => Framework',
	], $tmp);
});
