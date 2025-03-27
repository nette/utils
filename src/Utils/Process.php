<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * Represents a process, which can be started and controlled (reading output, writing input, waiting for completion).
 */
final class Process
{
	private const PollInterval = 50000;
	private const DefaultTimeout = 60;
	private const StdIn = 0;
	private const StdOut = 1;
	private const StdErr = 2;

	/** @var resource */
	private mixed $process;
	private array $status = ['running' => true];

	/** @var resource */
	private mixed $inputPipe;

	/** @var resource[] */
	private array $outputPipes = [];

	/** @var string[] */
	private array $outputBuffers = [];

	/** @var int[] Number of bytes already read from buffers. */
	private array $outputBufferOffsets = [];
	private float $startTime;


	/**
	 * Starts an executable with given arguments.
	 * @param  string         $executable Path to the executable binary.
	 * @param  string[]       $arguments  Arguments passed to the executable.
	 * @param  string[]|null  $env        Environment variables or null to use the same environment as the current process.
	 * @param  array          $options    Additional options for proc_open(), uses bypass_shell = true by default
	 * @param  mixed          $stdin      Input data (string, resource, Process, or null).
	 * @param  mixed          $stdout     Output target (string filename, resource, false for discard, or null for capture).
	 * @param  mixed          $stderr     Error output target (same as $output).
	 * @param  string|null    $directory  Working directory.
	 * @param  float|null     $timeout    Time limit in seconds.
	 */
	public static function runExecutable(
		string $executable,
		array $arguments = [],
		?array $env = null,
		array $options = [],
		mixed $stdin = '',
		mixed $stdout = null,
		mixed $stderr = null,
		?string $directory = null,
		?float $timeout = self::DefaultTimeout,
	): self
	{
		return new self([$executable, ...$arguments], $env, $options, $directory, $stdin, $stdout, $stderr, $timeout);
	}


	/**
	 * Starts a process from a command string. The command will be interpreted by the shell.
	 * @param  string         $command    Shell command to run.
	 * @param  string[]|null  $env        Environment variables or null to use the same environment as the current process.
	 * @param  array          $options    Options for proc_open().
	 * @param  mixed          $stdin      Input data (string, resource, Process, or null).
	 * @param  mixed          $stdout     Output target (string filename, resource, false for discard, or null for capture).
	 * @param  mixed          $stderr     Error output target (same as $output).
	 * @param  string|null    $directory  Working directory.
	 * @param  float|null     $timeout    Time limit in seconds.
	 */
	public static function runCommand(
		string $command,
		?array $env = null,
		array $options = [],
		mixed $stdin = '',
		mixed $stdout = null,
		mixed $stderr = null,
		?string $directory = null,
		?float $timeout = self::DefaultTimeout,
	): self
	{
		return new self($command, $env, $options, $directory, $stdin, $stdout, $stderr, $timeout);
	}


	private function __construct(
		string|array $command,
		?array $env,
		array $options,
		?string $directory,
		mixed $stdin,
		mixed $stdout,
		mixed $stderr,
		private ?float $timeout,
	) {
		$descriptors = [
			self::StdIn => $this->createInputDescriptor($stdin),
			self::StdOut => $this->createOutputDescriptor(self::StdOut, $stdout),
			self::StdErr => $this->createOutputDescriptor(self::StdErr, $stderr),
		];

		$this->process = @proc_open($command, $descriptors, $pipes, $directory, $env, $options);
		if (!is_resource($this->process)) {
			throw new ProcessFailedException('Failed to start process: ' . Helpers::getLastError());
		}

		[$this->inputPipe, $this->outputPipes[self::StdOut], $this->outputPipes[self::StdErr]] = $pipes + $descriptors;
		$this->writeInitialInput($stdin);
		$this->startTime = microtime(true);
	}


	public function __destruct()
	{
		$this->outputBuffers = [];
		$this->terminate();
	}


	/**
	 * Checks if the process is currently running.
	 */
	public function isRunning(): bool
	{
		if (!$this->status['running']) {
			return false;
		}

		$this->status = proc_get_status($this->process);
		if (!$this->status['running']) {
			$this->close();
		}

		return $this->status['running'];
	}


