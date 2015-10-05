<?php

/**
 * Test: Nette\Iterators\CachingIterator constructor.
 */

use Nette\Iterators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () { // ==> array
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


test(function () { // ==> stdClass
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


test(function () { // ==> IteratorAggregate
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

test(function () { // ==> Iterator
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


test(function () { // ==> SimpleXMLElement
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


test(function () { // ==> object
	Assert::exception(function () {
		$arr = dir('.');
		foreach (new Iterators\CachingIterator($arr) as $k => $v);
	}, InvalidArgumentException::class, NULL);
});


class RecursiveIteratorAggregate implements IteratorAggregate
{
	public function getIterator()
	{
		return new ArrayObject(['Nette', 'Framework']);
	}
}


test(function () { // ==> recursive IteratorAggregate
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
