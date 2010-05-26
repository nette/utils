<?php

/**
 * Test: Nette\String::padLeft() & padRight()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../NetteTest/initialize.php';



dump( String::padLeft("\xc5\xbdLU", 10, "\xc5\xa4OU") ); // ŽLU - ŤOU
dump( String::padLeft("\xc5\xbdLU", 9, "\xc5\xa4OU") );
dump( String::padLeft("\xc5\xbdLU", 3, "\xc5\xa4OU") );
dump( String::padLeft("\xc5\xbdLU", 0, "\xc5\xa4OU") );
dump( String::padLeft("\xc5\xbdLU", -1, "\xc5\xa4OU") );
dump( String::padLeft("\xc5\xbdLU", 10, "\xc5\xa4") );
dump( String::padLeft("\xc5\xbdLU", 3, "\xc5\xa4") );
dump( String::padLeft("\xc5\xbdLU", 10) );


dump( String::padRight("\xc5\xbdLU", 10, "\xc5\xa4OU") );
dump( String::padRight("\xc5\xbdLU", 9, "\xc5\xa4OU") );
dump( String::padRight("\xc5\xbdLU", 3, "\xc5\xa4OU") );
dump( String::padRight("\xc5\xbdLU", 0, "\xc5\xa4OU") );
dump( String::padRight("\xc5\xbdLU", -1, "\xc5\xa4OU") );
dump( String::padRight("\xc5\xbdLU", 10, "\xc5\xa4") );
dump( String::padRight("\xc5\xbdLU", 3, "\xc5\xa4") );
dump( String::padRight("\xc5\xbdLU", 10) );



__halt_compiler() ?>

------EXPECT------
string(14) "ŤOUŤOUŤŽLU"

string(12) "ŤOUŤOUŽLU"

string(4) "ŽLU"

string(4) "ŽLU"

string(4) "ŽLU"

string(18) "ŤŤŤŤŤŤŤŽLU"

string(4) "ŽLU"

string(11) "       ŽLU"

string(14) "ŽLUŤOUŤOUŤ"

string(12) "ŽLUŤOUŤOU"

string(4) "ŽLU"

string(4) "ŽLU"

string(4) "ŽLU"

string(18) "ŽLUŤŤŤŤŤŤŤ"

string(4) "ŽLU"

string(11) "ŽLU       "
