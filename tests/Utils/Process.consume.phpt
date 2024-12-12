<?php declare(strict_types=1);

use Nette\Utils\Process;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * Reads the output incrementally: polls while the process runs, then reads the final chunk.
 * The fixture flushes one byte every 50 ms and we poll every 20 ms, so the data reliably
 * arrives in several chunks rather than all at once.
 * @return string[]  the non-empty chunks in the order they were received
 */
$drain = function (Process $process, callable $consume): array {
	$chunks = [];
	do {
		usleep(20_000);
		if (($chunk = $consume($process)) !== '') {
			$chunks[] = $chunk;
		}
	} while ($process->isRunning());

	if (($chunk = $consume($process)) !== '') { // the part produced after the process finished
		$chunks[] = $chunk;
	}
	return $chunks;
};


test('incremental output consumption', function () use ($drain) {
	$process = Process::runExecutable(PHP_BINARY, ['-f', __DIR__ . '/fixtures.process/incremental.php', 'stdout']);
	$chunks = $drain($process, fn(Process $p) => $p->consumeStdOutput());

	Assert::same('helloworld', implode($chunks));
	Assert::true(count($chunks) > 1, 'output should arrive in several chunks');
	Assert::same('', $process->consumeStdOutput());
	Assert::same('helloworld', $process->getStdOutput());
});

test('incremental error output consumption', function () use ($drain) {
	$process = Process::runExecutable(PHP_BINARY, ['-f', __DIR__ . '/fixtures.process/incremental.php', 'stderr']);
	$chunks = $drain($process, fn(Process $p) => $p->consumeStdError());

	Assert::same('hello' . PHP_EOL . 'world' . PHP_EOL, implode($chunks));
	Assert::true(count($chunks) > 1, 'error output should arrive in several chunks');
	Assert::same('', $process->consumeStdError());
	Assert::same('hello' . PHP_EOL . 'world' . PHP_EOL, $process->getStdError());
});


// TODO: Process::run() and Process::ensure() convenience methods
