<?php

/**
 * Test: Nette\Utils\Strings::match()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::null( Strings::match('hello world!', '#([E-L])+#') );

Assert::same( array('hell', 'l'), Strings::match('hello world!', '#([e-l])+#') );

Assert::same( array('hell'), Strings::match('hello world!', '#[e-l]+#') );

Assert::same( array(array('hell', 0)), Strings::match('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );

Assert::same( array('ll'), Strings::match('hello world!', '#[e-l]+#', NULL, 2) );
