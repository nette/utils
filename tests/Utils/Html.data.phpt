<?php

/**
 * Test: Nette\Utils\Html user data attribute.
 *
 * @author     David Grudl
 */

use Nette\Utils\Html,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() { // standard usage in combination with normal attributes
	$el = Html::el('div', array('id' => 'id', 'data-will-be' => 'overwritten'));
	$el->data = array('x' => 'x');
	$el->data->testAttr = 'test';

	Assert::type( 'Nette\Utils\HtmlDataset', $el->data );
	Assert::same( 2, count($el->data) );
	Assert::same( '<div id="id" data-x="x" data-test-attr="test"></div>', (string) $el );
});


test(function() { // function
	$el = Html::el('div', array('data-will-be' => 'overwritten'));
	$el->data(array('a' => 'one'));
	$el->data('b', 'two');
	$el->setData('c', 'three');
	$el->addData(array('d' => 'four'));

	Assert::type( 'Nette\Utils\HtmlDataset', $el->getData() );
	Assert::same( 4, count($el->getData()) );
	Assert::same( 'one', $el->getData('a') );
	Assert::same( '<div data-a="one" data-b="two" data-c="three" data-d="four"></div>', (string) $el );
});


test(function() { // simple data attribute
	$el = Html::el('div');
	$el->data = 'simple';

	Assert::same( '<div data="simple"></div>', (string) $el );
});


test(function() { // backward compatibility: direct assignment using dash-separated name
	$el = Html::el('div');

	$el->{'data-test-attr'} = 'test';
	Assert::type( 'Nette\Utils\HtmlDataset', $el->data );
	Assert::same( 1, count($el->data) );
	Assert::same( 'test', $el->data->testAttr );
	Assert::true( isset($el->{'data-test-attr'}) );
	Assert::same( 'test', $el->{'data-test-attr'} );

	unset($el->{'data-test-attr'});
	Assert::false( isset($el->{'data-test-attr'}) );
});
