<?php

/**
 * Test: Nette\Utils\Html user data attribute.
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Html;


require __DIR__ . '/../bootstrap.php';


test(function() {
	$el = Html::el('div');
	$el->data['a'] = 'one';
	$el->data['b'] = NULL;
	$el->data['c'] = FALSE;
	$el->data['d'] = '';
	$el->data['e'] = 'two';

	Assert::same( '<div data-a="one" data-d="" data-e="two"></div>', (string) $el );
});


test(function() { // direct
	$el = Html::el('div');
	$el->{'data-x'} = 'x';
	$el->data['x'] = 'y';

	Assert::same( '<div data-x="x" data-x="y"></div>', (string) $el );
});


test(function() { // function
	$el = Html::el('div');
	$el->data('a', 'one');
	$el->data('b', 'two');

	Assert::same( '<div data-a="one" data-b="two"></div>', (string) $el );
});


test(function() {
	$el = Html::el('div');
	$el->data('top', NULL);
	$el->data('active', FALSE);
	$el->data('x', '');
	Assert::same( '<div data-x=""></div>', (string) $el );
});


test(function() {
	$el = Html::el('div');
	$el->data = 'simple';
	Assert::same( '<div data="simple"></div>', (string) $el );
});
