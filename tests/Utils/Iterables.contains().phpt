<?php

declare(strict_types=1);

use Nette\Utils\Iterables;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::false(Iterables::contains(new ArrayIterator([]), 'a'));
Assert::true(Iterables::contains(new ArrayIterator(['a']), 'a'));
Assert::true(Iterables::contains(new ArrayIterator([1, 2, 'a']), 'a'));
Assert::false(Iterables::contains(new ArrayIterator([1, 2, 3]), 'a'));
Assert::false(Iterables::contains(new ArrayIterator([1, 2, 3]), '1'));
