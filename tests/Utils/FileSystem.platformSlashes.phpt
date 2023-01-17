<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	Assert::same(DIRECTORY_SEPARATOR, FileSystem::platformSlashes('\\'));
	Assert::same(DIRECTORY_SEPARATOR, FileSystem::platformSlashes('/'));
});

test('protocol', function () {
	Assert::same('file://path' . DIRECTORY_SEPARATOR . 'file', FileSystem::platformSlashes('file://path/file'));
});
