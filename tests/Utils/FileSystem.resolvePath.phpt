<?php declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('absolute paths', function () {
	$sep = DIRECTORY_SEPARATOR;
	Assert::same("{$sep}abs{$sep}path", FileSystem::resolvePath('/base/dir', '/abs/path'));
	Assert::same("c:{$sep}abs{$sep}path", FileSystem::resolvePath('/base/dir', 'c:/abs/path'));
	Assert::same('http://example.com', FileSystem::resolvePath('/base/dir', 'http://example.com'));
	Assert::same("{$sep}", FileSystem::resolvePath('base', '/'));
});

test('relative paths', function () {
	$sep = DIRECTORY_SEPARATOR;
	Assert::same("base{$sep}rel", FileSystem::resolvePath('base', 'rel'));
	Assert::same("{$sep}base{$sep}rel", FileSystem::resolvePath('/base', 'rel'));
	Assert::same("{$sep}base{$sep}dir{$sep}file.txt", FileSystem::resolvePath('/base/dir/', 'file.txt'));
});

test('path normalization', function () {
	$sep = DIRECTORY_SEPARATOR;
	Assert::same("{$sep}base{$sep}", FileSystem::resolvePath('/base/dir', '../'));
	Assert::same("{$sep}base{$sep}other", FileSystem::resolvePath('/base/dir/', '../other'));
	Assert::same("{$sep}..{$sep}other", FileSystem::resolvePath('/base/', '../../other'));
	Assert::same("base{$sep}dir", FileSystem::resolvePath('base/./dir/../', 'dir'));
});

test('special cases', function () {
	$sep = DIRECTORY_SEPARATOR;
	Assert::same('base', FileSystem::resolvePath('base', ''));
	Assert::same("base{$sep}dir", FileSystem::resolvePath('base/dir', ''));
	Assert::same('', FileSystem::resolvePath('', ''));
});
