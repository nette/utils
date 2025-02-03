<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('converts slash characters to platform‐specific DIRECTORY_SEPARATOR', function () {
	Assert::same(DIRECTORY_SEPARATOR, FileSystem::platformSlashes('\\'));
	Assert::same(DIRECTORY_SEPARATOR, FileSystem::platformSlashes('/'));
});

test('converts URL paths to use platform‐specific separators', function () {
	Assert::same('file://path' . DIRECTORY_SEPARATOR . 'file', FileSystem::platformSlashes('file://path/file'));
});
