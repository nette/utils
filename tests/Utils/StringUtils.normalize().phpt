<?php

/**
 * Test: Nette\StringUtils::normalize()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( "Hello\n  World",  StringUtils::normalize("\r\nHello  \r  World \n\n") );
