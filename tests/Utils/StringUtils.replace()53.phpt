<?php

/**
 * Test: Nette\StringUtils::replace()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 * @phpversion 5.3
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( '@o wor@d!', StringUtils::replace('hello world!', '#[e-l]+#', function() { return '@'; }) );
