<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('absolute paths', function () {
	$S = DIRECTORY_SEPARATOR;
	Assert::same("{$S}abs{$S}path", FileSystem::resolvePath('/base/dir', '/abs/path'));
	Assert::same("c:{$S}abs{$S}path", FileSystem::resolvePath('/base/dir', 'c:/abs/path'));
	Assert::same('http://example.com', FileSystem::resolvePath('/base/dir', 'http://example.com'));
	Assert::same("{$S}", FileSystem::resolvePath('base', '/'));
});

test('relative paths', function () {
	$S = DIRECTORY_SEPARATOR;
	Assert::same("base{$S}rel", FileSystem::resolvePath('base', 'rel'));
	Assert::same("{$S}base{$S}rel", FileSystem::resolvePath('/base', 'rel'));
	Assert::same("{$S}base{$S}dir{$S}file.txt", FileSystem::resolvePath('/base/dir/', 'file.txt'));
});

test('path normalization', function () {
	$S = DIRECTORY_SEPARATOR;
	Assert::same("{$S}base{$S}", FileSystem::resolvePath('/base/dir', '../'));
	Assert::same("{$S}base{$S}other", FileSystem::resolvePath('/base/dir/', '../other'));
	Assert::same("{$S}..{$S}other", FileSystem::resolvePath('/base/', '../../other'));
	Assert::same("base{$S}dir", FileSystem::resolvePath('base/./dir/../', 'dir'));
});

test('special cases', function () {
	$S = DIRECTORY_SEPARATOR;
	Assert::same('base', FileSystem::resolvePath('base', ''));
	Assert::same("base{$S}dir", FileSystem::resolvePath('base/dir', ''));
	Assert::same('', FileSystem::resolvePath('', ''));
});
