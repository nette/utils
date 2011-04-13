<?php

/**
 * Test: Nette\StringUtils::matchAll()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( array(), StringUtils::matchAll('hello world!', '#([E-L])+#') );

Assert::same( array(
	array('hell', 'l'),
	array('l', 'l'),
), StringUtils::matchAll('hello world!', '#([e-l])+#') );

Assert::same( array(
	array('hell'),
	array('l'),
), StringUtils::matchAll('hello world!', '#[e-l]+#') );

Assert::same( array(
	array(
		array('hell', 0),
	),
	array(
		array('l', 9),
	),
), StringUtils::matchAll('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );

Assert::same( array(array('ll',	'l')), StringUtils::matchAll('hello world!', '#[e-l]+#', PREG_PATTERN_ORDER, 2) );
