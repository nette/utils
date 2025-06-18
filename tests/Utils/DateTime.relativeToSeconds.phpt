<?php

/**
 * Test: Nette\Utils\DateTime::relativeToSeconds()
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

date_default_timezone_set('Europe/Prague');


test('relativeToSeconds basic usage', function () {
	Assert::same(60, DateTime::relativeToSeconds('1 minute'));
	Assert::same(600, DateTime::relativeToSeconds('10 minutes'));
	Assert::same(-60, DateTime::relativeToSeconds('-1 minute'));
	Assert::same(3600, DateTime::relativeToSeconds('+1 hour'));
	Assert::same(0, DateTime::relativeToSeconds('now'));
});

test('relativeToSeconds throws on invalid', function () {
	Assert::exception(
		fn() => DateTime::relativeToSeconds('nonsense'),
		Throwable::class,
		'%a?%Failed to parse time string %a%',
	);

	Assert::error(
		fn() => DateTime::relativeToSeconds('1 minu'),
		E_USER_WARNING,
	);
});
