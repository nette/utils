<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\FileSystem writeAtomic() & symlinks
 */

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$dir = getTempDir() . '/symlink';
FileSystem::write($dir . '/target.txt', 'old');
if (!@symlink($dir . '/target.txt', $dir . '/link.txt')) { // @ - may not be permitted
	Tester\Environment::skip('Unable to create symlinks.');
}


test('writes through a symlink to its target', function () use ($dir) {
	FileSystem::writeAtomic($dir . '/link.txt', 'new');
	Assert::true(is_link($dir . '/link.txt'));
	Assert::same('new', FileSystem::read($dir . '/target.txt'));
	Assert::same('new', FileSystem::read($dir . '/link.txt'));
});
