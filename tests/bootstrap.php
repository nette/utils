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


function getPhpCliBinary(): string
{
	if (PHP_SAPI === 'cli') {
		return PHP_BINARY;
	}

	$ext = defined('PHP_WINDOWS_VERSION_BUILD') ? '.exe' : '';
	$path = dirname(PHP_BINARY) . '/php' . $ext;
	if (!is_file($path)) {
		Tester\Environment::skip('No CLI binary of PHP beside ' . PHP_BINARY . '.');
	}

	return $path;
}


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
