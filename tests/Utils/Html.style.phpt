<?php

/**
 * Test: Nette\Utils\Html style & class attribute.
 */

declare(strict_types=1);

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('appending styles and classes with array syntax', function () {
	$el = Html::el('div');
	$el->style[] = 'text-align:right';
	$el->style[] = null;
	$el->style[] = 'background-color: blue';
	$el->class[] = 'one';
	$el->class[] = null;
	$el->class[] = 'two';

	Assert::same('<div style="text-align:right;background-color: blue" class="one two"></div>', (string) $el);


	$el->style = null;
	$el->style['text-align'] = 'left';
	$el->style['background-color'] = 'green';
	Assert::same('<div style="text-align:left;background-color:green" class="one two"></div>', (string) $el);
});


test('manipulating style and class attributes with append and set', function () {
	$el = Html::el('div');
	$el->appendAttribute('style', 'text-align:right');
	$el->appendAttribute('style', null);
	$el->appendAttribute('style', 'background-color: blue');
	$el->appendAttribute('class', 'one');
	$el->appendAttribute('class', null);
	$el->appendAttribute('class', 'two');

	Assert::same('<div style="text-align:right;background-color: blue" class="one two"></div>', (string) $el);


	$el->setAttribute('style', null);
	$el->appendAttribute('style', 'text-align', 'left');
	$el->appendAttribute('style', 'background-color', 'green');
	Assert::same('<div style="text-align:left;background-color:green" class="one two"></div>', (string) $el);


	$el->setAttribute('style', [
		'text-align' => 'right',
		'background-color' => 'red',
	]);
	Assert::same('<div style="text-align:right;background-color:red" class="one two"></div>', (string) $el);


	$el->appendAttribute('style', [
		'text-align' => 'center',
		'color' => 'orange',
	]);
	Assert::same('<div style="text-align:center;color:orange;background-color:red" class="one two"></div>', (string) $el);
});


test('combining style, class, and id methods', function () {
	$el = Html::el('div');
	$el->style('color', 'white');
	$el->style('background-color', 'blue');
	$el->appendAttribute('style', 'text-align', 'left');

	$el->class = 'one';
	$el->class('', true);
	$el->class('two', true);

	$el->id('my', true);
	Assert::same('<div style="color:white;background-color:blue;text-align:left" class="one two" id="my"></div>', (string) $el);
});


test('appending style attributes with forced addition', function () {
	$el = Html::el('div');
	$el->style[] = 'text-align:right';
	$el->style('', true);
	$el->style('background-color: blue', true);
	$el->appendAttribute('style', 'color: orange', true);
	Assert::same('<div style="text-align:right;background-color: blue;color: orange"></div>', (string) $el);
});


test('toggling class names with boolean flags', function () {
	$el = Html::el('div');
	$el->class('top', true);
	$el->class('active', true);
	$el->appendAttribute('class', 'pull-right', true);
	Assert::same('<div class="top active pull-right"></div>', (string) $el);


	$el->class('top', null);
	$el->class('active', false);
	$el->appendAttribute('class', 'pull-right', false);
	Assert::same('<div></div>', (string) $el);
});
