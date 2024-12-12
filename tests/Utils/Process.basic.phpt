<?php declare(strict_types=1);

use Nette\Utils\Helpers;
use Nette\Utils\Process;
use Nette\Utils\ProcessFailedException;
use Nette\Utils\ProcessTimeoutException;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// Process execution - success

test('run executable successfully', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo "hello";']);
	Assert::true($process->isSuccess());
	Assert::same(0, $process->getExitCode());
	Assert::same('hello', $process->getStdOutput());
	Assert::same('', $process->getStdError());
});

test('run command successfully', function () {
	$process = Process::runCommand('echo hello');
	Assert::true($process->isSuccess());
	Assert::same(0, $process->getExitCode());
	Assert::same('hello' . PHP_EOL, $process->getStdOutput());
	Assert::same('', $process->getStdError());
});


// Process execution - errors

test('run executable with error', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'exit(1);']);
	Assert::false($process->isSuccess());
	Assert::same(1, $process->getExitCode());
});

test('run executable ensure success throws exception on error', function () {
	Assert::exception(
		fn() => Process::runExecutable(PHP_BINARY, ['-r', 'exit(1);'])->ensureSuccess(),
		ProcessFailedException::class,
		'Process failed with non-zero exit code: 1',
	);
});

test('ensureSuccess() does not throw on success', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo "ok";']);
	$process->ensureSuccess();
	Assert::same('ok', $process->getStdOutput());
});

test('run command with error', function () {
	$process = Process::runCommand('"' . PHP_BINARY . '" -r "exit(1);"');
	Assert::false($process->isSuccess());
	Assert::same(1, $process->getExitCode());
});

test('run command ensure success throws exception on error', function () {
	Assert::exception(
		fn() => Process::runCommand('"' . PHP_BINARY . '" -r "exit(1);"')->ensureSuccess(),
		ProcessFailedException::class,
		'Process failed with non-zero exit code: 1',
	);
});


// Process state monitoring

test('is running', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'sleep(1);']);
	Assert::true($process->isRunning());
	$process->wait();
	Assert::false($process->isRunning());
});

test('get pid', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'sleep(1);']);
	Assert::type('int', $process->getPid());
	$process->wait();
	Assert::null($process->getPid());
});


// Waiting for process

test('wait', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo "hello";']);
	$process->wait();
	$process->wait();
	Assert::false($process->isRunning());
	Assert::same(0, $process->getExitCode());
	Assert::same('hello', $process->getStdOutput());
});

test('wait with callback', function () {
	$output = '';
	$error = '';
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo "hello"; fwrite(STDERR, "error");']);
	$process->wait(function ($stdOut, $stdErr) use (&$output, &$error) {
		$output .= $stdOut;
		$error .= $stdErr;
	});
	Assert::same('hello', $output);
	Assert::same('error', $error);
});


// Automatically call wait()

test('getStdOutput() automatically call wait()', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo "hello";']);
	Assert::same('hello', $process->getStdOutput());
	Assert::false($process->isRunning());
});

test('getExitCode() automatically call wait()', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'exit(2);']);
	Assert::same(2, $process->getExitCode());
	Assert::false($process->isRunning());
});

test('reads large output without deadlocking', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo str_repeat("a", 1_000_000);']);
	Assert::same(1_000_000, strlen($process->getStdOutput()));
});


// Terminating process

test('terminate', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'sleep(5);']);
	$process->terminate();
	Assert::false($process->isRunning());
});

test('terminate() and then wait()', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'sleep(5);']);
	$process->terminate();
	$process->wait();
	Assert::false($process->isRunning());
});

test('getExitCode() after terminate()', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'sleep(5);']);
	$process->terminate();
	Assert::type('int', $process->getExitCode());
	Assert::false($process->isSuccess());
});

test('terminate() does not hang on a process that ignores SIGTERM', function () {
	if (!function_exists('pcntl_signal')) {
		Tester\Environment::skip('Requires the pcntl extension.');
	}
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'pcntl_async_signals(true); pcntl_signal(SIGTERM, fn() => null); while (true) sleep(1);']);
	usleep(100_000); // let the child install the handler
	$process->terminate(); // would hang in proc_close() if only SIGTERM were sent
	Assert::false($process->isRunning());
});


// Timeout

test('timeout', function () {
	Assert::exception(
		fn() => Process::runExecutable(PHP_BINARY, ['-r', 'sleep(5);'], timeout: 0.1)->wait(),
		ProcessTimeoutException::class,
		'Process exceeded the time limit of 0.1 seconds',
	);
});


// bypass_shell

if (Helpers::IsWindows) {
	test('bypass_shell = false', function () {
		$process = Process::runCommand('"' . PHP_BINARY . '" -r "echo 123;"', options: ['bypass_shell' => false]);
		Assert::same('123', $process->getStdOutput());
	});
}
