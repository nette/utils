<?php

declare(strict_types=1);

use Nette\Utils\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::false(Helpers::compare(1, '>', 1));
Assert::false(Helpers::compare(1, '>', 2));
Assert::false(Helpers::compare(1, '<', 1));
Assert::true(Helpers::compare(1, '<', 2));

Assert::true(Helpers::compare(1, '>=', 1));
Assert::false(Helpers::compare(1, '>=', 2));
Assert::true(Helpers::compare(1, '<=', 1));
Assert::true(Helpers::compare(1, '<=', 2));

Assert::true(Helpers::compare(1, '=', 1));
Assert::true(Helpers::compare(1.0, '=', 1));
Assert::false(Helpers::compare(1, '=', 2));

Assert::true(Helpers::compare(1, '==', 1));
Assert::true(Helpers::compare(1.0, '==', 1));
Assert::false(Helpers::compare(1, '==', 2));

Assert::true(Helpers::compare(1, '===', 1));
Assert::false(Helpers::compare(1.0, '===', 1));
Assert::false(Helpers::compare(1, '===', 2));

Assert::false(Helpers::compare(1, '<>', 1));
Assert::false(Helpers::compare(1.0, '<>', 1));
Assert::true(Helpers::compare(1, '<>', 2));

Assert::false(Helpers::compare(1, '!=', 1));
Assert::false(Helpers::compare(1.0, '!=', 1));
Assert::true(Helpers::compare(1, '!=', 2));

Assert::false(Helpers::compare(1, '!==', 1));
Assert::true(Helpers::compare(1.0, '!==', 1));
Assert::true(Helpers::compare(1, '!==', 2));

Assert::exception(
	fn() => Helpers::compare(1, 'x', 1),
	Nette\InvalidArgumentException::class,
	"Unknown operator 'x'",
);
