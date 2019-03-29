<?php

/**
 * Test: Nette\Utils\Json::decode()
 */

declare(strict_types=1);

use Nette\Utils\Json;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::equal((object) ['a' => 1], Json::decodeToObject('{"a":1}'));
Assert::equal((object) ['a', 'b'], Json::decodeToObject('["a","b"]'));
