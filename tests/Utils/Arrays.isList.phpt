<?php

/**
 * Test: Nette\Utils\Arrays::isList()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::false(Arrays::isList(null));
Assert::true(Arrays::isList([]));
Assert::true(Arrays::isList([1]));
Assert::true(Arrays::isList(['a', 'b', 'c']));
Assert::false(Arrays::isList([4 => 1, 2, 3]));
Assert::false(Arrays::isList([1 => 'a', 0 => 'b']));
Assert::false(Arrays::isList(['key' => 'value']));
$arr = [];
$arr[] = &$arr;
Assert::true(Arrays::isList($arr));
