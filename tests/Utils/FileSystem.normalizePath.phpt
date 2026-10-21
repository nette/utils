<?php declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$sep = DIRECTORY_SEPARATOR;
	Assert::same('', FileSystem::normalizePath(''));
	Assert::same($sep, FileSystem::normalizePath('\\'));
	Assert::same($sep, FileSystem::normalizePath('/'));
	Assert::same('file', FileSystem::normalizePath('file'));
	Assert::same("file{$sep}", FileSystem::normalizePath('file/'));
	Assert::same("d:{$sep}file", FileSystem::normalizePath('d:/file'));
	Assert::same("d:{$sep}file", FileSystem::normalizePath('d:\file'));
	Assert::same("{$sep}file", FileSystem::normalizePath('/file'));

	Assert::same('', FileSystem::normalizePath('.'));
	Assert::same($sep, FileSystem::normalizePath('\.'));
	Assert::same($sep, FileSystem::normalizePath('/.'));
	Assert::same($sep, FileSystem::normalizePath('.\\'));
	Assert::same($sep, FileSystem::normalizePath('./'));

	Assert::same($sep, FileSystem::normalizePath('/file/..'));
	Assert::same($sep, FileSystem::normalizePath('/file/../'));
	Assert::same('', FileSystem::normalizePath('file/..'));
	Assert::same($sep, FileSystem::normalizePath('file/../'));
	Assert::same("{$sep}..", FileSystem::normalizePath('/file/../..'));
	Assert::same("{$sep}..{$sep}", FileSystem::normalizePath('/file/../../'));
	Assert::same('..', FileSystem::normalizePath('file/../..'));
	Assert::same("..{$sep}", FileSystem::normalizePath('file/../../'));
	Assert::same("{$sep}..{$sep}bar", FileSystem::normalizePath('/file/../../bar'));
	Assert::same("..{$sep}bar", FileSystem::normalizePath('file/../../bar'));
	Assert::same("..{$sep}file", FileSystem::normalizePath('../file'));
	Assert::same("{$sep}..{$sep}file", FileSystem::normalizePath('/../file'));
	Assert::same('file', FileSystem::normalizePath('./file'));
	Assert::same("{$sep}file", FileSystem::normalizePath('/./file'));

	Assert::same("{$sep}..{$sep}bar", FileSystem::normalizePath('/file/./.././.././bar'));
	Assert::same("..{$sep}bar", FileSystem::normalizePath('file/../../bar/.'));
	Assert::same("{$sep}..{$sep}bar{$sep}", FileSystem::normalizePath('/file/./.././.././bar/'));
	Assert::same("..{$sep}bar{$sep}", FileSystem::normalizePath('file/../../bar/./'));


	Assert::same($sep, FileSystem::normalizePath('//'));
	Assert::same("{$sep}foo{$sep}", FileSystem::normalizePath('//foo//'));
	Assert::same("{$sep}", FileSystem::normalizePath('//foo//..//'));
});
