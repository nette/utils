<?php

declare(strict_types=1);

use Nette\Utils\Helpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(1, Helpers::falseToNull(1));
Assert::same(0, Helpers::falseToNull(0));
Assert::same(null, Helpers::falseToNull(null));
Assert::same(true, Helpers::falseToNull(true));
Assert::same(null, Helpers::falseToNull(false));
Assert::same([], Helpers::falseToNull([]));