	/**
	 * Finishes the process by waiting for its completion.
	 * While waiting, periodically checks for output and can invoke a callback with new output chunks.
	 *
	 * @param  (callable(string, string): void)|null  $callback
	 */
	public function wait(?\Closure $callback = null): void
	{
		while ($this->isRunning()) {
			$this->enforceTimeout();
			if ($callback) {
				$this->dispatchCallback($callback);
			}
			usleep(self::PollInterval);
		}

		if ($callback) {
			$this->dispatchCallback($callback);
		}
	}


	/**
	 * Terminates the running process if it is still running.
	 */
	public function terminate(): void
	{
		if (!$this->isRunning()) {
			return;
		} elseif (Helpers::IsWindows) {
			exec("taskkill /F /T /PID {$this->getPid()} 2>&1");
		} else {
			proc_terminate($this->process);
		}
		$this->status['running'] = false;
		$this->close();
	}


	/**
	 * Returns the process exit code. If the process is still running, waits until it finishes.
	 */
	public function getExitCode(): int
	{
		$this->wait();
		return $this->status['exitcode'] ?? -1;
	}


	/**
	 * Returns true if the process terminated with exit code 0.
	 */
	public function isSuccess(): bool
	{
		return $this->getExitCode() === 0;
	}


	/**
	 * Waits for the process to finish and throws ProcessFailedException if exit code is not zero.
	 */
	public function ensureSuccess(): void
	{
		$code = $this->getExitCode();
		if ($code !== 0) {
			throw new ProcessFailedException("Process failed with non-zero exit code: $code");
		}
	}


	/**
	 * Returns the PID of the running process, or null if it is not running.
	 */
	public function getPid(): ?int
	{
		return $this->isRunning() ? $this->status['pid'] : null;
	}


	/**
	 * Reads all remaining output into memory and returns it after waiting for the process to finish.
	 * Output from STDOUT.
	 */
	public function getStdOutput(): string
	{
		$this->wait();
		return $this->outputBuffers[self::StdOut] ?? throw new \LogicException('Cannot read output: output capturing was not enabled');
	}


	/**
	 * Reads all remaining error output into memory and returns it after waiting for the process to finish.
	 * Output from STDERR.
	 */
	public function getStdError(): string
	{
		$this->wait();
		return $this->outputBuffers[self::StdErr] ?? throw new \LogicException('Cannot read output: output capturing was not enabled');
	}


	/**
	 * Returns newly available STDOUT data since the last consumeOutput() call.
	 */
	public function consumeStdOutput(): string
	{
		return $this->consumeBuffer(self::StdOut);
	}


	/**
	 * Returns newly available STDERR data since the last consumeErrorOutput() call.
	 */
	public function consumeStdError(): string
	{
		return $this->consumeBuffer(self::StdErr);
	}


	/**
	 * Returns newly available data from the specified buffer and advances the read pointer.
	 */
	private function consumeBuffer(int $id): string
	{
		if (!isset($this->outputBuffers[$id])) {
			throw new \LogicException('Cannot read output: output capturing was not enabled');
		} elseif ($this->isRunning()) {
			$this->enforceTimeout();
			$this->readFromPipe($id);
		}
		$res = substr($this->outputBuffers[$id], $this->outputBufferOffsets[$id]);
		$this->outputBufferOffsets[$id] = strlen($this->outputBuffers[$id]);
		return $res;
	}


	/**
	 * Writes data into the process' STDIN. If STDIN is closed, throws exception.
	 */
	public function writeStdInput(string $string): void
	{
		if (!is_resource($this->inputPipe)) {
			throw new Nette\InvalidStateException('Cannot write to process: STDIN pipe is closed');
		}
		fwrite($this->inputPipe, $string);
	}


	/**
	 * Closes the STDIN pipe, indicating no more data will be sent.
	 */
	public function closeStdInput(): void
	{
		if (is_resource($this->inputPipe)) {
			fclose($this->inputPipe);
		}
	}


