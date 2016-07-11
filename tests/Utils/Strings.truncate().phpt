<?php

/**
 * Test: Nette\Utils\Strings::truncate()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$s = "\u{158}ekn\u{11B}te, jak se (dnes) m\u{E1}te?"; // Řekněte, jak se (dnes) máte?

Assert::same('…', Strings::truncate($s, -1)); // length=-1
Assert::same('…', Strings::truncate($s, 0)); // length=0
Assert::same('…', Strings::truncate($s, 1)); // length=1
Assert::same('Ř…', Strings::truncate($s, 2)); // length=2
Assert::same('Ře…', Strings::truncate($s, 3)); // length=3
Assert::same('Řek…', Strings::truncate($s, 4)); // length=4
Assert::same('Řekn…', Strings::truncate($s, 5)); // length=5
Assert::same('Řekně…', Strings::truncate($s, 6)); // length=6
Assert::same('Řeknět…', Strings::truncate($s, 7)); // length=7
Assert::same('Řekněte…', Strings::truncate($s, 8)); // length=8
Assert::same('Řekněte,…', Strings::truncate($s, 9)); // length=9
Assert::same('Řekněte,…', Strings::truncate($s, 10)); // length=10
Assert::same('Řekněte,…', Strings::truncate($s, 11)); // length=11
Assert::same('Řekněte,…', Strings::truncate($s, 12)); // length=12
Assert::same('Řekněte, jak…', Strings::truncate($s, 13)); // length=13
Assert::same('Řekněte, jak…', Strings::truncate($s, 14)); // length=14
Assert::same('Řekněte, jak…', Strings::truncate($s, 15)); // length=15
Assert::same('Řekněte, jak se…', Strings::truncate($s, 16)); // length=16
Assert::same('Řekněte, jak se …', Strings::truncate($s, 17)); // length=17
Assert::same('Řekněte, jak se …', Strings::truncate($s, 18)); // length=18
Assert::same('Řekněte, jak se …', Strings::truncate($s, 19)); // length=19
Assert::same('Řekněte, jak se …', Strings::truncate($s, 20)); // length=20
Assert::same('Řekněte, jak se …', Strings::truncate($s, 21)); // length=21
Assert::same('Řekněte, jak se (dnes…', Strings::truncate($s, 22)); // length=22
Assert::same('Řekněte, jak se (dnes)…', Strings::truncate($s, 23)); // length=23
Assert::same('Řekněte, jak se (dnes)…', Strings::truncate($s, 24)); // length=24
Assert::same('Řekněte, jak se (dnes)…', Strings::truncate($s, 25)); // length=25
Assert::same('Řekněte, jak se (dnes)…', Strings::truncate($s, 26)); // length=26
Assert::same('Řekněte, jak se (dnes)…', Strings::truncate($s, 27)); // length=27
Assert::same('Řekněte, jak se (dnes) máte?', Strings::truncate($s, 28)); // length=28
Assert::same('Řekněte, jak se (dnes) máte?', Strings::truncate($s, 29)); // length=29
Assert::same('Řekněte, jak se (dnes) máte?', Strings::truncate($s, 30)); // length=30
Assert::same('Řekněte, jak se (dnes) máte?', Strings::truncate($s, 31)); // length=31
Assert::same('Řekněte, jak se (dnes) máte?', Strings::truncate($s, 32)); // length=32

// mañana, U+006E + U+0303 (combining character)
Assert::same("man\u{303}", Strings::truncate("man\u{303}ana", 4, ''));
Assert::same('man', Strings::truncate("man\u{303}ana", 3, ''));
