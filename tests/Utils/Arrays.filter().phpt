<?php

/**
 * Test: Nette\Utils\Arrays::filter()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same(
	['a' => 1, 'b' => 2],
	Arrays::filter(
		['a' => 1, 'b' => 2, 'c' => 3],
		fn($v) => $v < 3,
	),
);

Assert::same(
	['c' => 3],
	Arrays::filter(
		['a' => 1, 'b' => 2, 'c' => 3],
		fn($v, $k) => $k === 'c',
	),
);

Assert::same(
	['a' => 1, 'b' => 2, 'c' => 3],
	Arrays::filter(
		['a' => 1, 'b' => 2, 'c' => 3],
		fn($v, $k, $a) => $a === ['a' => 1, 'b' => 2, 'c' => 3]
	),
);

Assert::same(
	[],
	Arrays::filter([], fn() => true)
);
