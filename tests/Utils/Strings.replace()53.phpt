<?php

/**
 * Test: Nette\Utils\Strings::replace()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @phpversion 5.3
 */

use Nette\Utils\Strings;



require __DIR__ . '/../bootstrap.php';



Assert::same( '@o wor@d!', Strings::replace('hello world!', '#[e-l]+#', function() { return '@'; }) );
