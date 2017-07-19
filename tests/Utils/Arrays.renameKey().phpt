<?php

/**
 * Test: Nette\Utils\Arrays::renameKey()
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


Arrays::renameKey($arr, '1', 'new1');
Arrays::renameKey($arr, 0, 'new2');
Arrays::renameKey($arr, null, 'new3');
Arrays::renameKey($arr, '', 'new4');
Arrays::renameKey($arr, 'undefined', 'new5');

Assert::same([
	'new3' => 'first',
	'new2' => 'second',
	'new1' => 'third',
	7 => 'fourth',
], $arr);
