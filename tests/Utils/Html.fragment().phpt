<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Html::fragment()
 */

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('renders children without own tag', function () {
	$el = Html::fragment(
		Html::el('strong')->setText('bold'),
		'plain < text',
		Html::html('<em>raw</em>'),
	);
	Assert::same('<strong>bold</strong>plain &lt; text<em>raw</em>', (string) $el);
});


test('plain strings are escaped, HtmlStringable is not', function () {
	Assert::same('&lt;b&gt;x&lt;/b&gt;', (string) Html::fragment('<b>x</b>'));
	Assert::same('<b>x</b>', (string) Html::fragment(Html::html('<b>x</b>')));
});


test('Stringable and int children are escaped like in addText()', function () {
	$stringable = new class implements Stringable {
		public function __toString(): string
		{
			return 'a < b';
		}
	};
	Assert::same('a &lt; b42', (string) Html::fragment($stringable, 42));
});


test('nulls are skipped', function () {
	$el = Html::fragment(null, 'a', null, 'b', null);
	Assert::same('ab', (string) $el);
	Assert::count(2, $el);
});


test('empty fragment', function () {
	$el = Html::fragment();
	Assert::same('', (string) $el);
	Assert::count(0, $el);
});


test('nested fragment', function () {
	$el = Html::fragment(
		Html::fragment('a', 'b'),
		'c',
	);
	Assert::same('abc', (string) $el);
});


test('array and generator can be spread', function () {
	Assert::same('ab', (string) Html::fragment(...['a', 'b']));

	$gen = (function () {
		yield 'a';
		yield 'b';
	})();
	Assert::same('ab', (string) Html::fragment(...$gen));
});


test('fragment is an ordinary element', function () {
	$el = Html::fragment('a');
	Assert::same('', $el->getName());
	$el->addText('b');
	Assert::same('ab', (string) $el);
});
