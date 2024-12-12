<?php

declare(strict_types=1);

use Nette\Utils\Process;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('incremental output consumption', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-f', __DIR__ . '/fixtures.process/incremental.php', 'stdout']);
	usleep(50_000);
	Assert::same('hel', $process->consumeStdOutput());
	usleep(50_000);
	Assert::same('lo', $process->consumeStdOutput());
	usleep(50_000);
	Assert::same('wor', $process->consumeStdOutput());
	usleep(50_000);
	Assert::same('ld', $process->consumeStdOutput());
	$process->wait();
	Assert::same('', $process->consumeStdOutput());
	Assert::same('helloworld', $process->getStdOutput());
});

test('incremental error output consumption', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-f', __DIR__ . '/fixtures.process/incremental.php', 'stderr']);
	usleep(50_000);
	Assert::same('hello' . PHP_EOL, $process->consumeStdError());
	usleep(50_000);
	Assert::same('world' . PHP_EOL, $process->consumeStdError());
	usleep(50_000);
	Assert::same('', $process->consumeStdError());
	Assert::same('hello' . PHP_EOL . 'world' . PHP_EOL, $process->getStdError());
});
