<?php

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::false(Iterables::containsKey(new ArrayIterator([]), 'a'));
Assert::true(Iterables::containsKey(new ArrayIterator(['a']), 0));
Assert::true(Iterables::containsKey(new ArrayIterator(['x' => 1, 'y' => 2, 'z' => 3]), 'y'));
Assert::false(Iterables::containsKey(new ArrayIterator(['x' => 1, 'y' => 2, 'z' => 3]), ''));
Assert::false(Iterables::containsKey(new ArrayIterator([1, 2, 3]), '1'));
