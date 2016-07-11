<?php

/**
 * Test: Nette\Utils\Strings::replace()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('@o wor@d!', Strings::replace('hello world!', '#[e-l]+#', function () { return '@'; }));
