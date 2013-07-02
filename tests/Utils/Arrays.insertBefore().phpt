<?php

/**
 * Test: Nette\Utils\Arrays::insertBefore() & insertAfter()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Arrays;


require __DIR__ . '/../bootstrap.php';


$arr  = array(
	NULL => 'first',
	FALSE => 'second',
	1 => 'third',
	7 => 'fourth'
);

Assert::same( array(
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
), $arr );


test(function() use ($arr) { // First item
	$dolly = $arr;
	Arrays::insertBefore($dolly, NULL, array('new' => 'value'));
	Assert::same( array(
		'new' => 'value',
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
	), $dolly );


	$dolly = $arr;
	Arrays::insertAfter($dolly, NULL, array('new' => 'value'));
	Assert::same( array(
		'' => 'first',
		'new' => 'value',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
	), $dolly );
});


test(function() use ($arr) { // Last item
	$dolly = $arr;
	Arrays::insertBefore($dolly, 7, array('new' => 'value'));
	Assert::same( array(
		'' => 'first',
		0 => 'second',
		1 => 'third',
		'new' => 'value',
		7 => 'fourth',
	), $dolly );


	$dolly = $arr;
	Arrays::insertAfter($dolly, 7, array('new' => 'value'));
	Assert::same( array(
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
		'new' => 'value',
	), $dolly );
});


test(function() use ($arr) { // Undefined item
	$dolly = $arr;
	Arrays::insertBefore($dolly, 'undefined', array('new' => 'value'));
	Assert::same( array(
		'new' => 'value',
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
	), $dolly );


	$dolly = $arr;
	Arrays::insertAfter($dolly, 'undefined', array('new' => 'value'));
	Assert::same( array(
		'' => 'first',
		0 => 'second',
		1 => 'third',
		7 => 'fourth',
		'new' => 'value',
	), $dolly );
});
