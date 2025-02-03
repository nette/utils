<?php

/**
 * Test: Nette\Utils\Html children usage.
 */

declare(strict_types=1);

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('building list with children and indentation', function () {
	$el = Html::el('ul');
	$el->create('li')->setText('one');
	$el->addHtml(Html::el('li')->setText('two'))->class('hello');
	Assert::same('<ul class="hello"><li>one</li><li>two</li></ul>', (string) $el);


	// with indentation
	Assert::match('
		<ul class="hello">
			<li>one</li>

			<li>two</li>
		</ul>
', $el->render(2), 'indentation');
});


test('mixing HTML and text children with array access', function () {
	$el = Html::el(null);
	$el->addHtml(Html::el('p')->setText('one'));
	$el->addText('<p>two</p>');
	$el->addHtml('<p>three</p>');
	Assert::same('<p>one</p>&lt;p&gt;two&lt;/p&gt;<p>three</p>', (string) $el);


	// ==> Get child:
	Assert::true(isset($el[0]));
	Assert::same('<p>one</p>', (string) $el[0]);
	Assert::same('&lt;p&gt;two&lt;/p&gt;', (string) $el[1]);
	Assert::same('<p>three</p>', (string) $el[2]);
	Assert::false(isset($el[3]));
});


test('nested elements in select with optgroup and options', function () {
	$el = Html::el('select');
	$el->create('optgroup')->label('Main')->create('option')->setText('sub one')->create('option')->setText('sub two');
	$el->create('option')->setText('Item');
	Assert::same('<select><optgroup label="Main"><option>sub one<option>sub two</option></option></optgroup><option>Item</option></select>', (string) $el);
	Assert::same(2, count($el));
	Assert::same('optgroup', $el[0]->getName());
	Assert::same('option', $el[1]->getName());
});


test('manipulating children collection', function () {
	$el = Html::el('ul');
	$el->addHtml('li');
	$el->addHtml('li');

	Assert::count(2, $el);
	Assert::count(2, $el->getChildren());
	Assert::count(2, iterator_to_array($el->getIterator()));

	unset($el[1]);
	Assert::count(1, $el->getChildren());

	$el->removeChildren();
	Assert::count(0, $el->getChildren());
	Assert::count(0, iterator_to_array($el->getIterator()));

	Assert::same('<ul></ul>', (string) $el);
});


test('cloning element with children preservation', function () {
	$el = Html::el('ul');
	$el->addHtml(Html::el('li'));

	$el2 = clone $el;

	Assert::same((string) $el, (string) $el2);
});
