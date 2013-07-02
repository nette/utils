<?php

/**
 * Test: Nette\Utils\Strings::matchAll()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::same( array(), Strings::matchAll('hello world!', '#([E-L])+#') );

Assert::same( array(
	array('hell', 'l'),
	array('l', 'l'),
), Strings::matchAll('hello world!', '#([e-l])+#') );

Assert::same( array(
	array('hell'),
	array('l'),
), Strings::matchAll('hello world!', '#[e-l]+#') );

Assert::same( array(
	array(
		array('hell', 0),
	),
	array(
		array('l', 9),
	),
), Strings::matchAll('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );

Assert::same( array(array('ll', 'l')), Strings::matchAll('hello world!', '#[e-l]+#', PREG_PATTERN_ORDER, 2) );
