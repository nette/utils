<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	$S = DIRECTORY_SEPARATOR;
	Assert::same('', FileSystem::normalizePath(''));
	Assert::same($S, FileSystem::normalizePath('\\'));
	Assert::same($S, FileSystem::normalizePath('/'));
	Assert::same('file', FileSystem::normalizePath('file'));
	Assert::same("file{$S}", FileSystem::normalizePath('file/'));
	Assert::same("d:{$S}file", FileSystem::normalizePath('d:/file'));
	Assert::same("d:{$S}file", FileSystem::normalizePath('d:\file'));
	Assert::same("{$S}file", FileSystem::normalizePath('/file'));

	Assert::same('', FileSystem::normalizePath('.'));
	Assert::same($S, FileSystem::normalizePath('\\.'));
	Assert::same($S, FileSystem::normalizePath('/.'));
	Assert::same($S, FileSystem::normalizePath('.\\'));
	Assert::same($S, FileSystem::normalizePath('./'));

	Assert::same($S, FileSystem::normalizePath('/file/..'));
	Assert::same($S, FileSystem::normalizePath('/file/../'));
	Assert::same('', FileSystem::normalizePath('file/..'));
	Assert::same($S, FileSystem::normalizePath('file/../'));
	Assert::same("{$S}..", FileSystem::normalizePath('/file/../..'));
	Assert::same("{$S}..{$S}", FileSystem::normalizePath('/file/../../'));
	Assert::same('..', FileSystem::normalizePath('file/../..'));
	Assert::same("..{$S}", FileSystem::normalizePath('file/../../'));
	Assert::same("{$S}..{$S}bar", FileSystem::normalizePath('/file/../../bar'));
	Assert::same("..{$S}bar", FileSystem::normalizePath('file/../../bar'));
	Assert::same("..{$S}file", FileSystem::normalizePath('../file'));
	Assert::same("{$S}..{$S}file", FileSystem::normalizePath('/../file'));
	Assert::same('file', FileSystem::normalizePath('./file'));
	Assert::same("{$S}file", FileSystem::normalizePath('/./file'));

	Assert::same("{$S}..{$S}bar", FileSystem::normalizePath('/file/./.././.././bar'));
	Assert::same("..{$S}bar", FileSystem::normalizePath('file/../../bar/.'));
	Assert::same("{$S}..{$S}bar{$S}", FileSystem::normalizePath('/file/./.././.././bar/'));
	Assert::same("..{$S}bar{$S}", FileSystem::normalizePath('file/../../bar/./'));


	Assert::same($S, FileSystem::normalizePath('//'));
	Assert::same("{$S}foo{$S}", FileSystem::normalizePath('//foo//'));
	Assert::same("{$S}", FileSystem::normalizePath('//foo//..//'));
});
