<?php

/**
 * Test: Nette\Utils\Json::decode()
 */

declare(strict_types=1);

use Nette\Utils\Json;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(['a' => 1], Json::decodeToArray('{"a":1}'));
