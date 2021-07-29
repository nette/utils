<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * File system tool.
 */
final class FileSystem
{
	use Nette\StaticClass;

	/**
	 * Creates a directory if it doesn't exist.
	 * @throws Nette\IOException  on error occurred
	 */
	public static function createDir(string $dir, int $mode = 0777): void
	{
		if (!is_dir($dir) && !@mkdir($dir, $mode, true)) { // @ - dir may already exist
			clearstatcache(true);
			if (!is_dir($dir)) {
				throw new Nette\IOException("Unable to create directory '$dir' with mode " . decoct($mode) . '. ' . Helpers::getLastError());
			}
		}
	}


	/**
	 * Copies a file or a directory. Overwrites existing files and directories by default.
	 * @throws Nette\IOException  on error occurred
	 * @throws Nette\InvalidStateException  if $overwrite is set to false and destination already exists
	 */
	public static function copy(string $origin, string $target, bool $overwrite = true): void
	{
		if (stream_is_local($origin) && !file_exists($origin)) {
			throw new Nette\IOException("File or directory '$origin' not found.");

		} elseif (!$overwrite && file_exists($target)) {
			throw new Nette\InvalidStateException("File or directory '$target' already exists.");

		} elseif (is_dir($origin)) {
			static::createDir($target);
			foreach (new \FilesystemIterator($target) as $item) {
				static::delete($item->getPathname());
			}
			foreach ($iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($origin, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item) {
				if ($item->isDir()) {
					static::createDir($target . '/' . $iterator->getSubPathName());
				} else {
					static::copy($item->getPathname(), $target . '/' . $iterator->getSubPathName());
				}
			}

		} else {
			static::createDir(dirname($target));
			if (
				($s = @fopen($origin, 'rb'))
				&& ($d = @fopen($target, 'wb'))
				&& @stream_copy_to_stream($s, $d) === false
			) { // @ is escalated to exception
				throw new Nette\IOException("Unable to copy file '$origin' to '$target'. " . Helpers::getLastError());
			}
		}
	}


	/**
	 * Deletes a file or directory if exists.
	 * @throws Nette\IOException  on error occurred
	 */
	public static function delete(string $path): void
	{
		if (is_file($path) || is_link($path)) {
			$func = DIRECTORY_SEPARATOR === '\\' && is_dir($path) ? 'rmdir' : 'unlink';
			if (!@$func($path)) { // @ is escalated to exception
				throw new Nette\IOException("Unable to delete '$path'. " . Helpers::getLastError());
			}

		} elseif (is_dir($path)) {
			foreach (new \FilesystemIterator($path) as $item) {
				static::delete($item->getPathname());
			}
			if (!@rmdir($path)) { // @ is escalated to exception
				throw new Nette\IOException("Unable to delete directory '$path'. " . Helpers::getLastError());
			}
		}
	}


	/**
	 * Renames or moves a file or a directory. Overwrites existing files and directories by default.
	 * @throws Nette\IOException  on error occurred
	 * @throws Nette\InvalidStateException  if $overwrite is set to false and destination already exists
	 */
	public static function rename(string $origin, string $target, bool $overwrite = true): void
	{
		if (!$overwrite && file_exists($target)) {
			throw new Nette\InvalidStateException("File or directory '$target' already exists.");

		} elseif (!file_exists($origin)) {
			throw new Nette\IOException("File or directory '$origin' not found.");

		} else {
			static::createDir(dirname($target));
			if (realpath($origin) !== realpath($target)) {
				static::delete($target);
			}
			if (!@rename($origin, $target)) { // @ is escalated to exception
				throw new Nette\IOException("Unable to rename file or directory '$origin' to '$target'. " . Helpers::getLastError());
			}
		}
	}


	/**
	 * Reads the content of a file.
	 * @throws Nette\IOException  on error occurred
	 */
	public static function read(string $file): string
	{
		$content = @file_get_contents($file); // @ is escalated to exception
		if ($content === false) {
			throw new Nette\IOException("Unable to read file '$file'. " . Helpers::getLastError());
		}
		return $content;
	}


	/**
	 * Writes the string to a file.
	 * @throws Nette\IOException  on error occurred
	 */
	public static function write(string $file, string $content, ?int $mode = 0666): void
	{
		static::createDir(dirname($file));
		if (@file_put_contents($file, $content) === false) { // @ is escalated to exception
			throw new Nette\IOException("Unable to write file '$file'. " . Helpers::getLastError());
		}
		if ($mode !== null && !@chmod($file, $mode)) { // @ is escalated to exception
			throw new Nette\IOException("Unable to chmod file '$file' to mode " . decoct($mode) . '. ' . Helpers::getLastError());
		}
	}


	/**
	 * Determines if the path is absolute.
	 */
	public static function isAbsolute(string $path): bool
	{
		return (bool) preg_match('#([a-z]:)?[/\\\\]|[a-z][a-z0-9+.-]*://#Ai', $path);
	}


	/**
	 * Normalizes `..` and `.` and directory separators in path.
	 */
	public static function normalizePath(string $path): string
	{
		$parts = $path === '' ? [] : preg_split('~[/\\\\]+~', $path);
		$res = [];
		foreach ($parts as $part) {
			if ($part === '..' && $res && end($res) !== '..' && end($res) !== '') {
				array_pop($res);
			} elseif ($part !== '.') {
				$res[] = $part;
			}
		}
		return $res === ['']
			? DIRECTORY_SEPARATOR
			: implode(DIRECTORY_SEPARATOR, $res);
	}


	/**
	 * Joins all segments of the path and normalizes the result.
	 */
	public static function joinPaths(string ...$paths): string
	{
		return self::normalizePath(implode('/', $paths));
	}
}
