<?php

/**
 * @phpExtension gd
 */

declare(strict_types=1);

use Nette\Utils\Image;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::true(Image::isTypeSupported(Image::GIF));
Assert::true(Image::isTypeSupported(Image::JPEG));
Assert::same(function_exists('imagecreatefromwebp'), Image::isTypeSupported(Image::WEBP));
Assert::same(function_exists('imagecreatefromavif'), Image::isTypeSupported(Image::AVIF));

Assert::contains(Image::GIF, Image::getSupportedTypes());
