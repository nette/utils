<?php

/**
 * Test: Nette\Utils\Strings::normalize()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( "Hello\n  World",  Strings::normalize("\r\nHello  \r  World \n\n") );

Assert::same( "Hello  World",  Strings::normalize("Hello \x00 World") );
Assert::same( "Hello  World",  Strings::normalize("Hello \x0B World") );
Assert::same( "Hello  World",  Strings::normalize("Hello \x1F World") );
Assert::same( "Hello \x7E World",  Strings::normalize("Hello \x7E World") );
Assert::same( "Hello  World",  Strings::normalize("Hello \x7F World") );
Assert::same( "Hello  World",  Strings::normalize("Hello \xC2\x80 World") );
Assert::same( "Hello  World",  Strings::normalize("Hello \xC2\x9F World") );
Assert::same( "Hello \xC2\xA0 World",  Strings::normalize("Hello \xC2\xA0 World") );
