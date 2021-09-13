<?php

/**
 * Test: Nette\Utils\Arrays::renameKey()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = [
	'' => 'first',
	0 => 'second',
	7 => 'fourth',
	1 => 'third',
];

Assert::true(Arrays::renameKey($arr, '1', 'new1'));
Assert::same([
	'' => 'first',
	0 => 'second',
	7 => 'fourth',
	'new1' => 'third',
], $arr);

Arrays::renameKey($arr, 0, 'new2');
Assert::same([
	'' => 'first',
	'new2' => 'second',
	7 => 'fourth',
	'new1' => 'third',
], $arr);

Arrays::renameKey($arr, '', 'new3');
Assert::same([
	'new3' => 'first',
	'new2' => 'second',
	7 => 'fourth',
	'new1' => 'third',
], $arr);

Arrays::renameKey($arr, '', 'new4');
Assert::same([
	'new3' => 'first',
	'new2' => 'second',
	7 => 'fourth',
	'new1' => 'third',
], $arr);

Assert::false(Arrays::renameKey($arr, 'undefined', 'new5'));
Assert::same([
	'new3' => 'first',
	'new2' => 'second',
	7 => 'fourth',
	'new1' => 'third',
], $arr);

Arrays::renameKey($arr, 'new2', 'new3');
Assert::same([
	'new3' => 'second',
	7 => 'fourth',
	'new1' => 'third',
], $arr);

Arrays::renameKey($arr, 'new3', 'new1');
Assert::same([
	'new1' => 'second',
	7 => 'fourth',
], $arr);

Assert::true(Arrays::renameKey($arr, 'new1', 'new1'));
Assert::same([
	'new1' => 'second',
	7 => 'fourth',
], $arr);
