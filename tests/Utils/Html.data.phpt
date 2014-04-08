<?php

/**
 * Test: Nette\Utils\Html user data attribute.
 *
 * @author     David Grudl
 */

use Nette\Utils\Html,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() {
	$el = Html::el('div');
	$el->data = array('string' => 'string');
	$el->data['empty'] = '';
	$el->data['null'] = null;
	$el->data['true'] = true;
	$el->data['false'] = false;
	$el->data['int'] = 42;
	$el->data->list = array();
	$el->data->list[] = 'one';
	$el->{'data-list'}[] = 'two';
	$el->data->dict = array('a' => 'A', 1 => 2);
	$el->data->obj = new \stdClass;
	$el->data->obj->a = 'A';
	$el->data->obj->b = 'B';

	Assert::same( '<div data-string="string" data-empty="" data-true="true" data-false="false" data-int="42" data-list=\'["one","two"]\' data-dict=\'{"a":"A","1":2}\' data-obj=\'{"a":"A","b":"B"}\'></div>', (string) $el );
});


test(function() { // direct
	$el = Html::el('div');
	$el->{'data-x'} = 'x';
	$el->data['x'] = 'y';

	Assert::same( '<div data-x="y"></div>', (string) $el );
});


test(function() { // function
	$el = Html::el('div');
	$el->data(array('a' => 'one'));
	$el->data('b', 'two');

	Assert::same( '<div data-a="one" data-b="two"></div>', (string) $el );
});


test(function() {
	$el = Html::el('div');
	$el->data('top', NULL);
	$el->data('active', FALSE);
	$el->addData(array('x' => ''));
	Assert::same( '<div data-active="false" data-x=""></div>', (string) $el );
});


test(function() {
	$el = Html::el('div');
	$el->data = 'simple';
	Assert::same( '<div data="simple"></div>', (string) $el );
});
