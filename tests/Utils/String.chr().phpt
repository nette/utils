<?php

/**
 * Test: String::chr()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



dump( String::chr(0), '#0' ); // "\x00"
dump( String::chr(255), '#255 in UTF-8' ); // "\xc3\xbf"
dump( String::chr(255, 'ISO-8859-1'), '#255 in ISO-8859-1' ); // "\xFF"



__halt_compiler();

------EXPECT------
#0: string(1) "\x00"

#255 in UTF-8: string(2) "ÿ"

#255 in ISO-8859-1: string(1) "\xff"
