<?php

/**
 * Test: Nette\Utils\Strings::match()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// not matched
Assert::null(Strings::match('hello world!', '#([E-L])+#'));


// capturing
Assert::same(['hell', 'l'], Strings::match('hello world!', '#([e-l])+#'));

Assert::same(['hell'], Strings::match('hello world!', '#[e-l]+#'));


// options
Assert::same([[' ', 12]], Strings::match('россия - враг', '#\s+#u', PREG_OFFSET_CAPTURE));
Assert::same([[' ', 12]], Strings::match('россия - враг', '#\s+#u', captureOffset: true));

Assert::same([[' ', 6]], Strings::match('россия - враг', '#\s+#u', captureOffset: true, utf8: true));
Assert::same(['e', null], Strings::match('hello world!', '#e(x)*#', unmatchedAsNull: true));

Assert::same(['ll'], Strings::match('hello world!', '#[e-l]+#', offset: 2));

Assert::same(['l'], Strings::match('žluťoučký kůň', '#[e-l]+#u', offset: 2));

Assert::same(['k'], Strings::match('žluťoučký kůň', '#[e-l]+#u', utf8: true, offset: 2));

Assert::same(['žluťoučký'], Strings::match('žluťoučký kůň', '#\w+#', utf8: true)); // without modifier

Assert::same([['k', 7]], Strings::match('žluťoučký kůň', '#[e-l]+#u', captureOffset: true, utf8: true, offset: 2));


// right edge
Assert::same([''], Strings::match('he', '#(?<=e)#', offset: 2));
Assert::same(null, Strings::match('he', '#(?<=x)#', offset: 2));
Assert::same(null, Strings::match('he', '##', offset: 3));
