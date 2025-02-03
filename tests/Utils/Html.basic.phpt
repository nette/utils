<?php

/**
 * Test: Nette\Utils\Html basic usage.
 */

declare(strict_types=1);

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('basic image element generation', function () {
	$el = Html::el('img')->src('image.gif')->alt('');
	Assert::same('<img src="image.gif" alt="">', (string) $el);
	Assert::same('<img src="image.gif" alt="">', $el->toHtml());
	Assert::same('<img src="image.gif" alt="">', $el->startTag());
	Assert::same('', $el->endTag());
});


test('setting attributes with setAttribute', function () {
	$el = Html::el('img')->setAttribute('src', 'image.gif')->setAttribute('alt', '');
	Assert::same('<img src="image.gif" alt="">', (string) $el);
	Assert::same('<img src="image.gif" alt="">', $el->startTag());
	Assert::same('', $el->endTag());
});


test('accesskey manipulation and removal', function () {
	$el = Html::el('img')->accesskey(0, true)->alt('alt', false);
	Assert::same('<img accesskey="0">', (string) $el);
	Assert::same('<img accesskey="0 1">', (string) $el->accesskey(1, true));
	Assert::same('<img accesskey="0">', (string) $el->accesskey(1, false));
	Assert::same('<img accesskey="0">', (string) $el->accesskey(0, true));
	Assert::same('<img accesskey="0">', (string) $el->accesskey(0));

	unset($el->accesskey);
	Assert::same('<img>', (string) $el);
});


test('appending attribute values', function () {
	$el = Html::el('img')->appendAttribute('accesskey', 0)->setAttribute('alt', false);
	Assert::same('<img accesskey="0">', (string) $el);
	Assert::same('<img accesskey="0 1">', (string) $el->appendAttribute('accesskey', 1));
	Assert::same('<img accesskey="0">', (string) $el->appendAttribute('accesskey', 1, false));
	Assert::same('<img accesskey="0">', (string) $el->appendAttribute('accesskey', 0));
	Assert::same('<img accesskey="0">', (string) $el->setAttribute('accesskey', 0));
	Assert::same('<img>', (string) $el->removeAttribute('accesskey'));
});


test('ignoring text for void element', function () {
	$el = Html::el('img')->src('image.gif')->alt('')->setText('any content');
	Assert::same('<img src="image.gif" alt="">', (string) $el);
	Assert::same('<img src="image.gif" alt="">', $el->startTag());
	Assert::same('', $el->endTag());
});


test('chained attribute methods and property assignments', function () {
	$el = Html::el('img')->setSrc('image.gif')->setAlt('alt1')->setAlt('alt2');
	Assert::same('<img src="image.gif" alt="alt2">', (string) $el);
	Assert::same('image.gif', $el->getSrc());
	Assert::null($el->getTitle());
	Assert::null($el->getAttribute('title'));
	Assert::same('alt2', $el->getAlt());
	Assert::same('alt2', $el->getAttribute('alt'));

	$el->addAlt('alt3');
	Assert::same('<img src="image.gif" alt="alt2 alt3">', (string) $el);


	$el->style = 'float:left';
	$el->class = 'three';
	$el->lang = '';
	$el->title = '0';
	$el->checked = true;
	$el->selected = false;
	$el->name = 'testname';
	$el->setName('span');
	Assert::same('<span src="image.gif" alt="alt2 alt3" style="float:left" class="three" lang="" title="0" checked name="testname"></span>', (string) $el);
});


test('formatting numeric attribute values', function () {
	$el = Html::el('span');
	$el->small = 1e-8;
	$el->big = 1e20;
	Assert::same('<span small="0.00000001" big="100000000000000000000"></span>', (string) $el);
});


test('escaping special characters in attributes', function () {
	Assert::same('<a one=\'"\' two="\'" three="<>" four="&amp;amp;"></a>', (string) Html::el('a')->one('"')->two("'")->three('<>')->four('&amp;'));
	Assert::same('<a one="``xx "></a>', (string) Html::el('a')->one('``xx')); // mXSS
});


class BR implements Nette\HtmlStringable
{
	public function __toString(): string
	{
		return '<br>';
	}
}

test('content setting with text and HTML conversion', function () {
	Assert::same('<p>Hello &amp;ndash; World</p>', (string) Html::el('p')->setText('Hello &ndash; World'));
	Assert::same('<p>Hello &ndash; World</p>', (string) Html::el('p')->setHtml('Hello &ndash; World'));

	Assert::same('<p><br></p>', (string) Html::el('p')->setText(Html::el('br')));
	Assert::same('<p><br></p>', (string) Html::el('p')->setHtml(Html::el('br')));

	Assert::same('<p><br></p>', (string) Html::el('p')->setText(new BR));
	Assert::same('<p><br></p>', (string) Html::el('p')->setHtml(new BR));
});


test('appending text and HTML with stringable content', function () {
	Assert::same('<p>Hello &amp;ndash; World</p>', (string) Html::el('p')->addText('Hello &ndash; World'));
	Assert::same('<p>Hello &ndash; World</p>', (string) Html::el('p')->addHtml('Hello &ndash; World'));

	Assert::same('<p><br></p>', (string) Html::el('p')->addText(Html::el('br')));
	Assert::same('<p><br></p>', (string) Html::el('p')->addHtml(Html::el('br')));

	Assert::same('<p><br></p>', (string) Html::el('p')->addText(new BR));
	Assert::same('<p><br></p>', (string) Html::el('p')->addHtml(new BR));
});


test('child element creation and text extraction', function () {
	$el = Html::el('p')->setHtml('Hello &ndash; World');
	$el->create('a')->setText('link');
	Assert::same('<p>Hello &ndash; World<a>link</a></p>', (string) $el);
	Assert::same('Hello – Worldlink', $el->getText());
	Assert::same('Hello – Worldlink', $el->toText());
});


test('email link obfuscation', function () {
	Assert::same('<a href="mailto:dave&#64;example.com"></a>', (string) Html::el('a')->href('mailto:dave@example.com'));
});


test('URL generation with query parameters', function () {
	Assert::same('<a href="file.php?a=10"></a>', (string) Html::el('a')->href('file.php', ['a' => 10]));
});


test('magic property existence for attributes', function () {
	Assert::false(isset(Html::el('a')->id));
	Assert::true(isset(Html::el('a')->id('')->id));

	Html::el('a')->id = null;
	Assert::false(isset(Html::el('a')->id));
});


test('attribute state after modifications', function () {
	Assert::true(isset(Html::el('a')->setAttribute('id', '')->id));
	Assert::false(isset(Html::el('a')->removeAttribute('id')->id));
	Assert::true(isset(Html::el('a')->setAttribute('id', '')->id));
	Assert::false(isset(Html::el('a')->setAttribute('id', null)->id));
});


test('batch attribute addition and removal', function () {
	$el = Html::el('a')->addAttributes(['onclick' => '', 'onmouseover' => '']);
	Assert::true(isset($el->onclick));
	Assert::true(isset($el->onmouseover));

	$el->removeAttributes(['onclick', 'onmouseover']);
	Assert::false(isset($el->onclick));
	Assert::false(isset($el->onmouseover));
});


test('HTML to plain text conversion', function () {
	Assert::same('hello"', Html::htmlToText('<a href="#">hello&quot;</a>'));
	Assert::same(' text', Html::htmlToText('<!-- comment --> text'));
	Assert::same("' ' ' \"", Html::htmlToText('&apos; &#39; &#x27; &quot;'));
});
