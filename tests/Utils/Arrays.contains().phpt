<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::false(Arrays::contains([], 'a'));
Assert::true(Arrays::contains(['a'], 'a'));
Assert::true(Arrays::contains([1, 2, 'a'], 'a'));
Assert::false(Arrays::contains([1, 2, 3], 'a'));
Assert::false(Arrays::contains([1, 2, 3], '1'));
