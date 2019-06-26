<?php

/**
 * Test: Nette\Utils\Image save method exceptions.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$main = Image::fromFile(__DIR__ . '/fixtures.images/alpha1.png');


test(function () use ($main) {
	Assert::true(is_string((string) $main));
});
