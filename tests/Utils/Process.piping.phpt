<?php

declare(strict_types=1);

use Nette\Utils\Helpers;
use Nette\Utils\Process;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


if (Helpers::IsWindows) {
	Tester\Environment::skip('Process piping is not supported on Windows.');
}

$process1 = Process::runExecutable(
	PHP_BINARY,
	['-f', __DIR__ . '/fixtures.process/tick.php'],
);

$process2 = Process::runExecutable(
	PHP_BINARY,
	['-f', __DIR__ . '/fixtures.process/rev.php'],
	stdin: $process1,
);

$output = '';
$process2->wait(function ($stdOut, $stdErr) use (&$output) {
	$output .= $stdOut;
});

Assert::same('kcit' . PHP_EOL . 'kcit' . PHP_EOL . 'kcit' . PHP_EOL, $output);
