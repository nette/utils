<?php

/**
 * Test: Nette\String::normalize()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../NetteTest/initialize.php';



Assert::same( "Hello\n  World",  String::normalize("\r\nHello  \r  World \n\n") );
