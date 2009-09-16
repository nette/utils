<?php

/**
 * Test: Nette\String::indent()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



dump( String::indent("") ); // ""
dump( String::indent("\n") ); // "\n"
dump( String::indent("word") ); // "\tword"
dump( String::indent("\nword") ); // "\n\tword"
dump( String::indent("\nword") ); // "\n\tword"
dump( String::indent("\nword\n") ); // "\n\tword\n"
dump( String::indent("\r\nword\r\n") ); // "\r\n\tword\r\n"
dump( String::indent("\r\nword\r\n", 2) ); // "\r\n\t\tword\r\n"
dump( String::indent("\r\nword\r\n", 2, '   ') ); // "\r\n      word\r\n"



__halt_compiler();

------EXPECT------
string(0) ""

string(1) "
"

string(5) "	word"

string(6) "
	word"

string(6) "
	word"

string(7) "
	word
"

string(9) "
	word
"

string(10) "
		word
"

string(14) "
      word
"
