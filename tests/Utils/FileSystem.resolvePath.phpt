<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	$S = DIRECTORY_SEPARATOR;
	$cwd = getcwd();
	Assert::same($cwd, FileSystem::resolvePath());
	Assert::same("{$S}foo{$S}bar", FileSystem::resolvePath('/foo', 'bar'));
	Assert::same("{$S}foo{$S}bar{$S}baz", FileSystem::resolvePath('/foo', 'bar', 'baz'));
	Assert::same("{$S}foo{$S}baz", FileSystem::resolvePath('/foo', 'bar/..', 'baz'));
	Assert::same("{$S}bar{$S}baz", FileSystem::resolvePath('foo', '/bar', 'baz'));
	Assert::same("{$cwd}{$S}foo{$S}bar{$S}baz", FileSystem::resolvePath('foo', 'bar', 'baz'));
});