	/**
	 * Called periodically while waiting for process completion to invoke callback with new output/error data.
	 */
	private function dispatchCallback(\Closure $callback): void
	{
		$output = isset($this->outputBuffers[self::StdOut]) ? $this->consumeStdOutput() : '';
		$error = isset($this->outputBuffers[self::StdErr]) ? $this->consumeStdError() : '';
		if ($output !== '' || $error !== '') {
			$callback($output, $error);
		}
	}


	/**
	 * Checks if the timeout has expired. If yes, terminates the process.
	 */
	private function enforceTimeout(): void
	{
		if ($this->timeout !== null && (microtime(true) - $this->startTime) >= $this->timeout) {
			$this->terminate();
			throw new ProcessTimeoutException('Process exceeded the time limit of ' . $this->timeout . ' seconds');
		}
	}


	/**
	 * Reads any new data from the specified pipe and appends it to the buffer.
	 */
	private function readFromPipe(int $id): void
	{
		if (!isset($this->outputBuffers[$id])) {
			return;
		} elseif (Helpers::IsWindows) {
			fseek($this->outputPipes[$id], strlen($this->outputBuffers[$id]));
		} else {
			stream_set_blocking($this->outputPipes[$id], false);
		}
		$this->outputBuffers[$id] .= stream_get_contents($this->outputPipes[$id]);
	}


	/**
	 * Writes initial input data to the process. If input is a string, writes and closes input.
	 * If input is a resource, copies it and closes input. If it is another Process, links outputs (not on Windows).
	 */
	private function writeInitialInput(mixed $input): void
	{
		if ($input === null || $input instanceof self) {
			// keeps input open until closeInput() is called

		} elseif (is_string($input)) {
			fwrite($this->inputPipe, $input);
			$this->closeStdInput();

		} elseif (is_resource($input)) {
			stream_copy_to_stream($input, $this->inputPipe);
			$this->closeStdInput();

		} else {
			throw new Nette\InvalidArgumentException('Input must be string, resource or null, ' . get_debug_type($input) . ' given.');
		}
	}


	/**
	 * Determines the STDIN descriptor based on the type of input.
	 */
	private function createInputDescriptor(mixed $input): mixed
	{
		if (!$input instanceof self) {
			return ['pipe', 'r'];
		} elseif (!Helpers::IsWindows) {
			return $input->outputPipes[self::StdOut];
		} else {
			throw new Nette\NotSupportedException('Process piping is not supported on Windows');
		}
	}


	/**
	 * Determines the descriptor for STDOUT or STDERR based on the specified output target.
	 */
	private function createOutputDescriptor(int $id, mixed $output): mixed
	{
		if (is_resource($output)) {
			return $output;

		} elseif (is_string($output)) {
			return fopen($output, 'w');

		} elseif ($output === false) {
			return ['file', Helpers::IsWindows ? 'NUL' : '/dev/null', 'w'];

		} elseif ($output === null) {
			$this->outputBuffers[$id] = '';
			$this->outputBufferOffsets[$id] = 0;
			// TODO: timeout lze zajisti na windows jedine s tmpfile()
			return Helpers::IsWindows ? tmpfile() : ['pipe', 'w'];

		} else {
			throw new Nette\InvalidArgumentException('Output must be string, resource, bool or null, ' . get_debug_type($output) . ' given.');
		}
	}


	/**
	 * Closes all pipes and the process resource.
	 */
	private function close(): void
	{
		foreach ($this->outputPipes as $id => $_) {
			$this->readFromPipe($id);
		}
		$this->closeStdInput();
		$this->closeOutputPipes();
		proc_close($this->process);
	}


	/**
	 * Closes all pipes. On Windows, tries to remove temporary files associated with them.
	 */
	private function closeOutputPipes(): void
	{
		foreach ($this->outputPipes as $id => &$pipe) {
			if (!is_resource($pipe) || !isset($this->outputBufferOffsets[$id])) { // TODO
				// already closed or not initialized by createOutputDescriptor()
			} elseif (Helpers::IsWindows) {
				$file = stream_get_meta_data($pipe)['uri'];
				fclose($pipe);
				@unlink($file);
			} else {
				fclose($pipe);
			}
		}
	}
}
