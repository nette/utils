<?php

/**
 * Test: Nette\Utils\Finder basic usage.
 */

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function export($iterator, bool $sort = true)
{
	$arr = [];
	foreach ($iterator as $key => $value) {
		$arr[] = FileSystem::unixSlashes($key);
	}

	if ($sort) {
		sort($arr);
	}
	return $arr;
}


test('expty search', function () {
	$finder = (new Finder)->in('fixtures.finder');
	Assert::same([], export($finder));

	$finder = (new Finder)->from('fixtures.finder');
	Assert::same([], export($finder));

	Assert::exception(
		fn() => Finder::findFiles(''),
		Nette\InvalidArgumentException::class,
	);
});


test('current dir', function () {
	$finder = Finder::findFiles('fixtures.finder/*.txt');
	Assert::same(['fixtures.finder/file.txt'], export($finder));
});


test('non-recursive file search', function () {
	$finder = Finder::findFiles('file.txt')->in('fixtures.finder');
	Assert::same(['fixtures.finder/file.txt'], export($finder));
});


test('recursive file search', function () {
	$finder = Finder::findFiles('file.txt')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('recursive file search with depth limit', function () {
	$finder = Finder::findFiles('file.txt')->from('fixtures.finder')->limitDepth(1);
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
	], export($finder));
});


test('non-recursive file & directory search', function () {
	$finder = Finder::find('file.txt')->in('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
	], export($finder));
});


test('recursive file & directory search', function () {
	$finder = Finder::find('file.txt')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});


test('recursive file & directory search in child-first order', function () {
	$finder = Finder::find('subdir*')->from('fixtures.finder')->childFirst();
	Assert::same([
		'fixtures.finder/subdir/subdir2',
		'fixtures.finder/subdir',
	], export($finder, false));
});


test('recursive file & directory search excluding folders', function () {
	$finder = Finder::find('file.txt')->from('fixtures.finder')->exclude('images')->exclude('subdir2');
	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir/file.txt',
	], export($finder));
});


test('non-recursive directory search', function () {
	$finder = Finder::findDirectories('subdir*')->in('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir',
	], export($finder));
});


test('recursive directory search', function () {
	$finder = Finder::findDirectories('subdir*')->from('fixtures.finder');
	Assert::same([
		'fixtures.finder/subdir',
		'fixtures.finder/subdir/subdir2',
	], export($finder));
});


test('absolute path', function () {
	$finder = Finder::find('fixtures.finder/im*')->in(__DIR__);
	Assert::same([
		FileSystem::unixSlashes(__DIR__) . '/fixtures.finder/images',
	], export($finder));
});


test('absolute path in mask', function () { // will not work if there are characters [] in the path!!!
	$finder = Finder::findDirectories(__DIR__);
	Assert::same([
		FileSystem::unixSlashes(__DIR__),
	], export($finder));
});
