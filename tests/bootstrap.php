<?php

// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .

declare(strict_types=1);

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');


// create temporary directory
define('TEMP_DIR', __DIR__ . '/tmp/' . lcg_value());
@mkdir(dirname(TEMP_DIR));
@mkdir(TEMP_DIR);


function test(\Closure $function)
{
	$function();
}


class RemoteStream /* extends \streamWrapper */
{
	public function stream_open()
	{
		return true;
	}


	public function url_stat()
	{
		return false;
	}
}

stream_wrapper_register('remote', RemoteStream::class, STREAM_IS_URL);
