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

Assert::same([['l', 2]], Strings::match('žluťoučký kůň', '#[e-l]+#u', PREG_OFFSET_CAPTURE));
Assert::same([['l', 2]], Strings::match('žluťoučký kůň', '#[e-l]+#u', captureOffset: true));

Assert::same([['l', 1]], Strings::match('žluťoučký kůň', '#[e-l]+#u', captureOffset: true, utf8: true));
Assert::same(['e', null], Strings::match('hello world!', '#e(x)*#', unmatchedAsNull: true));
Assert::same(['e', null], Strings::match('hello world!', '#e(x)*#', 0, 0, unmatchedAsNull: true)); // $flags = 0

Assert::same(['ll'], Strings::match('hello world!', '#[e-l]+#', offset: 2));

Assert::same(['l'], Strings::match('žluťoučký kůň', '#[e-l]+#u', offset: 2));

Assert::same(['k'], Strings::match('žluťoučký kůň', '#[e-l]+#u', utf8: true, offset: 2));

Assert::same(['žluťoučký'], Strings::match('žluťoučký kůň', '#\w+#', utf8: true)); // without modifier

Assert::same([['k', 7]], Strings::match('žluťoučký kůň', '#[e-l]+#u', captureOffset: true, utf8: true, offset: 2));

Assert::null(Strings::match('hello world!', '', offset: 50));
Assert::null(Strings::match('', '', offset: 1));
