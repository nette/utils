<?php

/**
 * Test: Nette\String::endsWith()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



dump( String::endsWith('123', NULL), "endsWith('123', NULL)" ); // True
dump( String::endsWith('123', ''), "endsWith('123', '')" ); // True
dump( String::endsWith('123', '3'), "endsWith('123', '3')" ); // True
dump( String::endsWith('123', '2'), "endsWith('123', '2')" ); // False
dump( String::endsWith('123', '123'), "endsWith('123', '123')" ); // True
dump( String::endsWith('123', '1234'), "endsWith('123', '1234')" ); // False



__halt_compiler();

------EXPECT------
endsWith('123', NULL): bool(TRUE)

endsWith('123', ''): bool(TRUE)

endsWith('123', '3'): bool(TRUE)

endsWith('123', '2'): bool(FALSE)

endsWith('123', '123'): bool(TRUE)

endsWith('123', '1234'): bool(FALSE)
