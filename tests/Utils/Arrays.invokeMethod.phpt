<?php

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test1
{
	public function fn(...$args)
	{
		return static::class . ' ' . implode(',', $args);
	}
}

class Test2 extends Test1
{
}


$list = [new Test1, 'key' => new Test2];

Assert::same(
	['Test1 a,b', 'key' => 'Test2 a,b'],
	Arrays::invokeMethod($list, 'fn', 'a', 'b'),
);

Assert::same(
	['Test1 a,b', 'key' => 'Test2 a,b'],
	Arrays::invokeMethod(new ArrayIterator($list), 'fn', 'a', 'b'),
);
