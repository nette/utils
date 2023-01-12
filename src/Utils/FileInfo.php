<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


class FileInfo extends \SplFileInfo
{
	private string $relativePath;


	public function __construct(string $file, string $relativePath)
	{
		parent::__construct($file);
		$this->setInfoClass(static::class);
		$this->relativePath = $relativePath;
	}


	/**
	 * Returns the relative directory path.
	 */
	public function getRelativePath(): string
	{
		return $this->relativePath;
	}


	/**
	 * Returns the relative path including file name.
	 */
	public function getRelativePathname(): string
	{
		return ($this->relativePath === '' ? '' : $this->relativePath . DIRECTORY_SEPARATOR)
			. $this->getBasename();
	}


	/**
	 * Returns the contents of the file.
	 * @throws Nette\IOException
	 */
	public function getContents(): string
	{
		$content = @file_get_contents($this->getPathname()); // @ - error escalated to exception
		if ($content === false) {
			throw new Nette\IOException(error_get_last()['message']);
		}
		return $content;
	}
}
