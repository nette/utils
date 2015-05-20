<?php

/**
 * Test: Nette\Iterators\Mapper
 */

use Nette\Iterators,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	'Nette' => 'Framework',
	'David' => 'Grudl',
];

$callback = function($item, $key) {
	return $key . ': ' . $item;
};

$iterator = new Iterators\Mapper(new \ArrayIterator($arr), $callback);

$iterator->rewind();
Assert::true( $iterator->valid() );
assert::same( 'Nette: Framework', $iterator->current() );

$iterator->next();
Assert::true( $iterator->valid() );
assert::same( 'David: Grudl', $iterator->current() );

$iterator->next();
Assert::false( $iterator->valid() );
