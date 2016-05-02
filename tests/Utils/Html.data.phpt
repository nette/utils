<?php

/**
 * Test: Nette\Utils\Html user data attribute.
 */

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () { // deprecated
	$el = Html::el('div');
	$el->data['a'] = 'one';
	$el->data['b'] = NULL;
	$el->data['c'] = FALSE;
	$el->data['d'] = '';
	$el->data['e'] = 'two';
	$el->{'data-x'} = 'x';
	$el->data['mxss'] = '``two';

	Assert::same('<div data-x="x" data-a="one" data-d="" data-e="two" data-mxss="``two "></div>', (string) $el);
});


test(function () { // direct
	$el = Html::el('div');
	$el->{'data-x'} = 'x';
	$el->{'data-list'} = [1, 2, 3];
	$el->{'data-arr'} = ['a' => 1];

	Assert::same('<div data-x="x" data-list="[1,2,3]" data-arr=\'{"a":1}\'></div>', (string) $el);
});


test(function () { // function
	$el = Html::el('div');
	$el->data('a', 'one');
	$el->data('b', 'two');
	$el->data('list', [1, 2, 3]);
	$el->data('arr', ['a' => 1]);

	Assert::same('one', $el->{'data-a'});
	Assert::same('<div data-a="one" data-b="two" data-list="[1,2,3]" data-arr=\'{"a":1}\'></div>', (string) $el);
});


test(function () { // special values
	$el = Html::el('div');
	$el->data('top', NULL);
	$el->data('t', TRUE);
	$el->data('f', FALSE);
	$el->data('x', '');

	Assert::same('<div data-t="true" data-f="false" data-x=""></div>', (string) $el);
});


test(function () { // setter
	$el = Html::el('div');
	$el->setAttribute('data-x', 'x');
	$el->setAttribute('data-list', [1,2,3]);
	$el->setAttribute('data-arr', ['a' => 1]);
	$el->setAttribute('top', NULL);
	$el->setAttribute('active', FALSE);

	Assert::same('<div data-x="x" data-list="[1,2,3]" data-arr=\'{"a":1}\'></div>', (string) $el);
});


test(function () {
	$el = Html::el('div');
	$el->data = 'simple';
	Assert::same('<div data="simple"></div>', (string) $el);

	$el->data('simple2');
	Assert::same('<div data="simple2"></div>', (string) $el);

	$el->setAttribute('data', 'simple3');
	Assert::same('<div data="simple3"></div>', (string) $el);
});
