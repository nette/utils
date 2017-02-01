<?php

declare(strict_types=1);

use Tester\Assert;
use Nette\Utils\DateTime;

require __DIR__ . '/../bootstrap.php';

Assert::noError(function () {
	$x = DateTime::from(254400000);
});

Assert::noError(function () {
	$x = DateTime::from(254400000);
	$x = $x->setTimestamp(254400000);
});

Assert::noError(function () {
	$x = DateTime::from(254400000)->format('U');
});

Assert::error(function () {
	$x = DateTime::from(254400000);
	$x->setTimestamp(254400000);
}, E_USER_WARNING, 'Nette\Utils\DateTime is immutable now, check how it is used in ' . __FILE__ . ':' . (__LINE__ - 1));
