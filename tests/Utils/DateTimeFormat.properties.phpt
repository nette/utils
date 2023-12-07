<?php

/**
 * Test: custom properties of Nette\Utils\DateTime.
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

Assert::same(1978, DateTime::from('1978-01-23 11:40:00.86')->year);
Assert::same(1, DateTime::from('1978-01-23 11:40:00.86')->month);
Assert::same(23, DateTime::from('1978-01-23 11:40:00.86')->day);
Assert::same(11, DateTime::from('1978-01-23 11:40:00.86')->hour);
Assert::same(40, DateTime::from('1978-01-23 11:40:00.86')->minute);
Assert::same(0, DateTime::from('1978-01-23 11:40:00.86')->second);
Assert::same(863500, DateTime::from('1978-01-23 11:40:00.8635')->microsecond);
Assert::same(863, DateTime::from('1978-01-23 11:40:00.8635')->millisecond);
Assert::same('1978-01-23', DateTime::from('1978-01-23 11:40:00.86')->date);
Assert::same('1978-01-23 11:40:00', DateTime::from('1978-01-23 11:40:00.86')->dateTime);
Assert::same('1978-01-23 11:40:00+0100', DateTime::from('1978-01-23 11:40:00.86')->dateTimeTz);
Assert::same('1978-01-23 11:40:00.86', DateTime::from('1978-01-23 11:40:00.860000')->dateTimeMicro);
Assert::same('1978-01-23 11:40:00.0', DateTime::from('1978-01-23 11:40:00.000000')->dateTimeMicro);
Assert::same('1978-01-23 11:40:00.86+0100', DateTime::from('1978-01-23 11:40:00.860000')->dateTimeMicroTz);
Assert::same(254_400_000.86, DateTime::from('1978-01-23 11:40:00.860000')->timestampMicro);
Assert::same(254_400_000, DateTime::from('1978-01-23 11:40:00.860000')->timestamp);
Assert::same(1, DateTime::from('1978-01-23 11:40:00.860000')->dayOfWeek);

/**
 * reverse engineering does not work, it is reason why keep zero for microsecond
 * @see \Nette\Utils\DateTimeFormat::getDateTimeMicro()
 */
Assert::type(DateTime::class, DateTime::createFromFormat('Y-m-d H:i:s.u', '1978-01-23 11:40:00.0'));
Assert::false(DateTime::createFromFormat('Y-m-d H:i:s.u', '1978-01-23 11:40:00.'));
Assert::false(DateTime::createFromFormat('Y-m-d H:i:s.u', '1978-01-23 11:40:00'));
