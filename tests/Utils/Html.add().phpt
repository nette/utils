<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Html::add()
 */

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('appends children with fragment semantics', function () {
	$el = Html::el('p')->setText('start');
	$el->add(
		Html::el('strong')->setText('bold'),
		'plain < text',
		null,
		Html::html('<em>raw</em>'),
	);
	Assert::same('<p>start<strong>bold</strong>plain &lt; text<em>raw</em></p>', (string) $el);
});


test('returns the element itself (unlike create)', function () {
	$el = Html::el('div');
	Assert::same($el, $el->add('a', 'b'));
	Assert::same('<div>ab</div>', (string) $el);
});


test('Stringable and int children are escaped like in addText()', function () {
	$stringable = new class implements Stringable {
		public function __toString(): string
		{
			return 'a < b';
		}
	};
	Assert::same('<i>a &lt; b42</i>', (string) Html::el('i')->add($stringable, 42));
});


test('magic add* attribute setters are unaffected', function () {
	$el = Html::el('div')->addClass('btn');
	Assert::same('<div class="btn"></div>', (string) $el);
});
