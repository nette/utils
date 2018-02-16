<?php

/**
 * Test: Nette\Utils\Callback::invoke() and invokeArgs()
 */

declare(strict_types=1);

use Nette\Utils\Callback;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test
{
	public function fun($a)
	{
		return __METHOD__ . $a;
	}


	public function ref(&$a)
	{
		$a = __METHOD__;
		return $a;
	}
}


$cb = [new Test, 'fun'];
Assert::same('Test::fun*', @Callback::invoke($cb, '*')); // is deprecated
Assert::same('Test::fun*', @Callback::invokeArgs($cb, ['*'])); // is deprecated


$cb = [new Test, 'ref'];
Assert::same('Test::ref', @Callback::invokeArgs($cb, [&$ref])); // is deprecated
Assert::same('Test::ref', $ref);


Assert::exception(function () {
	@Callback::invoke('undefined'); // is deprecated
}, Nette\InvalidArgumentException::class, "Callback 'undefined' is not callable.");


Assert::exception(function () {
	@Callback::invokeArgs('undefined'); // is deprecated
}, Nette\InvalidArgumentException::class, "Callback 'undefined' is not callable.");
