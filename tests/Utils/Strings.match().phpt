<?php

/**
 * Test: Nette\Utils\Strings::match()
 */

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::null(Strings::match('hello world!', '#([E-L])+#'));

Assert::same(['hell', 'l'], Strings::match('hello world!', '#([e-l])+#'));

Assert::same(['hell'], Strings::match('hello world!', '#[e-l]+#'));

Assert::same([['hell', 0]], Strings::match('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE));

Assert::same(['ll'], Strings::match('hello world!', '#[e-l]+#', NULL, 2));
