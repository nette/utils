<?php

/**
 * Test: Nette\Utils\Random::generate()
 */

use Nette\Utils\Random;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(10, strlen(Random::generate()));
Assert::same(0, strlen(Random::generate(0)));
Assert::same(5, strlen(Random::generate(5)));
Assert::same(200, strlen(Random::generate(200)));

Assert::truthy(preg_match('#^[0-9a-z]+$#', Random::generate()));
Assert::truthy(preg_match('#^[0-9]+$#', Random::generate(1000, '0-9')));
Assert::truthy(preg_match('#^[0a-z12]+$#', Random::generate(1000, '0a-z12')));
Assert::truthy(preg_match('#^[-a]+$#', Random::generate(1000, '-a')));
Assert::truthy(preg_match('#^[0]+$#', Random::generate(1000, '000')));
