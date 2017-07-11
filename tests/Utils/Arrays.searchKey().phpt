<?php

/**
 * Test: Nette\Utils\Arrays::searchKey()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	null => 'first',
	false => 'second',
	1 => 'third',
	7 => 'fourth',
];

Assert::same([
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
], $arr);


Assert::same(2, Arrays::searchKey($arr, '1'));
Assert::same(2, Arrays::searchKey($arr, 1));
Assert::same(1, Arrays::searchKey($arr, 0));
Assert::same(0, Arrays::searchKey($arr, null));
Assert::same(0, Arrays::searchKey($arr, ''));
Assert::null(Arrays::searchKey($arr, 'undefined'));
