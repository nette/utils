<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;


/**
 * File system tool.
 *
 * @author     David Grudl
 */
class FileSystem
{

	/**
	 * Creates a directory.
	 * @return void
	 */
	public static function createDir($dir, $mode = 0777)
	{
		if (!is_dir($dir) && !@mkdir($dir, $mode, TRUE)) { // intentionally @; not atomic
			throw new Nette\IOException("Unable to create directory '$dir'.");
		}
	}


	/**
	 * Copies a file or directory.
	 * @return void
	 */
	public static function copy($source, $dest, $overwrite = TRUE)
	{
		if (stream_is_local($source) && !file_exists($source)) {
			throw new Nette\IOException("File or directory '$source' not found.");

		} elseif (!$overwrite && file_exists($dest)) {
			throw new Nette\InvalidStateException("File or directory '$dest' already exists.");

		} elseif (is_dir($source)) {
			static::createDir($dest);
			foreach (new \FilesystemIterator($dest) as $item) {
				static::delete($item);
			}
			foreach ($iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item) {
				if ($item->isDir()) {
					static::createDir($dest . '/' . $iterator->getSubPathName());
				} else {
					static::copy($item, $dest . '/' . $iterator->getSubPathName());
				}
			}

		} else {
			static::createDir(dirname($dest));
			if (@stream_copy_to_stream(fopen($source, 'r'), fopen($dest, 'w')) === FALSE) {
				throw new Nette\IOException("Unable to copy file '$source' to '$dest'.");
			}
		}
	}


	/**
	 * Deletes a file or directory.
	 * @return void
	 */
	public static function delete($path)
	{
		if (is_file($path) || is_link($path)) {
			$func = DIRECTORY_SEPARATOR === '\\' && is_dir($path) ? 'rmdir' : 'unlink';
			if (!@$func($path)) {
				throw new Nette\IOException("Unable to delete '$path'.");
			}

		} elseif (is_dir($path)) {
			foreach (new \FilesystemIterator($path) as $item) {
				static::delete($item);
			}
			if (!@rmdir($path)) {
				throw new Nette\IOException("Unable to delete directory '$path'.");
			}
		}
	}


	/**
	 * Renames a file or directory.
	 * @return void
	 */
	public static function rename($name, $newName, $overwrite = TRUE)
	{
		if (!$overwrite && file_exists($newName)) {
			throw new Nette\InvalidStateException("File or directory '$newName' already exists.");

		} elseif (!file_exists($name)) {
			throw new Nette\IOException("File or directory '$name' not found.");

		} else {
			static::createDir(dirname($newName));
			static::delete($newName);
			if (!@rename($name, $newName)) {
				throw new Nette\IOException("Unable to rename file or directory '$name' to '$newName'.");
			}
		}
	}


	/**
	 * Writes a string to a file.
	 * @return bool
	 */
	public static function write($file, $content, $mode = 0666)
	{
		static::createDir(dirname($file));
		if (@file_put_contents($file, $content) === FALSE) {
			throw new Nette\IOException("Unable to write file '$file'.");
		}
		if ($mode !== NULL && !@chmod($file, $mode)) {
			throw new Nette\IOException("Unable to chmod file '$file'.");
		}
	}


	/**
	 * Is path absolute?
	 * @return bool
	 */
	public static function isAbsolute($path)
	{
		return (bool) preg_match('#([a-z]:)?[/\\\\]|[a-z][a-z0-9+.-]*://#Ai', $path);
	}

}
