<?php

/**
 * Test: Nette\String::normalize()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



Assert::same( "Hello\n  World",  String::normalize("\r\nHello  \r  World \n\n") );
