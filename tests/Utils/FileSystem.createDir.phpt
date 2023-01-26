<?php

/**
 * Test: Nette\Utils\FileSystem createDir()
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('createDir', function () {
	FileSystem::createDir(getTempDir() . '/1/b/');
	Assert::true(is_dir(getTempDir() . '/1/b'));

	FileSystem::createDir(getTempDir() . '/1/');
});

Assert::exception(function () {
	FileSystem::createDir('');
}, Nette\IOException::class, "Unable to create directory '' with mode 777.%A%");
