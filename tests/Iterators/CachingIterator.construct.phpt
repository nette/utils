<?php

/**
 * Test: Nette\Iterators\CachingIterator constructor.
 */

use Nette\Iterators,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() { // ==> array
	$arr = array('Nette', 'Framework');
	$tmp = array();
	foreach (new Iterators\CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});


test(function() { // ==> stdClass
	$arr = (object) array('Nette', 'Framework');
	$tmp = array();
	foreach (new Iterators\CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});


test(function() { // ==> IteratorAggregate
	$arr = new ArrayObject(array('Nette', 'Framework'));
	$tmp = array();
	foreach (new Iterators\CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});

test(function() { // ==> Iterator
	$arr = new ArrayObject(array('Nette', 'Framework'));
	$tmp = array();
	foreach (new Iterators\CachingIterator($arr->getIterator()) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});


test(function() { // ==> SimpleXMLElement
	$arr = new SimpleXMLElement('<feed><item>Nette</item><item>Framework</item></feed>');
	$tmp = array();
	foreach (new Iterators\CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'item => Nette',
		'item => Framework',
	), $tmp );
});


test(function() { // ==> object
	Assert::exception(function() {
		$arr = dir('.');
		foreach (new Iterators\CachingIterator($arr) as $k => $v);
	}, 'InvalidArgumentException', NULL);
});


class Iterator579a6c36991717f63eb0863942fe43ec33ac13d2 implements IteratorAggregate {
	public function getIterator() {
		return new ArrayObject(array('Nette', 'Framework'));
	}
}


test(function() { // ==> recursive IteratorAggregate
	$arr = new Iterator579a6c36991717f63eb0863942fe43ec33ac13d2();
	$tmp = array();
	foreach (new Iterators\CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});
