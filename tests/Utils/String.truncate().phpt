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
length=-1: "…"

length=0: "…"

length=1: "…"

length=2: "Ř…"

length=3: "Ře…"

length=4: "Řek…"

length=5: "Řekn…"

length=6: "Řekně…"

length=7: "Řeknět…"

length=8: "Řekněte…"

length=9: "Řekněte,…"

length=10: "Řekněte,…"

length=11: "Řekněte,…"

length=12: "Řekněte,…"

length=13: "Řekněte, jak…"

length=14: "Řekněte, jak…"

length=15: "Řekněte, jak…"

length=16: "Řekněte, jak se…"

length=17: "Řekněte, jak se …"

length=18: "Řekněte, jak se …"

length=19: "Řekněte, jak se …"

length=20: "Řekněte, jak se …"

length=21: "Řekněte, jak se …"

length=22: "Řekněte, jak se (dnes…"

length=23: "Řekněte, jak se (dnes)…"

length=24: "Řekněte, jak se (dnes)…"

length=25: "Řekněte, jak se (dnes)…"

length=26: "Řekněte, jak se (dnes)…"

length=27: "Řekněte, jak se (dnes)…"

length=28: "Řekněte, jak se (dnes) máte?"

length=29: "Řekněte, jak se (dnes) máte?"

length=30: "Řekněte, jak se (dnes) máte?"

length=31: "Řekněte, jak se (dnes) máte?"

length=32: "Řekněte, jak se (dnes) máte?"
