<?php

/**
 * Test: Nette\DateTime test.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 * @phpversion < 5.3
 */




require __DIR__ . '/../bootstrap.php';



date_default_timezone_set('Europe/Prague');

$obj = new Nette\DateTime('2010-01-01 12:00:00');
$ts = $obj->getTimestamp();
$obj->setTimestamp($ts);
Assert::same($ts, $obj->getTimestamp());

$obj->setTimezone(new DateTimeZone('Indian/Comoro'));
$obj->setTimestamp($ts);
Assert::same($ts, $obj->getTimestamp());

$obj->setTimezone(new DateTimeZone('UTC'));
Assert::same($ts, $obj->getTimestamp());
