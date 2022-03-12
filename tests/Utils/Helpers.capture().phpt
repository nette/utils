<?php

declare(strict_types=1);

use Nette\Utils\Helpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Helpers::capture(function () {}));
Assert::same('hello', Helpers::capture(function () { echo 'hello'; }));



$level = ob_get_level();

Assert::exception(
	fn() => Helpers::capture(fn() => undefined()),
	Error::class,
	'Call to undefined function undefined()',
);

Assert::same($level, ob_get_level());
