<?php

/**
 * Test: Nette\Utils\Strings::match()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::null(Strings::match('hello world!', '#([E-L])+#'));

Assert::same(['hell', 'l'], Strings::match('hello world!', '#([e-l])+#'));

Assert::same(['hell'], Strings::match('hello world!', '#[e-l]+#'));

Assert::same([['hell', 0]], Strings::match('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE));
Assert::same([['hell', 0]], Strings::match('hello world!', '#[e-l]+#', captureOffset: true));

Assert::same(['e', null], Strings::match('hello world!', '#e(x)*#', unmatchedAsNull: true));

Assert::same(['ll'], Strings::match('hello world!', '#[e-l]+#', offset: 2));

Assert::null(Strings::match('hello world!', '', offset: 50));
Assert::null(Strings::match('', '', offset: 1));
