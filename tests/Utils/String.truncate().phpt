<?php

/**
 * Test: String::truncate()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



iconv_set_encoding('internal_encoding', 'UTF-8');
$s = "\xc5\x98ekn\xc4\x9bte, jak se (dnes) m\xc3\xa1te?"; // Řekněte, jak se (dnes) máte?

dump( String::truncate($s, -1), "length=-1" );
dump( String::truncate($s, 0), "length=0" );
dump( String::truncate($s, 1), "length=1" );
dump( String::truncate($s, 2), "length=2" );
dump( String::truncate($s, 3), "length=3" );
dump( String::truncate($s, 4), "length=4" );
dump( String::truncate($s, 5), "length=5" );
dump( String::truncate($s, 6), "length=6" );
dump( String::truncate($s, 7), "length=7" );
dump( String::truncate($s, 8), "length=8" );
dump( String::truncate($s, 9), "length=9" );
dump( String::truncate($s, 10), "length=10" );
dump( String::truncate($s, 11), "length=11" );
dump( String::truncate($s, 12), "length=12" );
dump( String::truncate($s, 13), "length=13" );
dump( String::truncate($s, 14), "length=14" );
dump( String::truncate($s, 15), "length=15" );
dump( String::truncate($s, 16), "length=16" );
dump( String::truncate($s, 17), "length=17" );
dump( String::truncate($s, 18), "length=18" );
dump( String::truncate($s, 19), "length=19" );
dump( String::truncate($s, 20), "length=20" );
dump( String::truncate($s, 21), "length=21" );
dump( String::truncate($s, 22), "length=22" );
dump( String::truncate($s, 23), "length=23" );
dump( String::truncate($s, 24), "length=24" );
dump( String::truncate($s, 25), "length=25" );
dump( String::truncate($s, 26), "length=26" );
dump( String::truncate($s, 27), "length=27" );
dump( String::truncate($s, 28), "length=28" );
dump( String::truncate($s, 29), "length=29" );
dump( String::truncate($s, 30), "length=30" );
dump( String::truncate($s, 31), "length=31" );
dump( String::truncate($s, 32), "length=32" );



__halt_compiler();

------EXPECT------
length=-1: string(3) "…"

length=0: string(3) "…"

length=1: string(3) "…"

length=2: string(5) "Ř…"

length=3: string(6) "Ře…"

length=4: string(7) "Řek…"

length=5: string(8) "Řekn…"

length=6: string(10) "Řekně…"

length=7: string(11) "Řeknět…"

length=8: string(12) "Řekněte…"

length=9: string(13) "Řekněte,…"

length=10: string(13) "Řekněte,…"

length=11: string(13) "Řekněte,…"

length=12: string(13) "Řekněte,…"

length=13: string(17) "Řekněte, jak…"

length=14: string(17) "Řekněte, jak…"

length=15: string(17) "Řekněte, jak…"

length=16: string(20) "Řekněte, jak se…"

length=17: string(21) "Řekněte, jak se …"

length=18: string(21) "Řekněte, jak se …"

length=19: string(21) "Řekněte, jak se …"

length=20: string(21) "Řekněte, jak se …"

length=21: string(21) "Řekněte, jak se …"

length=22: string(26) "Řekněte, jak se (dnes…"

length=23: string(27) "Řekněte, jak se (dnes)…"

length=24: string(27) "Řekněte, jak se (dnes)…"

length=25: string(27) "Řekněte, jak se (dnes)…"

length=26: string(27) "Řekněte, jak se (dnes)…"

length=27: string(27) "Řekněte, jak se (dnes)…"

length=28: string(31) "Řekněte, jak se (dnes) máte?"

length=29: string(31) "Řekněte, jak se (dnes) máte?"

length=30: string(31) "Řekněte, jak se (dnes) máte?"

length=31: string(31) "Řekněte, jak se (dnes) máte?"

length=32: string(31) "Řekněte, jak se (dnes) máte?"
