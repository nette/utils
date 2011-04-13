<?php

/**
 * Test: Nette\StringUtils::length()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( 0,  StringUtils::length('') );
Assert::same( 20,  StringUtils::length("I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n") ); // Iñtërnâtiônàlizætiøn
