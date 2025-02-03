<?php

/**
 * Test: Nette\Utils\Arrays::wrap()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('wraps strings with specified prefix and suffix', function () {
	Assert::same([], Arrays::wrap([], '{', '}'));
	Assert::same(['STR'], Arrays::wrap(['STR']));
	Assert::same(['{STR'], Arrays::wrap(['STR'], '{'));
	Assert::same(['STR}'], Arrays::wrap(['STR'], '', '}'));
	Assert::same(['{STR}'], Arrays::wrap(['STR'], '{', '}'));
});


test('converts scalars to strings with error on array conversion', function () {
	$o = new class {
		public function __toString()
		{
			return 'toString';
		}
	};

	$cases = [
		[0, '0'],
		[1.1, '1.1'],
		[null, ''],
		[false, ''],
		[true, '1'],
		[$o, 'toString'],
	];
	Assert::same(array_column($cases, 1), Arrays::wrap(array_column($cases, 0)));

	Assert::error(
		fn() => Arrays::wrap([[]]),
		PHP_VERSION_ID < 80000 ? E_NOTICE : E_WARNING,
		'Array to string conversion',
	);
});
