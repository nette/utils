<?php

/**
 * Test: Nette\Utils\Finder result test.
 */

declare(strict_types=1);

use Nette\Utils\FileInfo;
use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('absolute path', function () {
	$files = Finder::findFiles(basename(__FILE__))
		->in(__DIR__)
		->collect();

	Assert::equal(
		[__FILE__ => new FileInfo(__FILE__)],
		$files,
	);

	$file = reset($files);
	Assert::same(__FILE__, (string) $file);
	Assert::same('', $file->getRelativePath());
	Assert::same('Finder.fileInfo.phpt', $file->getRelativePathname());
});


test('relative path', function () {
	$files = Finder::findFiles('readme')
		->from('fixtures.finder')
		->collect();

	$files = array_values($files);
	$ds = DIRECTORY_SEPARATOR;
	Assert::same('subdir', $files[0]->getRelativePath());
	Assert::same("subdir{$ds}readme", $files[0]->getRelativePathname());
});
