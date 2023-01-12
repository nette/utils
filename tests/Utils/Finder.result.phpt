<?php

/**
 * Test: Nette\Utils\Finder result test.
 */

declare(strict_types=1);

use Nette\Utils\Finder;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


// check key => value pair
$finder = Finder::findFiles(basename(__FILE__))->in(__DIR__);

$arr = iterator_to_array($finder);
Assert::same(1, count($arr));
Assert::true(isset($arr[__FILE__]));
Assert::type('SplFileInfo', $arr[__FILE__]);
Assert::same(__FILE__, (string) $arr[__FILE__]);


// missing in() & from()
$finder = Finder::findFiles('*');

Assert::exception(
	fn() => $finder->getIterator(),
	Nette\InvalidStateException::class,
	'Call in() or from() to specify directory to search.',
);
