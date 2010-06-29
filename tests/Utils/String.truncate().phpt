<?php

/**
 * Test: Nette\String::truncate()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



iconv_set_encoding('internal_encoding', 'UTF-8');
$s = "\xc5\x98ekn\xc4\x9bte, jak se (dnes) m\xc3\xa1te?"; // Řekněte, jak se (dnes) máte?

T::dump( String::truncate($s, -1), "length=-1" );
T::dump( String::truncate($s, 0), "length=0" );
T::dump( String::truncate($s, 1), "length=1" );
T::dump( String::truncate($s, 2), "length=2" );
T::dump( String::truncate($s, 3), "length=3" );
T::dump( String::truncate($s, 4), "length=4" );
T::dump( String::truncate($s, 5), "length=5" );
T::dump( String::truncate($s, 6), "length=6" );
T::dump( String::truncate($s, 7), "length=7" );
T::dump( String::truncate($s, 8), "length=8" );
T::dump( String::truncate($s, 9), "length=9" );
T::dump( String::truncate($s, 10), "length=10" );
T::dump( String::truncate($s, 11), "length=11" );
T::dump( String::truncate($s, 12), "length=12" );
T::dump( String::truncate($s, 13), "length=13" );
T::dump( String::truncate($s, 14), "length=14" );
T::dump( String::truncate($s, 15), "length=15" );
T::dump( String::truncate($s, 16), "length=16" );
T::dump( String::truncate($s, 17), "length=17" );
T::dump( String::truncate($s, 18), "length=18" );
T::dump( String::truncate($s, 19), "length=19" );
T::dump( String::truncate($s, 20), "length=20" );
T::dump( String::truncate($s, 21), "length=21" );
T::dump( String::truncate($s, 22), "length=22" );
T::dump( String::truncate($s, 23), "length=23" );
T::dump( String::truncate($s, 24), "length=24" );
T::dump( String::truncate($s, 25), "length=25" );
T::dump( String::truncate($s, 26), "length=26" );
T::dump( String::truncate($s, 27), "length=27" );
T::dump( String::truncate($s, 28), "length=28" );
T::dump( String::truncate($s, 29), "length=29" );
T::dump( String::truncate($s, 30), "length=30" );
T::dump( String::truncate($s, 31), "length=31" );
T::dump( String::truncate($s, 32), "length=32" );



__halt_compiler() ?>

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
