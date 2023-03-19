<?php

/**
 * Test: Nette\Utils\Finder append.
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('append finder', function () {
	($finder = new Finder)
		->files('file.txt')
		->in('fixtures.finder')
		->append()
		->directories('subdir*')
		->from('fixtures.finder')
		->append()
		->files('file.txt')
		->from('fixtures.finder/*/subdir*');

	$ds = DIRECTORY_SEPARATOR;
	Assert::same([
		"fixtures.finder{$ds}file.txt",
		"fixtures.finder{$ds}subdir",
		"fixtures.finder{$ds}subdir{$ds}subdir2",
		"fixtures.finder{$ds}subdir{$ds}subdir2{$ds}file.txt",
	], array_map('strval', $finder->collect()));
});

test('append files', function () {
	($finder = new Finder)
		->append(__FILE__)
		->append(FileSystem::unixSlashes(__DIR__));

	Assert::equal([
		new Nette\Utils\FileInfo(__FILE__),
		new Nette\Utils\FileInfo(__DIR__),
	], $finder->collect());
});
