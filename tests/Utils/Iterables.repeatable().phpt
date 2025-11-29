<?php

/**
 * Test: Nette\Utils\Iterables::repeatable()
 */

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$counter = 0;
$repeatable = Iterables::repeatable(function () use (&$counter) {
	$counter++;
	yield 'a' => 1;
	yield 'b' => 2;
	yield 'c' => 3;
});

// First iteration
Assert::same(
	['a' => 1, 'b' => 2, 'c' => 3],
	iterator_to_array($repeatable),
);
Assert::same(1, $counter);

// Second iteration - factory should be called again
Assert::same(
	['a' => 1, 'b' => 2, 'c' => 3],
	iterator_to_array($repeatable),
);
Assert::same(2, $counter);


// Test with empty iterator
$repeatable = Iterables::repeatable(fn() => new EmptyIterator);
Assert::same(
	[],
	iterator_to_array($repeatable),
);
