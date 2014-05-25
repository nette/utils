<?php

/**
 * Test: Nette\Iterators\Filter count()
 */

use Nette\Iterators,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() {
	$arr = array('Zero', 'One', 'Two', 'Three', 'Four');
	$isKeyEven = function($value, $key) {
		return $key % 2 === 0;
	};
	$isKeyOdd = function($value, $key) {
		return $key % 2 !== 0;
	};
	$iterator = new \ArrayIterator($arr);
	$evenIterator = new Iterators\Filter( $iterator, $isKeyEven);
	$oddIterator = new Iterators\Filter( $iterator, $isKeyOdd);
	Assert::same( 3, $evenIterator->count() );
	Assert::same( 2, $oddIterator->count() );
});

