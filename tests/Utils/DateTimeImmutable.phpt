<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\DateTimeImmutable
 */

use Nette\Utils\DateTimeImmutable;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

date_default_timezone_set('Europe/Prague');


test('modify() returns a new instance and leaves the original untouched', function () {
	$a = new DateTimeImmutable('2024-01-15 10:00:00');
	$b = $a->modify('+1 day');

	Assert::type(DateTimeImmutable::class, $b);
	Assert::notSame($a, $b);
	Assert::same('2024-01-15 10:00:00', (string) $a);
	Assert::same('2024-01-16 10:00:00', (string) $b);
	Assert::type(DateTimeImmutable::class, $a);
	Assert::type(DateTimeImmutable::class, $a);
});


test('modify() throws on invalid modifier', function () {
	Assert::exception(
		fn() => (new DateTimeImmutable('2024-01-15'))->modify('gibberish'),
		Throwable::class,
		'%a%gibberish%a%',
	);
});


test('inherited modification methods preserve the subclass', function () {
	$dt = new DateTimeImmutable('2024-01-15 10:00:00');
	Assert::type(DateTimeImmutable::class, $dt->add(new DateInterval('P1D')));
	Assert::type(DateTimeImmutable::class, $dt->sub(new DateInterval('P1D')));
	Assert::type(DateTimeImmutable::class, $dt->setTimezone(new DateTimeZone('UTC')));
	Assert::type(DateTimeImmutable::class, $dt->setTimestamp(0));
});


test('relative sub-day parts are applied in UTC, so they are DST-safe', function () {
	// 01:50 +20 minutes crosses the spring-forward gap (02:00 -> 03:00), exactly 20 real minutes elapse
	$dt = new DateTimeImmutable('2024-03-31 01:50:00 +20 minutes', new DateTimeZone('Europe/Prague'));
	Assert::same('2024-03-31 03:10:00 +02:00', $dt->format('Y-m-d H:i:s P'));

	$dt = (new DateTimeImmutable('2024-03-31 01:50:00', new DateTimeZone('Europe/Prague')))->modify('+20 minutes');
	Assert::same('2024-03-31 03:10:00 +02:00', $dt->format('Y-m-d H:i:s P'));
});


test('setDate()/setTime() return a new instance and validate strictly', function () {
	$a = new DateTimeImmutable('2024-01-15 10:00:00');
	$b = $a->setDate(2030, 6, 1)->setTime(8, 15, 30);

	Assert::same('2024-01-15 10:00:00', (string) $a);
	Assert::same('2030-06-01 08:15:30', (string) $b);

	Assert::exception(
		fn() => $a->setDate(2024, 2, 30),
		Throwable::class,
		'The date 2024-02-30 is not valid.',
	);
	Assert::exception(
		fn() => $a->setTime(23, 0, 60),
		Throwable::class,
		'The time 23:00:60.00000 is not valid.',
	);
});


test('from() converts timestamps literally, without the relative heuristic', function () {
	Assert::same('1970-01-01 01:01:00', DateTimeImmutable::from(60)->format('Y-m-d H:i:s'));
	Assert::same('1978-01-23 11:40:00', (string) DateTimeImmutable::from(254_400_000));
	Assert::same(254_400_000, DateTimeImmutable::from(254_400_000)->getTimestamp());
});


test('from() accepts strings, null and other DateTimeInterface objects', function () {
	Assert::same('1978-05-05 00:00:00', (string) DateTimeImmutable::from('1978-05-05'));
	Assert::same((new DateTimeImmutable)->format('Y-m-d H:i:s'), (string) DateTimeImmutable::from(null));

	Assert::type(DateTimeImmutable::class, DateTimeImmutable::from(new DateTime('1978-05-05')));
	Assert::same(
		'1978-05-05 12:00:00.123450',
		DateTimeImmutable::from(new DateTime('1978-05-05 12:00:00.12345'))->format('Y-m-d H:i:s.u'),
	);
});


test('fromParts() builds a date and validates it', function () {
	Assert::same('2024-08-20 14:30:00', (string) DateTimeImmutable::fromParts(2024, 8, 20, 14, 30));
	Assert::same('2024-08-20 14:30:15.500000', DateTimeImmutable::fromParts(2024, 8, 20, 14, 30, 15.5)->format('Y-m-d H:i:s.u'));

	Assert::exception(
		fn() => DateTimeImmutable::fromParts(2024, 2, 30),
		Throwable::class,
		'The date 2024-02-30 is not valid.',
	);
});


test('constructor parses absolute and relative parts', function () {
	Assert::same('2005-01-02 00:00:00', (string) new DateTimeImmutable('2 january 2005'));

	$now = new DateTimeImmutable('now');
	Assert::true(abs((new DateTimeImmutable(''))->getTimestamp() - $now->getTimestamp()) <= 1);

	$nowTs = time();
	Assert::true(abs((new DateTimeImmutable('+1 hour'))->getTimestamp() - ($nowTs + 3600)) <= 1);
	Assert::true(abs((new DateTimeImmutable(' +10 minutes '))->getTimestamp() - ($nowTs + 600)) <= 1);
});


test('constructor honors the timezone for both absolute and relative input', function () {
	$utc = new DateTimeZone('UTC');

	$dt = new DateTimeImmutable('2024-09-01 10:00:00', $utc);
	Assert::same('UTC', $dt->getTimezone()->getName());
	Assert::same('2024-09-01 10:00:00 UTC (+00:00)', $dt->format('Y-m-d H:i:s T (P)'));

	$dt = new DateTimeImmutable('+3 hours', $utc);
	Assert::same('UTC', $dt->getTimezone()->getName());
});


test('constructor throws on invalid input', function () {
	Assert::exception(
		fn() => new DateTimeImmutable('invalid date format'),
		Throwable::class,
		'%a%invalid date format%a%',
	);
	Assert::exception(
		fn() => new DateTimeImmutable('2024-02-31 10:00:00'),
		Throwable::class,
		"The parsed date was invalid '2024-02-31 10:00:00'",
	);
	Assert::exception(
		fn() => new DateTimeImmutable('1978-01-23 23:00:60'),
		Throwable::class,
		"The parsed time was invalid '1978-01-23 23:00:60'",
	);
});


test('createFromFormat() returns the Nette subclass and accepts a string timezone', function () {
	$dt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-08-20 14:30:00', 'UTC');
	Assert::type(DateTimeImmutable::class, $dt);
	Assert::same('2024-08-20 14:30:00', (string) $dt);
	Assert::same('UTC', $dt->getTimezone()->getName());

	Assert::false(DateTimeImmutable::createFromFormat('Y-m-d', 'nonsense'));
});


test('JSON serialization uses ISO 8601 and __toString uses Y-m-d H:i:s', function () {
	$dt = new DateTimeImmutable('2024-08-20 14:30:00');
	Assert::same('"2024-08-20T14:30:00+02:00"', json_encode($dt));
	Assert::same('2024-08-20 14:30:00', (string) $dt);
});
