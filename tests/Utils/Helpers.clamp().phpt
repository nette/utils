<?php

declare(strict_types=1);

use Nette\Utils\Helpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(20, Helpers::clamp(20, 10, 30));
Assert::same(21, Helpers::clamp(20, 21, 30));
Assert::same(19, Helpers::clamp(20, 10, 19));
Assert::same(19.0, Helpers::clamp(20.0, 10.0, 19.0));

Assert::exception(
	fn() => Helpers::clamp(20, 30, 10),
	InvalidArgumentException::class,
	'Minimum (30) is not less than maximum (10).',
);
