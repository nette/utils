<?php

/**
 * Test: Nette\Iterators\CachingIterator constructor.
 */

declare(strict_types=1);

use Nette\Iterators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('array', function () {
	$arr = ['Nette', 'Framework'];
	$tmp = [];
	foreach (new Iterators\CachingIterator($arr) as $k => $v) {
		$tmp[] = "$k => $v";
	}
	Assert::same([
		'0 => Nette',
		'1 => Framework',
	], $tmp);
});


test('stdClass', function () {
	$arr = (object) ['Nette', 'Framework'];
	$tmp = [];
	foreach (new Iterators\CachingIterator($arr) as $k => $v) {
		$tmp[] = "$k => $v";
	}
	Assert::same([
		'0 => Nette',
		'1 => Framework',
	], $tmp);
});


test('IteratorAggregate', function () {
	$arr = new ArrayObject(['Nette', 'Framework']);
	$tmp = [];
	foreach (new Iterators\CachingIterator($arr) as $k => $v) {
		$tmp[] = "$k => $v";
	}
	Assert::same([
		'0 => Nette',
		'1 => Framework',
	], $tmp);
});

test('Iterator', function () {
	$arr = new ArrayObject(['Nette', 'Framework']);
	$tmp = [];
	foreach (new Iterators\CachingIterator($arr->getIterator()) as $k => $v) {
		$tmp[] = "$k => $v";
	}
	Assert::same([
		'0 => Nette',
		'1 => Framework',
	], $tmp);
});


test('SimpleXMLElement', function () {
	$arr = new SimpleXMLElement('<feed><item>Nette</item><item>Framework</item></feed>');
	$tmp = [];
	foreach (new Iterators\CachingIterator($arr) as $k => $v) {
		$tmp[] = "$k => $v";
	}
	Assert::same([
		'item => Nette',
		'item => Framework',
	], $tmp);
});


test('object', function () {
	Assert::exception(function () {
		$arr = dir('.');
		foreach (new Iterators\CachingIterator($arr) as $k => $v);
	}, InvalidArgumentException::class, null);
});


class RecursiveIteratorAggregate implements IteratorAggregate
{
	public function getIterator(): Traversable
	{
		return new ArrayObject(['Nette', 'Framework']);
	}
}


test('recursive IteratorAggregate', function () {
	$arr = new RecursiveIteratorAggregate;
	$tmp = [];
	foreach (new Iterators\CachingIterator($arr) as $k => $v) {
		$tmp[] = "$k => $v";
	}
	Assert::same([
		'0 => Nette',
		'1 => Framework',
	], $tmp);
});
