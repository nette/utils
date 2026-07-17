<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Html::text() and Html::html() static factories.
 */

use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('Html::text() escapes content', function () {
	$el = Html::text('a < b');
	Assert::type(Html::class, $el);
	Assert::same('', $el->getName());
	Assert::same('a &lt; b', (string) $el);
});


test('Html::html() keeps raw HTML', function () {
	$el = Html::html('<b>x</b>');
	Assert::type(Html::class, $el);
	Assert::same('<b>x</b>', (string) $el);
});


test('unknown static method throws', function () {
	Assert::exception(
		fn() => Html::foo('x'),
		Nette\MemberAccessException::class,
		'Call to undefined static method Nette\Utils\Html::foo()%a?%',
	);
});


test('instance call $el->text() / $el->html() triggers deprecation and still sets attribute', function () {
	$el = Html::el('span');
	Assert::error(
		fn() => $el->text('x'),
		E_USER_DEPRECATED,
		"Method \$el->text() is deprecated, use setText() for content or setAttribute() for the 'text' attribute; Html::text() is a static factory.",
	);
	Assert::same('x', $el->attrs['text']);

	Assert::error(
		fn() => $el->html('y'),
		E_USER_DEPRECATED,
		"Method \$el->html() is deprecated, use setHtml() for content or setAttribute() for the 'html' attribute; Html::html() is a static factory.",
	);
	Assert::same('y', $el->attrs['html']);
});


test('static factory called from inside instance context goes to __call', function () {
	$el = new class extends Html {
		public function build(): void
		{
			Html::text('x'); // object context wins, ends up in __call
		}
	};
	Assert::error(
		fn() => $el->build(),
		E_USER_DEPRECATED,
	);
});
