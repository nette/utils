<?php

/**
 * Test: Nette\Utils\Strings::normalize()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Utils\Strings;



require __DIR__ . '/../bootstrap.php';



Assert::same( "Hello\n  World",  Strings::normalize("\r\nHello  \r  World \n\n") );
