<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	Assert::same('/', FileSystem::unixSlashes('\\'));
	Assert::same('/', FileSystem::unixSlashes('/'));
});
