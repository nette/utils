<?php

/**
 * Test: Nette\String::startsWith()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



Assert::true( String::startsWith('123', NULL), "startsWith('123', NULL)" );
Assert::true( String::startsWith('123', ''), "startsWith('123', '')" );
Assert::true( String::startsWith('123', '1'), "startsWith('123', '1')" );
Assert::false( String::startsWith('123', '2'), "startsWith('123', '2')" );
Assert::true( String::startsWith('123', '123'), "startsWith('123', '123')" );
Assert::false( String::startsWith('123', '1234'), "startsWith('123', '1234')" );
