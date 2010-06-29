<?php

/**
 * Test: Nette\String::endsWith()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



Assert::true( String::endsWith('123', NULL), "endsWith('123', NULL)" );
Assert::true( String::endsWith('123', ''), "endsWith('123', '')" );
Assert::true( String::endsWith('123', '3'), "endsWith('123', '3')" );
Assert::false( String::endsWith('123', '2'), "endsWith('123', '2')" );
Assert::true( String::endsWith('123', '123'), "endsWith('123', '123')" );
Assert::false( String::endsWith('123', '1234'), "endsWith('123', '1234')" );
