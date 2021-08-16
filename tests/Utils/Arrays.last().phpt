<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::null(Arrays::last([]));
Assert::null(Arrays::last([null]));
Assert::false(Arrays::last([false]));
Assert::same(3, Arrays::last([1, 2, 3]));


$arr = [1, 2, 3];
Assert::same(3, Arrays::last($arr));
Assert::same(1, current($arr));
