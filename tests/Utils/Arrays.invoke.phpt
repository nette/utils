<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
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
$list['key'] = [new Test, 'fn2'];

Assert::same(
	['Test::fn1 a,b', 'key' => 'Test::fn2 a,b'],
	Arrays::invoke($list, 'a', 'b'),
);

Assert::same(
	['Test::fn1 a,b', 'key' => 'Test::fn2 a,b'],
	Arrays::invoke(new ArrayIterator($list), 'a', 'b'),
);
