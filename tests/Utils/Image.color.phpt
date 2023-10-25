<?php

/**
 * Test: Nette\Utils\ImageColor
 */

declare(strict_types=1);

use Nette\Utils\ImageColor;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('hex()', function () {
	Assert::equal(ImageColor::rgb(17, 34, 51, 1.0), ImageColor::hex('#123'));
	Assert::equal(ImageColor::rgb(17, 34, 51, 0.8 / 3), ImageColor::hex('#1234'));
	Assert::equal(ImageColor::rgb(18, 52, 86), ImageColor::hex('#123456'));
	Assert::equal(ImageColor::rgb(18, 52, 86, 120 / 255), ImageColor::hex('#12345678'));

	Assert::exception(
		fn() => ImageColor::hex('#12'),
		InvalidArgumentException::class,
		'Invalid hex color format.',
	);
});

test('toRGBA()', function () {
	Assert::same((ImageColor::rgb(0, 1, 2, 0.3))->toRGBA(), [0, 1, 2, 89]);
	Assert::same((ImageColor::rgb(1000, -1, 1000, -10))->toRGBA(), [255, 0, 255, 127]);
});
