<?php

declare(strict_types=1);

use Nette\Utils\Process;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// Environment variables

test('environment variables', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo getenv("TEST_VAR");'], env: ['TEST_VAR' => '123']);
	Assert::same('123', $process->getStdOutput());
});

test('no environment variables', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo !getenv("PATH") ? "ok" : "no";'], env: []);
	Assert::same('ok', $process->getStdOutput());
});

test('parent environment variables', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo getenv("PATH") ? "ok" : "no";']);
	Assert::same('ok', $process->getStdOutput());
});
