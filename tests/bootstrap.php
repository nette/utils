<?php declare(strict_types=1);

// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .

if (@!include __DIR__ . '/../vendor/autoload.php') { // @ dependencies may not be installed
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


// configure environment
Tester\Environment::setup();
Tester\Environment::setupFunctions();


function getTempDir(): string
{
	$dir = __DIR__ . '/tmp/' . getmypid();

	if (empty($GLOBALS['\lock'])) {
		// garbage collector
		$GLOBALS['\lock'] = $lock = fopen(__DIR__ . '/lock', 'w');
		if (rand(0, 100)) {
			flock($lock, LOCK_SH);
			@mkdir(dirname($dir)); // @ directory may already exist
		} elseif (flock($lock, LOCK_EX)) {
			Tester\Helpers::purge(dirname($dir));
		}

		@mkdir($dir); // @ directory may already exist
	}

	return $dir;
}


/**
 * Compares the pixels of the images, as the bytes of a PNG depend on the version of GD.
 */
function assertImage(string $expectedFile, Nette\Utils\Image $actual): void
{
	$normalize = function (Nette\Utils\Image $image): string {
		$image = clone $image;
		$image->paletteToTrueColor();
		return $image->toString(Nette\Utils\Image::PNG);
	};
	Tester\Assert::same($normalize(Nette\Utils\Image::fromFile($expectedFile)), $normalize($actual));
}
