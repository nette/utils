<?php

/**
 * Test: Nette\Utils\DateTime::getIntlDateFormat().
 */

declare(strict_types=1);

use Nette\Utils\DateTime;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('Europe/Prague');

Assert::same(DateTime::getIntlDateFormat(
	$format = 'LLLL YYYY HH:mm:ss',
	$time = DateTime('2021-02-01 15:47:21'),
	$locale = 'cs_CZ',
	$timeZone = 'Europe/Prague'
), 'únor 2021 15:47:21');
