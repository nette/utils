<?php

/**
 * Test: Nette\String::replace()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 * @phpversion 5.3
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



Assert::same( '@o wor@d!', String::replace('hello world!', '#[e-l]+#', function() { return '@'; }) );
