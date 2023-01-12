<?php

/**
 * Test: Nette\Utils\Finder basic usage.
 */

declare(strict_types=1);

use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function export($iterator, bool $sort = true)
{
	$arr = [];
	foreach ($iterator as $key => $value) {
		$arr[] = strtr($key, '\\', '/');
	}

	if ($sort) {
		sort($arr);
	}
	return $arr;
}


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


test('getRelativePathName', function () {
	$res = [];
	foreach (Finder::findFiles('file.txt')->from('fixtures.finder') as $foo) {
		$res[$foo->getRelativePathName()] = true;
	}

	Assert::same(
		['file.txt', 'subdir/file.txt', 'subdir/subdir2/file.txt'],
		export($res),
	);
});


test('empty args', function () {
	$finder = Finder::find()->in('fixtures.finder');
	Assert::same([], export($finder));

	$finder = Finder::findFiles()->in('fixtures.finder');
	Assert::same([], export($finder));

	$finder = Finder::findDirectories()->in('fixtures.finder');
	Assert::same([], export($finder));

	$finder = Finder::find()->exclude()->in('fixtures.finder');
	Assert::same([], export($finder));
});
