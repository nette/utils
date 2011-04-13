<?php

/**
 * Test: Nette\StringUtils::truncate()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



iconv_set_encoding('internal_encoding', 'UTF-8');
$s = "\xc5\x98ekn\xc4\x9bte, jak se (dnes) m\xc3\xa1te?"; // Řekněte, jak se (dnes) máte?

Assert::same( '…', StringUtils::truncate($s, -1), 'length=-1' );
Assert::same( '…', StringUtils::truncate($s, 0), 'length=0' );
Assert::same( '…', StringUtils::truncate($s, 1), 'length=1' );
Assert::same( 'Ř…', StringUtils::truncate($s, 2), 'length=2' );
Assert::same( 'Ře…', StringUtils::truncate($s, 3), 'length=3' );
Assert::same( 'Řek…', StringUtils::truncate($s, 4), 'length=4' );
Assert::same( 'Řekn…', StringUtils::truncate($s, 5), 'length=5' );
Assert::same( 'Řekně…', StringUtils::truncate($s, 6), 'length=6' );
Assert::same( 'Řeknět…', StringUtils::truncate($s, 7), 'length=7' );
Assert::same( 'Řekněte…', StringUtils::truncate($s, 8), 'length=8' );
Assert::same( 'Řekněte,…', StringUtils::truncate($s, 9), 'length=9' );
Assert::same( 'Řekněte,…', StringUtils::truncate($s, 10), 'length=10' );
Assert::same( 'Řekněte,…', StringUtils::truncate($s, 11), 'length=11' );
Assert::same( 'Řekněte,…', StringUtils::truncate($s, 12), 'length=12' );
Assert::same( 'Řekněte, jak…', StringUtils::truncate($s, 13), 'length=13' );
Assert::same( 'Řekněte, jak…', StringUtils::truncate($s, 14), 'length=14' );
Assert::same( 'Řekněte, jak…', StringUtils::truncate($s, 15), 'length=15' );
Assert::same( 'Řekněte, jak se…', StringUtils::truncate($s, 16), 'length=16' );
Assert::same( 'Řekněte, jak se …', StringUtils::truncate($s, 17), 'length=17' );
Assert::same( 'Řekněte, jak se …', StringUtils::truncate($s, 18), 'length=18' );
Assert::same( 'Řekněte, jak se …', StringUtils::truncate($s, 19), 'length=19' );
Assert::same( 'Řekněte, jak se …', StringUtils::truncate($s, 20), 'length=20' );
Assert::same( 'Řekněte, jak se …', StringUtils::truncate($s, 21), 'length=21' );
Assert::same( 'Řekněte, jak se (dnes…', StringUtils::truncate($s, 22), 'length=22' );
Assert::same( 'Řekněte, jak se (dnes)…', StringUtils::truncate($s, 23), 'length=23' );
Assert::same( 'Řekněte, jak se (dnes)…', StringUtils::truncate($s, 24), 'length=24' );
Assert::same( 'Řekněte, jak se (dnes)…', StringUtils::truncate($s, 25), 'length=25' );
Assert::same( 'Řekněte, jak se (dnes)…', StringUtils::truncate($s, 26), 'length=26' );
Assert::same( 'Řekněte, jak se (dnes)…', StringUtils::truncate($s, 27), 'length=27' );
Assert::same( 'Řekněte, jak se (dnes) máte?', StringUtils::truncate($s, 28), 'length=28' );
Assert::same( 'Řekněte, jak se (dnes) máte?', StringUtils::truncate($s, 29), 'length=29' );
Assert::same( 'Řekněte, jak se (dnes) máte?', StringUtils::truncate($s, 30), 'length=30' );
Assert::same( 'Řekněte, jak se (dnes) máte?', StringUtils::truncate($s, 31), 'length=31' );
Assert::same( 'Řekněte, jak se (dnes) máte?', StringUtils::truncate($s, 32), 'length=32' );
