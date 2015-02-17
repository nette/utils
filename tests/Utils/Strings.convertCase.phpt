<?php

/**
 * Test: Nette\Utils\Strings and lower, upper, firstLower, firstUpper, capitalize
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( "ďábelské", Strings::lower('ĎÁBELSKÉ') );
Assert::same( "ďÁBELSKÉ", Strings::firstLower('ĎÁBELSKÉ') );
Assert::same( "ĎÁBELSKÉ", Strings::upper('ďábelské') );
Assert::same( "Ďábelské", Strings::firstUpper('ďábelské') );
Assert::same( "Ďábelské Ódy", Strings::capitalize('ďábelské ódy') );
