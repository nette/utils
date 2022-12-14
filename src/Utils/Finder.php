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

	/** @var FinderBatch[] */
	private array $batches = [];
	private FinderBatch $batch;
	private bool $selfFirst = true;
	private bool $sort = false;
	private int $maxDepth = -1;
	private bool $ignoreUnreadableDirs = true;


	public function __construct()
	{
		$this->and();
	}


	/**
	 * Begins search for files and directories matching mask.
	 * @param  string  ...$masks
	 */
	public static function find(...$masks): static
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		return (new static)->files(...$masks)->directories(...$masks);
	}


	/**
	 * Begins search for files matching mask.
	 * @param  string  ...$masks
	 */
	public static function findFiles(...$masks): static
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		return (new static)->files(...$masks);
	}


	/**
	 * Begins search for directories matching mask.
	 * @param  string  ...$masks
	 */
	public static function findDirectories(...$masks): static
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		return (new static)->directories(...$masks);
	}


	/**
	 * Begins search for files matching mask.
	 */
	public function files(string ...$masks): static
	{
		foreach ($masks as $mask) {
			$mask = self::normalizeSlashes($mask);
			if ($mask === '' || str_ends_with($mask, '/')) {
				throw new Nette\InvalidArgumentException("Invalid mask '$mask'");
			}
			if (str_starts_with($mask, '**/')) {
				$mask = substr($mask, 3);
			}
			$this->batch->find[] = [$mask, 'isFile'];
		}

		return $this;
	}


	/**
	 * Begins search for directories matching mask.
	 */
	public function directories(string ...$masks): static
	{
		foreach ($masks as $mask) {
			$mask = rtrim(self::normalizeSlashes($mask), '/');
			if ($mask === '') {
				throw new Nette\InvalidArgumentException("Invalid mask '$mask'");
			}
			if (str_starts_with($mask, '**/')) {
				$mask = substr($mask, 3);
			}
			$this->batch->find[] = [$mask, 'isDir'];
		}

		return $this;
	}


	/**
	 * Searches in the given folder(s).
	 * @param  string  ...$paths
	 */
	public function in(...$paths): static
	{
		$paths = is_array($tmp = reset($paths)) ? $tmp : $paths;
		foreach ($paths as $path) {
			if ($path === '') {
				throw new Nette\InvalidArgumentException("Invalid directory '$path'");
			}
			$path = rtrim(self::normalizeSlashes($path), '/');
			$this->batch->in[] = $path;
		}

		return $this;
	}


	/**
	 * Searches recursively from the given folder(s).
	 * @param  string  ...$paths
	 */
	public function from(...$paths): static
	{
		$paths = is_array($tmp = reset($paths)) ? $tmp : $paths;
		foreach ($paths as $path) {
			if ($path === '') {
				throw new Nette\InvalidArgumentException("Invalid directory '$path'");
			}
			$path = rtrim(self::normalizeSlashes($path), '/');
			$this->batch->in[] = $path . '/**';
		}

		return $this;
	}


	/**
	 * Shows folder content prior to the folder.
	 */
	public function childFirst(): static
	{
		$this->selfFirst = false;
		return $this;
	}


	public function ignoreUnreadableDirs(bool $state = true): static
	{
		$this->ignoreUnreadableDirs = $state;
		return $this;
	}


	public function sortByName(bool $state = true): static
	{
		$this->sort = $state;
		return $this;
	}


	/**
	 * Starts defining a new search group.
	 */
	public function and(): static
	{
		$this->batches[] = $this->batch = new FinderBatch;
		return $this;
	}


	/********************* filtering ****************d*g**/


	/**
	 * Restricts the search using mask.
	 * @param  string  ...$masks
	 */
	public function exclude(...$masks): static
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		foreach ($masks as $mask) {
			$mask = self::normalizeSlashes($mask);
			if (!preg_match('~^/?(\*\*/)?(.+)(/\*\*|/\*|/|)$~D', $mask, $m)) {
				throw new Nette\InvalidArgumentException("Invalid mask '$mask'");
			}
			$end = $m[3];
			$re = $this->buildPattern($m[2]);
			$cb = fn(FileInfo $file): bool => ($end && !$file->isDir())
				|| !preg_match($re, self::normalizeSlashes($file->getRelativePathname()));

			$this->recurseFilter($cb);
			if ($end !== '/*') {
				$this->filter($cb);
			}
		}

		return $this;
	}


	/********************* filtering ****************d*g**/


	/**
	 * Restricts the search using callback.
	 * @param  callable(FileInfo): bool  $callback
	 */
	public function filter(callable $callback): static
	{
		$this->batch->filters[] = $callback;
		return $this;
	}


	/**
	 * Restricts the search using callback.
	 * @param  callable(FileInfo): bool  $callback
	 */
	public function recurseFilter(callable $callback): static
	{
		$this->batch->recurseFilters[] = $callback;
		return $this;
	}


	/**
	 * Limits recursion level.
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


	public function toArray(): array
	{
		return iterator_to_array($this->getIterator());
	}


	/** @return \Generator<string, FileInfo> */
	public function getIterator(): \Generator
	{
		$groups = $this->prepare();

		if ($this->sort) {
			ksort($groups, SORT_NATURAL);
		}

		foreach ($groups as $dir => $searches) {
			yield from $this->traverseDir($dir, $searches);
		}
	}


	/**
	 * @param  string[]  $subDirs
	 * @return \Generator<string, FileInfo>
	 */
	private function traverseDir(string $dir, array $searches, array $subDirs = []): \Generator
	{
		if ($this->maxDepth >= 0 && count($subDirs) > $this->maxDepth) {
			return;
		} elseif (!is_dir($dir)) {
			throw new Nette\InvalidStateException("Directory '$dir' not found.");
		}

		try {
			$items = new \FilesystemIterator($dir, \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_PATHNAME);
		} catch (\UnexpectedValueException $e) {
			if ($this->ignoreUnreadableDirs) {
				return;
			} else {
				throw new Nette\InvalidStateException($e->getMessage());
			}
		}

		if ($this->sort) {
			$items = iterator_to_array($items);
			natsort($items);
		}

		$relativePath = implode(DIRECTORY_SEPARATOR, $subDirs);

		foreach ($items as $pathName) {
			if (str_starts_with($pathName, '//')) {
				$pathName = substr($pathName, 1); // on windows when $dir = '/'
			}
			$pathName = self::normalizeSlashes($pathName, true);
			$file = new FileInfo($pathName, $relativePath);
			$cache = [];
			$into = [];

			if ($file->isDir()) {
				foreach ($searches as $search) {
					if (
						$search->recursive
						&& $this->invokeFilters($search->batch->recurseFilters, $file, $cache)
					) {
						$into[] = $search;
					}
				}
			}

			if (!$this->selfFirst && $into) {
				yield from $this->traverseDir($pathName, $into, array_merge($subDirs, [$file->getBasename()]));
			}

			$relativePathname = self::normalizeSlashes($file->getRelativePathname());
			foreach ($searches as $search) {
				if (
					$file->{$search->mode}()
					&& preg_match($search->find, $relativePathname)
					&& $this->invokeFilters($search->batch->filters, $file, $cache)
				) {
					yield $pathName => $file;
					break;
				}
			}

			if ($this->selfFirst && $into) {
				yield from $this->traverseDir($pathName, $into, array_merge($subDirs, [$file->getBasename()]));
			}
		}
	}


	private function invokeFilters(array $filters, FileInfo $file, array &$cache): bool
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


	private function prepare(): array
	{
		$groups = [];
		foreach ($this->batches as $batch) {
			foreach ($batch->find as [$mask, $mode]) {
				if (FileSystem::isAbsolute($mask)) {
					if ($batch->in) {
						throw new Nette\InvalidStateException("You cannot combine the absolute path in the mask '$mask' and the directory to search '{$batch->in[0]}'.");
					}
					[$base, $rest, $recursive] = self::splitRecursivePart($mask);
					$groups[$base][] = (object) ['find' => $this->buildPattern($rest), 'mode' => $mode, 'recursive' => $recursive, 'batch' => $batch];
				} else {
					foreach ($batch->in ?: ['.'] as $in) {
						[$base, $rest, $recursive] = self::splitRecursivePart($in . '/' . $mask);
						$groups[$base][] = (object) ['find' => $this->buildPattern($rest), 'mode' => $mode, 'recursive' => $recursive, 'batch' => $batch];
					}
				}
			}
		}

		$expanded = [];
		foreach ($groups as $dir => $searches) {
			if (!is_dir($dir) && strpbrk($dir, '*?[')) {
				foreach (glob($dir, GLOB_NOSORT | GLOB_ONLYDIR | GLOB_NOESCAPE) as $dir) {
					$expanded[$dir] = $searches;
				}
			} else {
				$expanded[$dir] = $searches;
			}
		}

		return $expanded;
	}


	private static function splitRecursivePart(string $path): array
	{
		$a = strrpos($path, '/');
		$parts = preg_split('~(?<=/)\*\*($|/)~', substr($path, 0, $a + 1), 2);
		return isset($parts[1])
			? [$parts[0], $parts[1] . substr($path, $a + 1), true]
			: [$parts[0], substr($path, $a + 1), false];
	}


	/**
	 * Converts wild chars to regular expression.
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


	private static function normalizeSlashes(string $path, bool $native = false): string
	{
		return !$native || DIRECTORY_SEPARATOR === '/'
			? strtr($path, '\\', '/')
			: str_replace(':\\\\', '://', strtr($path, '/', '\\'));
	}
}
