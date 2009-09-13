<?php

/**
 * Test: String::startsWith()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



dump( String::startsWith('123', NULL), "startsWith('123', NULL)" ); // True
dump( String::startsWith('123', ''), "startsWith('123', '')" ); // True
dump( String::startsWith('123', '1'), "startsWith('123', '1')" ); // True
dump( String::startsWith('123', '2'), "startsWith('123', '2')" ); // False
dump( String::startsWith('123', '123'), "startsWith('123', '123')" ); // True
dump( String::startsWith('123', '1234'), "startsWith('123', '1234')" ); // False



__halt_compiler();

------EXPECT------
startsWith('123', NULL): bool(TRUE)

startsWith('123', ''): bool(TRUE)

startsWith('123', '1'): bool(TRUE)

startsWith('123', '2'): bool(FALSE)

startsWith('123', '123'): bool(TRUE)

startsWith('123', '1234'): bool(FALSE)
