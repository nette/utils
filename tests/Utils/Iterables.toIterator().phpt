<?php

/**
 * Test: Nette\Utils\Iterables::toIterator()
 */

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('array', function () {
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


test('Iterator', function () {
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


test('IteratorAggregate', function () {
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
