<?php

/**
 * Test: Nette\String::match()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../bootstrap.php';



Assert::null( String::match('hello world!', '#([E-L])+#') );

Assert::same( array('hell',	'l'), String::match('hello world!', '#([e-l])+#') );

Assert::same( array('hell'), String::match('hello world!', '#[e-l]+#') );

Assert::same( array(array('hell', 0)), String::match('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );

Assert::same( array('ll'), String::match('hello world!', '#[e-l]+#', NULL, 2) );
