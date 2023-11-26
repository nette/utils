<?php

/**
 * Test: Nette\Utils\Iterables::filter()
 */

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same(
	['a' => 1, 'b' => 2],
	iterator_to_array(Iterables::filter(
		new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]),
		fn($v) => $v < 3,
	)),
);

Assert::same(
	['c' => 3],
	iterator_to_array(Iterables::filter(
		new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]),
		fn($v, $k) => $k === 'c',
	)),
);

Assert::same(
	['a' => 1, 'b' => 2, 'c' => 3],
	iterator_to_array(Iterables::filter(
		$it = new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]),
		fn($v, $k, $a) => $a === $it,
	)),
);

Assert::same(
	[],
	iterator_to_array(Iterables::filter(
		new ArrayIterator([]),
		fn() => true,
	)),
);
