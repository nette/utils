<?php

/**
 * Test: Nette\Utils\DateTime modify() method.
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

date_default_timezone_set('Europe/Prague');


test('Basic operations (no DST involved)', function () {
	$base = new DateTime('2024-07-15 10:00:00'); // Summer time (CEST)

	$dt = clone $base;
	$dt->modify('+30 minutes');
	Assert::same('2024-07-15 10:30:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), '+30 minutes');

	$dt = clone $base;
	$dt->modify('+2 hours');
	Assert::same('2024-07-15 12:00:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), '+2 hours');

	$dt = clone $base;
	$dt->modify('-5 days');
	Assert::same('2024-07-10 10:00:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), '-5 days');

	$dt = new DateTime('2024-01-15 10:00:00'); // Winter time (CET)
	$dt->modify('+1 month');
	Assert::same('2024-02-15 10:00:00 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'), '+1 month in winter');
});


test('Spring DST transition (2025-03-30 02:00 -> 03:00)', function () {
	$startSpring = new DateTime('2025-03-30 01:45:00'); // Before the jump (CET +01:00)

	// Modification ending BEFORE the jump
	$dt = clone $startSpring;
	$dt->modify('+10 minutes');
	Assert::same('2025-03-30 01:55:00 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'), '+10 min (ends before jump)');

	// Modification crossing the jump (duration logic thanks to Nette fix)
	$dt = clone $startSpring;
	$dt->modify('+30 minutes'); // 01:45 CET + 30 min duration = 01:15 UTC = 03:15 CEST
	Assert::same('2025-03-30 03:15:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), '+30 min (crosses jump)');

	$dt = clone $startSpring;
	$dt->modify('+90 minutes'); // 01:45 CET + 90 min duration = 02:15 UTC = 04:15 CEST (Key test!)
	Assert::same('2025-03-30 04:15:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), '+90 min (crosses jump)');

	// Adding a day across the jump (day has only 23 hours)
	$dt = clone $startSpring;
	$dt->modify('+1 day');
	Assert::same('2025-03-31 01:45:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), '+1 day');

	// Combination of day + hours across the jump
	$dt = clone $startSpring;
	$dt->modify('+1 day +1 hour');
	Assert::same('2025-03-31 02:45:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), '+1 day + 1 hour');

	$dt = clone $startSpring;
	$dt->modify('+2 hours'); // 01:45 CET + 2h duration = 02:45 UTC = 04:45 CEST
	Assert::same('2025-03-30 04:45:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), '+2 hours (crosses jump)');
});


test('Autumn DST transition (2024-10-27 03:00 -> 02:00)', function () {
	$startAutumn = new DateTime('2024-10-27 01:45:00'); // Before the fallback (CEST +02:00)

	// Modification ending BEFORE the fallback (still CEST)
	$dt = clone $startAutumn;
	$dt->modify('+30 minutes');
	Assert::same('2024-10-27 02:15:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), '+30 min (ends before fallback)');

	// Modification crossing the fallback (lands in the second 2:xx hour - CET)
	$dt = clone $startAutumn;
	$dt->modify('+90 minutes'); // 01:45 CEST + 90 min duration = 01:15 UTC = 02:15 CET
	Assert::same('2024-10-27 02:15:00 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'), '+90 min (crosses fallback, lands in CET)');

	$dt = clone $startAutumn;
	$dt->modify('+1 hour + 30 minutes'); // Same as +90 minutes
	Assert::same('2024-10-27 02:15:00 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'), '+1 hour + 30 minutes (crosses fallback)');

	// Adding a day across the fallback (day has 25 hours)
	$dt = clone $startAutumn;
	$dt->modify('+1 day');
	Assert::same('2024-10-28 01:45:00 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'), '+1 day');

	// Combination of day + hours across the fallback
	$dt = clone $startAutumn;
	$dt->modify('+1 day +2 hours');
	Assert::same('2024-10-28 03:45:00 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'), '+1 day + 2 hours');
});


test('Complex and varied format strings', function () {
	$dt = new DateTime('2024-04-10 12:00:00'); // CEST
	// Expected: -2m -> 2024-02-10 12:00 CET | +7d -> 2024-02-17 12:00 CET | +23h 59m 59s -> 2024-02-18 11:59:59 CET
	$dt->modify('- 2 months +7 days +23 hours +59 minutes +59 seconds');
	Assert::same('2024-02-18 11:59:59 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Complex mixed modification 1');

	$dt = new DateTime('2024-01-10 15:00:00'); // CET
	$dt->modify('  2days '); // Spaces and format variation
	Assert::same('2024-01-12 15:00:00 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Format "  2days "');

	// Textual relative modifier
	$dt = new DateTime('2024-04-10 12:00:00'); // CEST
	$dt->modify('first day of next month noon');
	Assert::same('2024-05-01 12:00:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Textual relative modifier');

	// Complex mixed modification 2 (year/month/day/hour/min)
	$dt = new DateTime('2023-11-20 08:30:00'); // CET
	// +1y -> 2024-11-20 08:30 CET | -3m -> 2024-08-20 08:30 CEST | +10d -> 2024-08-30 08:30 CEST | +5h -> 2024-08-30 13:30 CEST | -15min -> 2024-08-30 13:15 CEST
	$dt->modify('+1 year -3 months + 10 days + 5 hours - 15 minutes');
	Assert::same('2024-08-30 13:15:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Complex mixed modification 2');

	// Extra spaces and singular unit
	$dt = new DateTime('2024-05-15 10:00:00'); // CEST
	$dt->modify('+ 2 days   - 1hour'); // Extra spaces, 'hour' singular
	Assert::same('2024-05-17 09:00:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Extra spaces and singular unit');

	// Seconds and milliseconds
	$dt = new DateTime('2024-06-01 12:00:00.000000'); // CEST, explicit microseconds
	$dt->modify('+3 sec - 500 milliseconds');
	Assert::same('2024-06-01 12:00:02.500000', $dt->format('Y-m-d H:i:s.u'), 'Seconds and milliseconds');
	Assert::same('CEST (+02:00)', $dt->format('T (P)'), 'Timezone check for ms test');

	// Textual day + numeric hour
	$dt = new DateTime('2024-06-15 09:00:00'); // CEST (Saturday)
	// 'next sunday' -> 2024-06-16 00:00:00, '+ 4 hours' -> 2024-06-16 04:00:00
	$dt->modify('next sunday + 4 hours');
	Assert::same('2024-06-16 04:00:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Textual day + numeric hour');

	// Textual time + numeric minute
	$dt = new DateTime('2024-06-15 09:00:00'); // CEST
	// 'noon' -> 12:00:00, '- 30 minutes' -> 11:30:00
	$dt->modify('noon - 30 minutes');
	Assert::same('2024-06-15 11:30:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Textual time + numeric minute');

	// Zero value modifiers
	$dt = new DateTime('2024-05-05 05:05:05'); // CEST
	$dt->modify('+0 days - 0 hours + 0 seconds');
	Assert::same('2024-05-05 05:05:05 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Zero value modifiers');

	// Microsecond addition
	$dt = new DateTime('2024-07-01 10:20:30.123456'); // CEST
	$dt->modify('+ 100 usecs');
	Assert::same('2024-07-01 10:20:30.123556', $dt->format('Y-m-d H:i:s.u'), 'Microsecond addition');
	Assert::same('CEST (+02:00)', $dt->format('T (P)'), 'Timezone check for usec test');

	// Chained textual modifiers
	$dt = new DateTime('2024-03-10 10:00:00'); // CET
	// 'first day of may' -> 2024-05-01 00:00 | 'noon' -> 2024-05-01 12:00
	$dt->modify('first day of may 2024 noon');
	Assert::same('2024-05-01 12:00:00 CEST (+02:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Chained textual modifiers');

	// ago
	$dt = new DateTime('2024-03-10 10:00:00'); // CET
	$dt->modify('12 minutes ago');
	Assert::same('2024-03-10 09:48:00 CET (+01:00)', $dt->format('Y-m-d H:i:s T (P)'), 'Ago modifier');
});


test('Invalid modifier format exceptions', function () {
	if (PHP_VERSION_ID < 80300) {
		Assert::error(
			fn() => (new DateTime)->modify('+'),
			E_WARNING,
			'DateTime::modify(): Failed to parse time string (+) at position 0 (+): Unexpected character',
		);
	} else {
		Assert::error(
			fn() => (new DateTime)->modify('+'),
			DateMalformedStringException::class,
			'DateTime::modify(): Failed to parse time string (+) at position 0 (+): Unexpected character',
		);
	}

	Assert::error(
		fn() => (new DateTime)->modify('2024-02-31 10:00:00'), // Invalid day for February
		E_USER_WARNING,
		"Nette\\Utils\\DateTime: The parsed date was invalid '2024-02-31 10:00:00'",
	);
});
