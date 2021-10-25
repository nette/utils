<?php

/**
 * Test: Nette\Utils\Strings::replace()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test
{
	public static function cb()
	{
		return '@';
	}
}

Assert::same('hello world!', Strings::replace('hello world!', '#([E-L])+#', '#'));
Assert::same('#o wor#d!', Strings::replace('hello world!', ['#([e-l])+#'], '#'));
Assert::same('#o wor#d!', Strings::replace('hello world!', '#([e-l])+#', '#'));
Assert::same('@o wor@d!', Strings::replace('hello world!', '#[e-l]+#', fn() => '@'));
Assert::same('@o wor@d!', Strings::replace('hello world!', '#[e-l]+#', Closure::fromCallable('Test::cb')));
Assert::same('@o wor@d!', Strings::replace('hello world!', ['#[e-l]+#'], Closure::fromCallable('Test::cb')));
Assert::same('@o wor@d!', Strings::replace('hello world!', '#[e-l]+#', ['Test', 'cb']));
Assert::same('#@ @@@#d!', Strings::replace('hello world!', [
	'#([e-l])+#' => '#',
	'#[o-w]#' => '@',
]));
Assert::same(' !', Strings::replace('hello world!', '#\w#'));
Assert::same(' !', Strings::replace('hello world!', ['#\w#']));

// flags & callback
Assert::same('hell0o worl9d!', Strings::replace('hello world!', '#[e-l]+#', fn($m) => implode('', $m[0]), captureOffset: true));
Strings::replace('hello world!', '#e(x)*#', fn($m) => Assert::null($m[1]), unmatchedAsNull: true);
