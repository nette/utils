<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * Finder allows searching through directory trees using iterator.
 *
 * Finder::findFiles('*.php')
 *     ->size('> 10kB')
 *     ->from('.')
 *     ->exclude('temp');
 *
 * @implements \IteratorAggregate<string, FileInfo>
 */
class Finder implements \IteratorAggregate
{
	use Nette\SmartObject;

	/** @var array<array{string, string}> */
	private array $find = [];

	/** @var string[] */
	private array $in = [];

	/** @var \Closure[] */
	private array $filters = [];

	/** @var \Closure[] */
	private array $descentFilters = [];
	private bool $childFirst = false;
	private int $maxDepth = -1;


	/**
	 * Begins search for files and directories matching mask.
	 * @param  string  ...$masks
	 */
	public static function find(...$masks): static
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		return (new static)->addMask($masks, 'dir')->addMask($masks, 'file');
	}


	/**
	 * Begins search for files matching mask.
	 * @param  string  ...$masks
	 */
	public static function findFiles(...$masks): static
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		return (new static)->addMask($masks, 'file');
	}


	/**
	 * Begins search for directories matching mask.
	 * @param  string  ...$masks
	 */
	public static function findDirectories(...$masks): static
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		return (new static)->addMask($masks, 'dir');
	}


	private function addMask(array $masks, string $type): static
	{
		foreach ($masks as $mask) {
			$mask = FileSystem::unixSlashes($mask);
			if ($mask === '') {
				throw new Nette\InvalidArgumentException("Invalid mask '$mask'");
			}
			if (str_starts_with($mask, '**/')) {
				$mask = substr($mask, 3);
			}
			$this->find[] = [$mask, $type];
		}

		return $this;
	}


	/**
	 * Searches in the given directories. Wildcards are allowed.
	 * @param  string  ...$paths
	 */
	public function in(...$paths): static
	{
		$paths = is_array($tmp = reset($paths)) ? $tmp : $paths;
		$this->addLocation($paths, '');
		return $this;
	}


	/**
	 * Searches recursively from the given directories. Wildcards are allowed.
	 * @param  string  ...$paths
	 */
	public function from(...$paths): static
	{
		$paths = is_array($tmp = reset($paths)) ? $tmp : $paths;
		$this->addLocation($paths, '/**');
		return $this;
	}


	private function addLocation(array $paths, string $ext): void
	{
		foreach ($paths as $path) {
			if ($path === '') {
				throw new Nette\InvalidArgumentException("Invalid directory '$path'");
			}
			$path = rtrim(FileSystem::unixSlashes($path), '/');
			$this->in[] = $path . $ext;
		}
	}


	/**
	 * Lists directory's contents before the directory itself. By default, this is disabled.
	 */
	public function childFirst(bool $state = true): static
	{
		$this->childFirst = $state;
		return $this;
	}


	/********************* filtering ****************d*g**/


	/**
	 * Skips entries that matches the given masks relative to the ones defined with the in() or from() methods.
	 * @param  string  ...$masks
	 */
	public function exclude(...$masks): static
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		foreach ($masks as $mask) {
			$mask = FileSystem::unixSlashes($mask);
			if (!preg_match('~^/?(\*\*/)?(.+)(/\*\*|/\*|/|)$~D', $mask, $m)) {
				throw new Nette\InvalidArgumentException("Invalid mask '$mask'");
			}
			$end = $m[3];
			$re = $this->buildPattern($m[2]);
			$filter = fn(FileInfo $file): bool => ($end && !$file->isDir())
				|| !preg_match($re, FileSystem::unixSlashes($file->getRelativePathname()));

			$this->descentFilter($filter);
			if ($end !== '/*') {
				$this->filter($filter);
			}
		}

		return $this;
	}


	/**
	 * Yields only entries which satisfy the given filter.
	 * @param  callable(FileInfo): bool  $callback
	 */
	public function filter(callable $callback): static
	{
		$this->filters[] = \Closure::fromCallable($callback);
		return $this;
	}


	/**
	 * It descends only to directories that match the specified filter.
	 * @param  callable(FileInfo): bool  $callback
	 */
	public function descentFilter(callable $callback): static
	{
		$this->descentFilters[] = \Closure::fromCallable($callback);
		return $this;
	}


	/**
	 * Sets the maximum depth of entries.
	 */
	public function limitDepth(?int $depth): static
	{
		$this->maxDepth = $depth ?? -1;
		return $this;
	}


	/**
	 * Restricts the search by size. $operator accepts "[operator] [size] [unit]" example: >=10kB
	 */
	public function size(string $operator, ?int $size = null): static
	{
		if (func_num_args() === 1) { // in $operator is predicate
			if (!preg_match('#^(?:([=<>!]=?|<>)\s*)?((?:\d*\.)?\d+)\s*(K|M|G|)B?$#Di', $operator, $matches)) {
				throw new Nette\InvalidArgumentException('Invalid size predicate format.');
			}

			[, $operator, $size, $unit] = $matches;
			$units = ['' => 1, 'k' => 1e3, 'm' => 1e6, 'g' => 1e9];
			$size *= $units[strtolower($unit)];
			$operator = $operator ?: '=';
		}

		return $this->filter(fn(FileInfo $file): bool => !$file->isFile() || Helpers::compare($file->getSize(), $operator, $size));
	}


	/**
	 * Restricts the search by modified time. $operator accepts "[operator] [date]" example: >1978-01-23
	 */
	public function date(string $operator, string|int|\DateTimeInterface|null $date = null): static
	{
		if (func_num_args() === 1) { // in $operator is predicate
			if (!preg_match('#^(?:([=<>!]=?|<>)\s*)?(.+)$#Di', $operator, $matches)) {
				throw new Nette\InvalidArgumentException('Invalid date predicate format.');
			}

			[, $operator, $date] = $matches;
			$operator = $operator ?: '=';
		}

		$date = DateTime::from($date)->format('U');
		return $this->filter(fn(FileInfo $file): bool => !$file->isFile() || Helpers::compare($file->getMTime(), $operator, $date));
	}


	/********************* iterator generator ****************d*g**/


	/**
	 * Returns an array with all found files and directories.
	 */
	public function collect(): array
	{
		return iterator_to_array($this->getIterator());
	}


	/** @return \Generator<string, FileInfo> */
	public function getIterator(): \Generator
	{
		$plan = $this->buildPlan();
		foreach ($plan as $dir => $searches) {
			yield from $this->traverseDir($dir, $searches);
		}
	}


	/**
	 * @param  array<\stdClass{pattern: string, mode: string, recursive: bool}>  $searches
	 * @param  string[]  $subdirs
	 * @return \Generator<string, FileInfo>
	 */
	private function traverseDir(string $dir, array $searches, array $subdirs = []): \Generator
	{
		if ($this->maxDepth >= 0 && count($subdirs) > $this->maxDepth) {
			return;
		} elseif (!is_dir($dir)) {
			throw new Nette\InvalidStateException("Directory '$dir' not found.");
		}

		$pathNames = new \FilesystemIterator($dir, \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_PATHNAME | \FilesystemIterator::UNIX_PATHS);
		$relativePath = implode('/', $subdirs);
		$absolute = FileSystem::isAbsolute($dir);

		foreach ($pathNames as $pathName) {
			if (!$absolute) {
				$pathName = preg_replace('~\.?/~A', '', $pathName);
			}
			$pathName = FileSystem::platformSlashes($pathName);
			$file = new FileInfo($pathName, $relativePath);
			$cache = $subSearch = [];

			if ($file->isDir()) {
				foreach ($searches as $search) {
					if ($search->recursive && $this->proveFilters($this->descentFilters, $file, $cache)) {
						$subSearch[] = $search;
					}
				}
			}

			if ($this->childFirst && $subSearch) {
				yield from $this->traverseDir($pathName, $subSearch, array_merge($subdirs, [$file->getBasename()]));
			}

			$relativePathname = FileSystem::unixSlashes($file->getRelativePathname());
			foreach ($searches as $search) {
				if (
					$file->getType() === $search->mode
					&& preg_match($search->pattern, $relativePathname)
					&& $this->proveFilters($this->filters, $file, $cache)
				) {
					yield $pathName => $file;
					break;
				}
			}

			if (!$this->childFirst && $subSearch) {
				yield from $this->traverseDir($pathName, $subSearch, array_merge($subdirs, [$file->getBasename()]));
			}
		}
	}


	private function proveFilters(array $filters, FileInfo $file, array &$cache): bool
	{
		foreach ($filters as $filter) {
			$res = &$cache[spl_object_id($filter)];
			$res ??= $filter($file);
			if (!$res) {
				return false;
			}
		}

		return true;
	}


	/** @return array<string, array<\stdClass{pattern: string, mode: string, recursive: bool}>> */
	private function buildPlan(): array
	{
		$plan = $dirCache = [];
		foreach ($this->find as [$mask, $mode]) {
			$splits = [];
			if (FileSystem::isAbsolute($mask)) {
				if ($this->in) {
					throw new Nette\InvalidStateException("You cannot combine the absolute path in the mask '$mask' and the directory to search '{$this->in[0]}'.");
				}
				$splits[] = self::splitRecursivePart($mask);
			} else {
				foreach ($this->in ?: ['.'] as $in) {
					$in = strtr($in, ['[' => '[[]', ']' => '[]]']); // in path, do not treat [ and ] as a pattern by glob()
					$splits[] = self::splitRecursivePart($in . '/' . $mask);
				}
			}

			foreach ($splits as [$base, $rest, $recursive]) {
				$base = $base === '' ? '.' : $base;
				$dirs = $dirCache[$base] ??= strpbrk($base, '*?[')
					? glob($base, GLOB_NOSORT | GLOB_ONLYDIR | GLOB_NOESCAPE)
					: [strtr($base, ['[[]' => '[', '[]]' => ']'])]; // unescape [ and ]

				$search = (object) ['pattern' => $this->buildPattern($rest), 'mode' => $mode, 'recursive' => $recursive];
				foreach ($dirs as $dir) {
					$plan[$dir][] = $search;
				}
			}
		}

		return $plan;
	}


	/**
	 * Since glob() does not know ** wildcard, we divide the path into a part for glob and a part for manual traversal.
	 */
	private static function splitRecursivePart(string $path): array
	{
		$a = strrpos($path, '/');
		$parts = preg_split('~(?<=^|/)\*\*($|/)~', substr($path, 0, $a + 1), 2);
		return isset($parts[1])
			? [$parts[0], $parts[1] . substr($path, $a + 1), true]
			: [$parts[0], substr($path, $a + 1), false];
	}


	/**
	 * Converts wildcards to regular expression.
	 */
	private function buildPattern(string $mask): string
	{
		if ($mask === '*') {
			return '##';
		} elseif (str_starts_with($mask, './')) {
			$anchor = '^';
			$mask = substr($mask, 2);
		} else {
			$anchor = '(?:^|/)';
		}

		$pattern = strtr(
			preg_quote($mask, '#'),
			[
				'\*\*/' => '(.+/)?',
				'\*' => '[^/]*',
				'\?' => '[^/]',
				'\[\!' => '[^',
				'\[' => '[',
				'\]' => ']',
				'\-' => '-',
			],
		);
		return '#' . $anchor . $pattern . '$#D' . (defined('PHP_WINDOWS_VERSION_BUILD') ? 'i' : '');
	}
}
