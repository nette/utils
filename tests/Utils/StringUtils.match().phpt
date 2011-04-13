<?php

/**
 * Test: Nette\StringUtils::match()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::null( StringUtils::match('hello world!', '#([E-L])+#') );

Assert::same( array('hell',	'l'), StringUtils::match('hello world!', '#([e-l])+#') );

Assert::same( array('hell'), StringUtils::match('hello world!', '#[e-l]+#') );

Assert::same( array(array('hell', 0)), StringUtils::match('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );

Assert::same( array('ll'), StringUtils::match('hello world!', '#[e-l]+#', NULL, 2) );
