<?php

/**
 * Test: Nette\Utils\Image cloning.
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$original = Image::fromFile(__DIR__ . '/fixtures.images/logo.gif');

$dolly = clone $original;
Assert::notSame($dolly->getImageResource(), $original->getImageResource());
Assert::same($dolly->getHeight(), $original->getHeight());
Assert::same($dolly->getWidth(), $original->getWidth());
Assert::same($dolly->isTrueColor(), $original->isTrueColor());
Assert::same($dolly->toString(Image::PNG), $original->toString(Image::PNG));
