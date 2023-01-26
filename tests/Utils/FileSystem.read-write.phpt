<?php

/**
 * Test: Nette\Utils\FileSystem read & write.
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('write + read', function () {
	FileSystem::write(getTempDir() . '/2/file', 'Hello');
	Assert::true(is_file(getTempDir() . '/2/file'));
	Assert::same('Hello', FileSystem::read(getTempDir() . '/2/file'));
});

Assert::exception(function () {
	FileSystem::write('', 'Hello');
}, Nette\IOException::class, "Unable to create directory '' with mode 777.%A%");

Assert::exception(function () {
	FileSystem::read('missing');
}, Nette\IOException::class, "Unable to read file 'missing'. %a%");
