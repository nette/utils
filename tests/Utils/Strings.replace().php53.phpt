<?php

/**
 * Test: Nette\Utils\Strings::replace()
 *
 * @author     David Grudl
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( '@o wor@d!', Strings::replace('hello world!', '#[e-l]+#', function() { return '@'; }) );
