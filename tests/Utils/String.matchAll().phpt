<?php

/**
 * Test: Nette\String::matchAll()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



Assert::same( array(), String::matchAll('hello world!', '#([E-L])+#') );

Assert::same( array(
	array('hell', 'l'),
	array('l', 'l'),
), String::matchAll('hello world!', '#([e-l])+#') );

Assert::same( array(
	array('hell'),
	array('l'),
), String::matchAll('hello world!', '#[e-l]+#') );

Assert::same( array(
	array(
		array('hell', 0),
	),
	array(
		array('l', 9),
	),
), String::matchAll('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );

Assert::same( array(array('ll',	'l')), String::matchAll('hello world!', '#[e-l]+#', PREG_PATTERN_ORDER, 2) );
