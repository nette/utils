<?php

/**
 * Test: Nette\Utils\Arrays::renameKey() as references
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	1 => 'a',
	2 => 'b',
];

$arr2 = [
	1 => &$arr[1],
	2 => &$arr[2],
];

Arrays::renameKey($arr, '1', 'new1');

$arr2[1] = 'A';
$arr2[2] = 'B';

Assert::same('A', $arr['new1']);
Assert::same('B', $arr[2]);


Arrays::renameKey($arr, 'new1', 2);

$arr2[1] = 'AA';
$arr2[2] = 'BB';

Assert::same('AA', $arr[2]);
