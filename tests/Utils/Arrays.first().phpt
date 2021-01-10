<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::null(Arrays::first([]));
Assert::null(Arrays::first([null]));
Assert::false(Arrays::first([false]));
Assert::same(1, Arrays::first([1, 2, 3]));


$arr = [1, 2, 3];
end($arr);
Assert::same(1, Arrays::first($arr));
Assert::same(3, current($arr));
