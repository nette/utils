<?php

/**
 * Test: Nette\Utils\Html basic usage.
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Html;


require __DIR__ . '/../bootstrap.php';


test(function() {
	Html::$xhtml = TRUE;
	$el = Html::el('img')->src('image.gif')->alt('');
	Assert::same( '<img src="image.gif" alt="" />', (string) $el );
	Assert::same( '<img src="image.gif" alt="" />', $el->startTag() );
	Assert::same( '', $el->endTag() );
});


test(function() {
	Html::$xhtml = TRUE;
	$el = Html::el('img')->accesskey(0, TRUE)->alt('alt', FALSE);
	Assert::same( '<img accesskey="0" />', (string) $el );
	Assert::same( '<img accesskey="0 1" />', (string) $el->accesskey(1, TRUE) );
	Assert::same( '<img accesskey="0" />', (string) $el->accesskey(0) );
});


test(function() {
	$el = Html::el('img')->src('image.gif')->alt('')->setText(NULL)->setText('any content');
	Assert::same( '<img src="image.gif" alt="" />', (string) $el );
	Assert::same( '<img src="image.gif" alt="" />', $el->startTag() );
	Assert::same( '', $el->endTag() );

	Html::$xhtml = FALSE;
	Assert::same( '<img src="image.gif" alt="">', (string) $el );
});


test(function() {
	Html::$xhtml = FALSE;
	$el = Html::el('img')->setSrc('image.gif')->setAlt('alt1')->setAlt('alt2');
	Assert::same( '<img src="image.gif" alt="alt2">', (string) $el );
	Assert::same( 'image.gif', $el->getSrc() );
	Assert::null( $el->getTitle() );
	Assert::same( 'alt2', $el->getAlt() );

	$el->addAlt('alt3');
	Assert::same( '<img src="image.gif" alt="alt2 alt3">', (string) $el );


	$el->style = 'float:left';
	$el->class = 'three';
	$el->lang = '';
	$el->title = '0';
	$el->checked = TRUE;
	$el->selected = FALSE;
	$el->name = 'testname';
	$el->setName('span');
	Assert::same( '<span src="image.gif" alt="alt2 alt3" style="float:left" class="three" lang="" title="0" checked name="testname"></span>', (string) $el );
});


test(function() { // attributes escaping
	Assert::same( '<a one=\'"\' two="\'" three="<>" four="&amp;amp;"></a>', (string) Html::el('a')->one('"')->two("'")->three('<>')->four('&amp;') );
});


test(function() { // setText vs. setHtml
	Assert::same( '<p>Hello &amp;ndash; World</p>', (string) Html::el('p')->setText('Hello &ndash; World') );
	Assert::same( '<p>Hello &ndash; World</p>', (string) Html::el('p')->setHtml('Hello &ndash; World') );
});


test(function() { // getText vs. getHtml
	$el = Html::el('p')->setHtml('Hello &ndash; World');
	$el->create('a')->setText('link');
	Assert::same( '<p>Hello &ndash; World<a>link</a></p>', (string) $el );
	Assert::same( 'Hello – Worldlink', $el->getText() );
});


test(function() { // email obfuscate
	Assert::same( '<a href="mailto:dave&#64;example.com"></a>', (string) Html::el('a')->href('mailto:dave@example.com') );
});


test(function() { // href with query
	Assert::same( '<a href="file.php?a=10"></a>', (string) Html::el('a')->href('file.php', array('a' => 10)) );
});


test(function() { // isset
	Assert::false( isset(Html::el('a')->id) );
	Assert::true( isset(Html::el('a')->id('')->id) );
});
