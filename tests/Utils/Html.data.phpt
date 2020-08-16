<?php

/**
 * Test: Nette\Utils\Html user data attribute.
 */

declare(strict_types=1);

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('direct', function () {
	$el = Html::el('div');
	$el->{'data-x'} = 'x';
	$el->{'data-list'} = [1, 2, 3];
	$el->{'data-arr'} = ['a' => 1];

	Assert::same('<div data-x="x" data-list="[1,2,3]" data-arr=\'{"a":1}\'></div>', (string) $el);
});


test('function', function () {
	$el = Html::el('div');
	$el->data('a', 'one');
	$el->data('b', 'two');
	$el->data('list', [1, 2, 3]);
	$el->data('arr', ['a' => 1]);

	Assert::same('one', $el->{'data-a'});
	Assert::same('<div data-a="one" data-b="two" data-list="[1,2,3]" data-arr=\'{"a":1}\'></div>', (string) $el);
});


test('special values', function () {
	$el = Html::el('div');
	$el->data('top', null);
	$el->data('t', true);
	$el->data('f', false);
	$el->data('x', '');

	Assert::same('<div data-t="true" data-f="false" data-x=""></div>', (string) $el);
});


test('setter', function () {
	$el = Html::el('div');
	$el->setAttribute('data-x', 'x');
	$el->setAttribute('data-list', [1, 2, 3]);
	$el->setAttribute('data-arr', ['a' => 1]);
	$el->setAttribute('top', null);
	$el->setAttribute('active', false);

	Assert::same('<div data-x="x" data-list="[1,2,3]" data-arr=\'{"a":1}\'></div>', (string) $el);
});


test('', function () {
	$el = Html::el('div');
	$el->data = 'simple';
	Assert::same('<div data="simple"></div>', (string) $el);

	$el->data('simple2');
	Assert::same('<div data="simple2"></div>', (string) $el);

	$el->setAttribute('data', 'simple3');
	Assert::same('<div data="simple3"></div>', (string) $el);
});
