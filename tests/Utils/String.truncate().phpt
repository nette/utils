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

Assert::same( '…', String::truncate($s, -1), 'length=-1' );
Assert::same( '…', String::truncate($s, 0), 'length=0' );
Assert::same( '…', String::truncate($s, 1), 'length=1' );
Assert::same( 'Ř…', String::truncate($s, 2), 'length=2' );
Assert::same( 'Ře…', String::truncate($s, 3), 'length=3' );
Assert::same( 'Řek…', String::truncate($s, 4), 'length=4' );
Assert::same( 'Řekn…', String::truncate($s, 5), 'length=5' );
Assert::same( 'Řekně…', String::truncate($s, 6), 'length=6' );
Assert::same( 'Řeknět…', String::truncate($s, 7), 'length=7' );
Assert::same( 'Řekněte…', String::truncate($s, 8), 'length=8' );
Assert::same( 'Řekněte,…', String::truncate($s, 9), 'length=9' );
Assert::same( 'Řekněte,…', String::truncate($s, 10), 'length=10' );
Assert::same( 'Řekněte,…', String::truncate($s, 11), 'length=11' );
Assert::same( 'Řekněte,…', String::truncate($s, 12), 'length=12' );
Assert::same( 'Řekněte, jak…', String::truncate($s, 13), 'length=13' );
Assert::same( 'Řekněte, jak…', String::truncate($s, 14), 'length=14' );
Assert::same( 'Řekněte, jak…', String::truncate($s, 15), 'length=15' );
Assert::same( 'Řekněte, jak se…', String::truncate($s, 16), 'length=16' );
Assert::same( 'Řekněte, jak se …', String::truncate($s, 17), 'length=17' );
Assert::same( 'Řekněte, jak se …', String::truncate($s, 18), 'length=18' );
Assert::same( 'Řekněte, jak se …', String::truncate($s, 19), 'length=19' );
Assert::same( 'Řekněte, jak se …', String::truncate($s, 20), 'length=20' );
Assert::same( 'Řekněte, jak se …', String::truncate($s, 21), 'length=21' );
Assert::same( 'Řekněte, jak se (dnes…', String::truncate($s, 22), 'length=22' );
Assert::same( 'Řekněte, jak se (dnes)…', String::truncate($s, 23), 'length=23' );
Assert::same( 'Řekněte, jak se (dnes)…', String::truncate($s, 24), 'length=24' );
Assert::same( 'Řekněte, jak se (dnes)…', String::truncate($s, 25), 'length=25' );
Assert::same( 'Řekněte, jak se (dnes)…', String::truncate($s, 26), 'length=26' );
Assert::same( 'Řekněte, jak se (dnes)…', String::truncate($s, 27), 'length=27' );
Assert::same( 'Řekněte, jak se (dnes) máte?', String::truncate($s, 28), 'length=28' );
Assert::same( 'Řekněte, jak se (dnes) máte?', String::truncate($s, 29), 'length=29' );
Assert::same( 'Řekněte, jak se (dnes) máte?', String::truncate($s, 30), 'length=30' );
Assert::same( 'Řekněte, jak se (dnes) máte?', String::truncate($s, 31), 'length=31' );
Assert::same( 'Řekněte, jak se (dnes) máte?', String::truncate($s, 32), 'length=32' );
