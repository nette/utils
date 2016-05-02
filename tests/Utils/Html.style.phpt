<?php

/**
 * Test: Nette\Utils\Html style & class attribute.
 */

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
	$el = Html::el('div');
	$el->style[] = 'text-align:right';
	$el->style[] = NULL;
	$el->style[] = 'background-color: blue';
	$el->class[] = 'one';
	$el->class[] = NULL;
	$el->class[] = 'two';

	Assert::same('<div style="text-align:right;background-color: blue" class="one two"></div>', (string) $el);


	$el->style = NULL;
	$el->style['text-align'] = 'left';
	$el->style['background-color'] = 'green';
	Assert::same('<div style="text-align:left;background-color:green" class="one two"></div>', (string) $el);
});


test(function () {
	$el = Html::el('div');
	$el->appendAttribute('style', 'text-align:right');
	$el->appendAttribute('style', NULL);
	$el->appendAttribute('style', 'background-color: blue');
	$el->appendAttribute('class', 'one');
	$el->appendAttribute('class', NULL);
	$el->appendAttribute('class', 'two');

	Assert::same('<div style="text-align:right;background-color: blue" class="one two"></div>', (string) $el);


	$el->setAttribute('style', NULL);
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


test(function () { // append
	$el = Html::el('div');
	$el->style('color', 'white');
	$el->style('background-color', 'blue');
	$el->appendAttribute('style', 'text-align', 'left');

	$el->class = 'one';
	$el->class('', TRUE);
	$el->class('two', TRUE);

	$el->id('my', TRUE);
	Assert::same('<div style="color:white;background-color:blue;text-align:left" class="one two" id="my"></div>', (string) $el);
});


test(function () { // append II
	$el = Html::el('div');
	$el->style[] = 'text-align:right';
	$el->style('', TRUE);
	$el->style('background-color: blue', TRUE);
	$el->appendAttribute('style', 'color: orange', TRUE);
	Assert::same('<div style="text-align:right;background-color: blue;color: orange"></div>', (string) $el);
});


test(function () { // append III
	$el = Html::el('div');
	$el->class('top', TRUE);
	$el->class('active', TRUE);
	$el->appendAttribute('class', 'pull-right', TRUE);
	Assert::same('<div class="top active pull-right"></div>', (string) $el);


	$el->class('top', NULL);
	$el->class('active', FALSE);
	$el->appendAttribute('class', 'pull-right', FALSE);
	Assert::same('<div></div>', (string) $el);
});
