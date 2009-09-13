<?php

/**
 * Test: String::webalize()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



dump( String::webalize("&\xc5\xbdLU\xc5\xa4OU\xc4\x8cK\xc3\x9d K\xc5\xae\xc5\x87 \xc3\xb6\xc5\x91\xc3\xb4o!") ); // &ŽLUŤOUČKÝ KŮŇ öőôo!
dump( String::webalize("&\xc5\xbdLU\xc5\xa4OU\xc4\x8cK\xc3\x9d K\xc5\xae\xc5\x87 \xc3\xb6\xc5\x91\xc3\xb4o!", NULL, FALSE) ); // &ŽLUŤOUČKÝ KŮŇ öőôo!
dump( String::webalize("\xc2\xBC!", '!') ); // "1-4-!"



__halt_compiler();

------EXPECT------
string(18) "zlutoucky-kun-oooo"

string(18) "ZLUTOUCKY-KUN-oooo"

string(5) "1-4-!"
