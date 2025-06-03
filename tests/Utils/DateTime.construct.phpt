<?php

/**
 * Test: Nette\Utils\DateTime constructor __construct() method.
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

date_default_timezone_set('Europe/Prague');


test('Absolute date/time strings', function () {
	$dt = new DateTime('2024-08-20 14:30:00');
	Assert::same('2024-08-20 14:30:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'));

	// Absolute format that might look relative
	$dt = new DateTime('2 january 2005');
	Assert::same('2005-01-02 00:00:00 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'));
});


test('now', function () {
	$now = new DateTimeImmutable('now');

	$dt = new DateTime('');
	Assert::true(abs($dt->getTimestamp() - $now->getTimestamp()) <= 1);

	$dt = new DateTime('now');
	Assert::true(abs($dt->getTimestamp() - $now->getTimestamp()) <= 1);
});


test('Numeric relative strings (should use corrected modify logic)', function () {
	$nowTs = time();

	$dt = new DateTime('+1 hour');
	// Expect time approximately one hour later than $nowTs
	Assert::true(abs($dt->getTimestamp() - ($nowTs + 3600)) <= 1);

	$dt = new DateTime('- 2 days');
	// Allow slightly larger tolerance due to potential DST changes within the 2 days
	Assert::true(abs($dt->getTimestamp() - ($nowTs - 2 * 86400)) <= 2);

	$dt = new DateTime(' +10 minutes '); // With spaces
	Assert::true(abs($dt->getTimestamp() - ($nowTs + 600)) <= 1);
});


test('Textual relative strings', function () {
	$dt = new DateTime('yesterday');
	$yesterdayRef = new DateTimeImmutable('yesterday');
	Assert::same($yesterdayRef->format('Y-m-d'), $dt->format('Y-m-d'));

	$dt = new DateTime('next monday');
	$nextMondayRef = new DateTimeImmutable('next monday');
	Assert::same($nextMondayRef->format('Y-m-d'), $dt->format('Y-m-d'));

	$dt = new DateTime('first day of next month');
	$firstNextRef = new DateTimeImmutable('first day of next month');
	Assert::same($firstNextRef->format('Y-m-d H:i:s'), $dt->format('Y-m-d H:i:s'));
});


test('Timezone handling', function () {
	$defaultTz = (new DateTime)->getTimezone();
	$utcTz = new DateTimeZone('UTC');

	// 1. No timezone provided -> should use default
	$dt = new DateTime('2024-09-01 10:00:00');
	Assert::same($defaultTz->getName(), $dt->getTimezone()->getName(), 'Uses default timezone when null');

	// 2. Explicit timezone provided -> should use provided
	$dt = new DateTime('2024-09-01 10:00:00', $utcTz);
	Assert::same($utcTz->getName(), $dt->getTimezone()->getName(), 'Uses provided timezone (UTC)');
	Assert::same('2024-09-01 10:00:00 UTC (+00:00)', $dt->format('Y-m-d H:i:s T (P)'));

	// 3. Relative string, no timezone -> should use default
	$dt = new DateTime('+3 hours');
	Assert::same($defaultTz->getName(), $dt->getTimezone()->getName(), 'Relative string uses default timezone when null');

	// 4. Relative string, explicit timezone -> should use provided
	$dt = new DateTime('+3 hours', $utcTz);
	Assert::same($utcTz->getName(), $dt->getTimezone()->getName(), 'Relative string uses provided timezone (UTC)');

	// 5. Absolute string (date only), explicit timezone -> should use provided
	$dt = new DateTime('2024-11-11', $utcTz);
	Assert::same($utcTz->getName(), $dt->getTimezone()->getName(), 'Absolute date string uses provided timezone (UTC)');
	Assert::same('2024-11-11 00:00:00 UTC (+00:00)', $dt->format('Y-m-d H:i:s T (P)'));
});


test('Exception handling for invalid input', function () {
	Assert::exception(
		fn() => new DateTime('invalid date format'),
		Throwable::class,
		'%a%invalid date format%a%',
	);

	Assert::error(
		fn() => new DateTime('0000-00-00'),
		E_USER_WARNING,
		"Nette\\Utils\\DateTime: The parsed date was invalid '0000-00-00'",
	);

	Assert::error(
		fn() => new DateTime('2024-02-31 10:00:00'), // Invalid day for February
		E_USER_WARNING,
		"Nette\\Utils\\DateTime: The parsed date was invalid '2024-02-31 10:00:00'",
	);

	Assert::error(
		fn() => new DateTime('1978-01-23 23:00:60'),
		E_USER_WARNING,
		"Nette\\Utils\\DateTime: The parsed time was invalid '1978-01-23 23:00:60'",
	);
});
