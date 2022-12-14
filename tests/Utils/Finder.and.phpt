<?php

/**
 * Test: Nette\Utils\Finder multiple batches.
 */

declare(strict_types=1);

use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function export($iterator)
{
	$arr = [];
	foreach ($iterator as $key => $value) {
		$arr[] = strtr($key, '\\', '/');
	}

	sort($arr);
	return $arr;
}


test('and', function () {
	$finder = Finder::findFiles('file.txt')
		->in('fixtures.finder')
		->and()
		->directories('subdir*')->from('fixtures.finder')
		->and()
		->files('file.txt')->from('fixtures.finder/*/subdir*');

	Assert::same([
		'fixtures.finder/file.txt',
		'fixtures.finder/subdir',
		'fixtures.finder/subdir/subdir2',
		'fixtures.finder/subdir/subdir2/file.txt',
	], export($finder));
});
