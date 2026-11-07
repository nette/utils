<?php declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

use Nette\PHPStan\Tester\TypeAssert;

TypeAssert::assertTypes(__DIR__ . '/utils-types.php');

TypeAssert::assertErrors(
	__DIR__ . '/immediately-invoked-callables.php',
	array_map(
		fn(int $line) => "missingType.checkedException on line $line",
		[13, 14, 20, 21, 27, 33, 39, 45, 51, 57, 64, 75, 76, 82, 83, 89, 95, 101, 107, 113, 119],
	),
	[__DIR__ . '/immediately-invoked-callables.neon'],
);
