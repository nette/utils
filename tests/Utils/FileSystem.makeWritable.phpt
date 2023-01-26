<?php

/**
 * Test: Nette\Utils\FileSystem makeWritable()
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


if (!defined('PHP_WINDOWS_VERSION_BUILD')) {
	test('makeWritable', function () {
		FileSystem::createDir(getTempDir() . '/12/x');
		FileSystem::write(getTempDir() . '/12/x/file', 'Hello');
		chmod(getTempDir() . '/12/x/file', 0444);
		chmod(getTempDir() . '/12/x', 0555);
		chmod(getTempDir() . '/12', 0555);

		FileSystem::makeWritable(getTempDir() . '/12');

		Assert::same(0777, fileperms(getTempDir() . '/12') & 0777);
		Assert::same(0777, fileperms(getTempDir() . '/12/x') & 0777);
		Assert::same(0666, fileperms(getTempDir() . '/12/x/file') & 0777);
	});
}

Assert::exception(function () {
	FileSystem::makeWritable(getTempDir() . '/13');
}, Nette\IOException::class, "File or directory '%S%' not found.");
