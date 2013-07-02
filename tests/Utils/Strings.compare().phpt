<?php

/**
 * Test: Nette\Utils\Strings::compare()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::same( TRUE,  Strings::compare('', '') );
Assert::same( TRUE,  Strings::compare('', '', 0) );
Assert::same( TRUE,  Strings::compare('', '', 1) );
Assert::same( FALSE, Strings::compare('xy', 'xx') );
Assert::same( TRUE,  Strings::compare('xy', 'xx', 0) );
Assert::same( TRUE,  Strings::compare('xy', 'xx', 1) );
Assert::same( FALSE, Strings::compare('xy', 'yy', 1) );
Assert::same( TRUE,  Strings::compare('xy', 'yy', -1) );
Assert::same( TRUE,  Strings::compare('xy', 'yy', -1) );
Assert::same( TRUE,  Strings::compare("I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n", "I\xc3\x91T\xc3\x8bRN\xc3\x82TI\xc3\x94N\xc3\x80LIZ\xc3\x86TI\xc3\x98N") ); // Iñtërnâtiônàlizætiøn
Assert::same( TRUE,  Strings::compare("I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n", "I\xc3\x91T\xc3\x8bRN\xc3\x82TI\xc3\x94N\xc3\x80LIZ\xc3\x86TI\xc3\x98N", 10) );
