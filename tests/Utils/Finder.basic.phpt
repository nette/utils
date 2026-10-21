<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Finder basic usage.
 */

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


// Ensures that the fixtures symlinks exist. On Windows without core.symlinks
// Git checks them out as plain text files containing the target path, so we
// recreate them as real symlinks. Skips the test if symlinks can't be created.
function setupSymlinks(): void
{
	$links = [
		__DIR__ . '/fixtures.finder3/subdir/file.txt' => '../file.txt',
		__DIR__ . '/fixtures.finder3/another_subdir/subdir' => '../subdir',
	];
	$todo = array_filter($links, fn($link) => !is_link($link), ARRAY_FILTER_USE_KEY);
	if (!$todo) {
		return;
	}

	// verify symlinks can be created before deleting anything
	$probe = __DIR__ . '/fixtures.finder3/__symlink_probe';
	@unlink($probe);
	if (!@symlink('.', $probe)) {
		Tester\Environment::skip('Unable to create symlinks.');
	}
	@unlink($probe);

	foreach ($todo as $link => $target) {
		@unlink($link);
		symlink($target, $link);
	}
}


test('empty search', function () {
	$finder = (new Finder)->in('fixtures.finder');
	Assert::same([], export($finder));

	$finder = (new Finder)->from('fixtures.finder');
	Assert::same([], export($finder));

	Assert::exception(
		fn() => Finder::findFiles(''),
		Nette\InvalidArgumentException::class,
	);
});


test('default mask', function () {
	$finder = Finder::find()->in('fixtures.finder');
	Assert::same(['fixtures.finder/file.txt', 'fixtures.finder/images', 'fixtures.finder/subdir'], export($finder));

	$finder = Finder::findFiles()->in('fixtures.finder');
	Assert::same(['fixtures.finder/file.txt'], export($finder));

	$finder = Finder::findDirectories()->in('fixtures.finder');
	Assert::same(['fixtures.finder/images', 'fixtures.finder/subdir'], export($finder));

	$finder = (new Finder)->files()->in('fixtures.finder');
	Assert::same(['fixtures.finder/file.txt'], export($finder));

	$finder = (new Finder)->directories()->in('fixtures.finder');
	Assert::same(['fixtures.finder/images', 'fixtures.finder/subdir'], export($finder));
});


test('current dir', function () {
	$finder = Finder::findFiles('fixtures.finder/*.txt');
	Assert::same(['fixtures.finder/file.txt'], export($finder));
});


test('non-recursive file search', function () {
	$finder = Finder::findFiles('file.txt')->in('fixtures.finder');
	Assert::same(['fixtures.finder/file.txt'], export($finder));
});


test('non-recursive file search alt', function () {
	$finder = (new Finder)->files('file.txt')->in('fixtures.finder');
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
	], export($finder, sort: false));
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


test('non-recursive directory search alt', function () {
	$finder = (new Finder)->directories('subdir*')->in('fixtures.finder');
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


test('symlink to file', function () {
	setupSymlinks();
	$finder = Finder::find('subdir/*.txt')->in('fixtures.finder3');
	Assert::same([
		'fixtures.finder3/subdir/file.txt',
	], export($finder));
});


test('symlink to directory', function () {
	setupSymlinks();
	$finder = Finder::findDirectories()->in('fixtures.finder3/another_subdir');
	Assert::same([
		'fixtures.finder3/another_subdir/subdir',
	], export($finder));
});


test('symlink to file in symlinked directory', function () {
	setupSymlinks();
	$finder = Finder::find('subdir/*.txt')->in('fixtures.finder3/another_subdir');
	Assert::same([
		'fixtures.finder3/another_subdir/subdir/file.txt',
	], export($finder));
});
