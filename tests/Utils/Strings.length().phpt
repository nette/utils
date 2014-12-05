<?php

/**
 * Test: Nette\Utils\Strings::length()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( 0, Strings::length('') );
Assert::same( 20, Strings::length("I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n") ); // Iñtërnâtiônàlizætiøn
Assert::same( 1, Strings::length("\xF0\x90\x80\x80") ); // U+010000
Assert::same( 6, Strings::length("ma\xC3\xB1ana") );   // mañana, U+00F1
Assert::same( 7, Strings::length("man\xCC\x83ana") );  // mañana, U+006E + U+0303 (combining character)
