<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Finder descentFilter.
 */

use Nette\Utils\FileInfo;
use Nette\Utils\Finder;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function export($iterator): array
{
	$arr = [];
	foreach ($iterator as $key => $value) {
		$arr[] = strtr($key, '\\', '/');
	}

	sort($arr);
	return $arr;
}


test('descentFilter prunes traversal into a directory', function () {
	$finder = Finder::findFiles('*')
		->from('fixtures.finder')
		->descentFilter(fn(FileInfo $file): bool => $file->getBasename() !== 'subdir');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images/logo.gif',
	], export($finder));
});


test('descentFilter does not hide the directory itself', function () {
	$finder = Finder::find('*')
		->from('fixtures.finder')
		->descentFilter(fn(FileInfo $file): bool => $file->getBasename() !== 'subdir');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images',
		'fixtures.finder/images/logo.gif',
		'fixtures.finder/subdir',
	], export($finder));
});


test('same closure as filter and descentFilter hides directory and contents', function () {
	// the pattern used by RobotLoader
	$filter = fn(FileInfo $file): bool => $file->getBasename() !== 'subdir';
	$finder = Finder::find('*')
		->from('fixtures.finder')
		->filter($filter)
		->descentFilter($filter);
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images',
		'fixtures.finder/images/logo.gif',
	], export($finder));
});


test('descentFilter has no effect on non-recursive search', function () {
	$finder = Finder::find('*')
		->in('fixtures.finder')
		->descentFilter(fn(FileInfo $file): bool => false);
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/images',
		'fixtures.finder/subdir',
	], export($finder));
});
