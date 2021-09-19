<?php

/**
 * Test: Nette\Utils\Arrays::getKeyOffset()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	'' => 'first',
	0 => 'second',
	7 => 'third',
	1 => 'fourth',
];

Assert::same(3, Arrays::getKeyOffset($arr, '1'));
Assert::same(3, Arrays::getKeyOffset($arr, 1));
Assert::same(2, Arrays::getKeyOffset($arr, 7));
Assert::same(1, Arrays::getKeyOffset($arr, 0));
Assert::same(0, Arrays::getKeyOffset($arr, ''));
Assert::null(Arrays::getKeyOffset($arr, 'undefined'));
