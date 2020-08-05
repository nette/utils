<?php

/**
 * Test: Nette\Utils\Arrays::toKey()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(0, Arrays::toKey(0));
Assert::same(1, Arrays::toKey(1));
Assert::same('', Arrays::toKey(''));
Assert::same('', Arrays::toKey(null));
Assert::same('01', Arrays::toKey('01'));
