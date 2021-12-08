<?php

/**
 * Test: Nette\Iterators\Mapper
 */

declare(strict_types=1);

use Nette\Iterators;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	'Nette' => 'Framework',
	'David' => 'Grudl',
];

$callback = function ($item, $key) {
	return $key . ': ' . $item;
};

$iterator = new Iterators\Mapper(new ArrayIterator($arr), $callback);

$iterator->rewind();
Assert::true($iterator->valid());
Assert::same('Nette: Framework', $iterator->current());

$iterator->next();
Assert::true($iterator->valid());
Assert::same('David: Grudl', $iterator->current());

$iterator->next();
Assert::false($iterator->valid());
