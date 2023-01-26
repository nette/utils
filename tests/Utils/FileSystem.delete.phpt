<?php

/**
 * Test: Nette\Utils\FileSystem delete()
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('delete file', function () {
	FileSystem::write(getTempDir() . '/7/file', 'Hello');
	FileSystem::delete(getTempDir() . '/7/file');
	Assert::true(is_dir(getTempDir() . '/7'));
});

test('delete dir', function () {
	FileSystem::write(getTempDir() . '/7/file', 'Hello');
	FileSystem::delete(getTempDir() . '/7');
	Assert::false(file_exists(getTempDir() . '/7'));
});
