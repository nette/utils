<?php

/**
 * Test: Nette\Utils\Callback::invokeAll()
 */

declare(strict_types=1);

use Nette\Utils\Callback;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test
{
	public function fn1(...$args)
	{
		return __METHOD__ . ' ' . implode(',', $args);
	}


	public function fn2(...$args)
	{
		return __METHOD__ . ' ' . implode(',', $args);
	}
}


$list = [];
$list[] = [new Test, 'fn1'];
$list[] = [new Test, 'fn2'];

Assert::same(
	['Test::fn1 a,b', 'Test::fn2 a,b'],
	Callback::invokeAll($list, 'a', 'b')
);
