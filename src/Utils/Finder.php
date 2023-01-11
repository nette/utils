<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;


/**
 * Finder allows searching through directory trees using iterator.
 *
 * <code>
 * Finder::findFiles('*.php')
 *     ->size('> 10kB')
 *     ->from('.')
 *     ->exclude('temp');
 * </code>
 *
 * @implements \IteratorAggregate<string, \SplFileInfo>
 */
class Finder implements \IteratorAggregate
{
	use Nette\SmartObject;

	private array $paths = [];
	private array $groups = [];
	private array $exclude = [];
	private int $order = RecursiveIteratorIterator::SELF_FIRST;
	private int $maxDepth = -1;
	private ?array $cursor;


	/**
	 * Begins search for files and directories matching mask.
	 * @param  string  ...$masks
	 * @return static
	 */
	public static function find(...$masks): self
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		return (new static)->select($masks, 'isDir')->select($masks, 'isFile');
	}


	/**
	 * Begins search for files matching mask.
	 * @param  string  ...$masks
	 * @return static
	 */
	public static function findFiles(...$masks): self
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		return (new static)->select($masks, 'isFile');
	}


	/**
	 * Begins search for directories matching mask.
	 * @param  string  ...$masks
	 * @return static
	 */
	public static function findDirectories(...$masks): self
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		return (new static)->select($masks, 'isDir');
	}


	/**
	 * Creates filtering group by mask & type selector.
	 * @return static
	 */
	private function select(array $masks, string $type): self
	{
		$this->cursor = &$this->groups[];
		$pattern = self::buildPattern($masks);
		$this->filter(fn(RecursiveDirectoryIterator $file): bool => !$file->isDot()
				&& $file->$type()
				&& (!$pattern || preg_match($pattern, '/' . strtr($file->getSubPathName(), '\\', '/'))));
		return $this;
	}


	/**
	 * Searches in the given folder(s).
	 * @param  string  ...$paths
	 * @return static
	 */
	public function in(...$paths): self
	{
		$this->maxDepth = 0;
		return $this->from(...$paths);
	}


	/**
	 * Searches recursively from the given folder(s).
	 * @param  string  ...$paths
	 * @return static
	 */
	public function from(...$paths): self
	{
		if ($this->paths) {
			throw new Nette\InvalidStateException('Directory to search has already been specified.');
		}

		$this->paths = is_array($tmp = reset($paths)) ? $tmp : $paths;
		$this->cursor = &$this->exclude;
		return $this;
	}


	/**
	 * Shows folder content prior to the folder.
	 * @return static
	 */
	public function childFirst(): self
	{
		$this->order = RecursiveIteratorIterator::CHILD_FIRST;
		return $this;
	}


	/**
	 * Converts Finder pattern to regular expression.
	 */
	private static function buildPattern(array $masks): ?string
	{
		$pattern = [];
		foreach ($masks as $mask) {
			$mask = rtrim(strtr($mask, '\\', '/'), '/');
			$prefix = '';
			if ($mask === '') {
				continue;

			} elseif ($mask === '*') {
				return null;

			} elseif ($mask[0] === '/') { // absolute fixing
				$mask = ltrim($mask, '/');
				$prefix = '(?<=^/)';
			}

			$pattern[] = $prefix . strtr(
				preg_quote($mask, '#'),
				['\*\*' => '.*', '\*' => '[^/]*', '\?' => '[^/]', '\[\!' => '[^', '\[' => '[', '\]' => ']', '\-' => '-'],
			);
		}

		return $pattern ? '#/(' . implode('|', $pattern) . ')$#Di' : null;
	}


	/********************* iterator generator ****************d*g**/


	/**
	 * Returns iterator.
	 */
	public function getIterator(): \Iterator
	{
		if (!$this->paths) {
			throw new Nette\InvalidStateException('Call in() or from() to specify directory to search.');

		} elseif (count($this->paths) === 1) {
			return $this->buildIterator((string) $this->paths[0]);
		}

		$iterator = new \AppendIterator;
		foreach ($this->paths as $path) {
			$iterator->append($this->buildIterator((string) $path));
		}

		return $iterator;
	}


	/**
	 * Returns per-path iterator.
	 */
	private function buildIterator(string $path): \Iterator
	{
		$iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::FOLLOW_SYMLINKS);

		if ($this->exclude) {
			$iterator = new \RecursiveCallbackFilterIterator($iterator, function ($foo, $bar, RecursiveDirectoryIterator $file): bool {
				if (!$file->isDot() && !$file->isFile()) {
					foreach ($this->exclude as $filter) {
						if (!$filter($file)) {
							return false;
						}
					}
				}

				return true;
			});
		}

		if ($this->maxDepth !== 0) {
			$iterator = new RecursiveIteratorIterator($iterator, $this->order);
			$iterator->setMaxDepth($this->maxDepth);
		}

		$iterator = new \CallbackFilterIterator($iterator, function ($foo, $bar, \Iterator $file): bool {
			while ($file instanceof \OuterIterator) {
				$file = $file->getInnerIterator();
			}

			foreach ($this->groups as $filters) {
				foreach ($filters as $filter) {
					if (!$filter($file)) {
						continue 2;
					}
				}

				return true;
			}

			return false;
		});

		return $iterator;
	}


	/********************* filtering ****************d*g**/


	/**
	 * Restricts the search using mask.
	 * Excludes directories from recursive traversing.
	 * @param  string  ...$masks
	 * @return static
	 */
	public function exclude(...$masks): self
	{
		$masks = is_array($tmp = reset($masks)) ? $tmp : $masks;
		$pattern = self::buildPattern($masks);
		if ($pattern) {
			$this->filter(fn(RecursiveDirectoryIterator $file): bool => !preg_match($pattern, '/' . strtr($file->getSubPathName(), '\\', '/')));
		}

		return $this;
	}


	/**
	 * Restricts the search using callback.
	 * @param  callable(RecursiveDirectoryIterator): bool  $callback
	 * @return static
	 */
	public function filter(callable $callback): self
	{
		$this->cursor[] = $callback;
		return $this;
	}


	/**
	 * Limits recursion level.
	 * @return static
	 */
	public function limitDepth(int $depth): self
	{
		$this->maxDepth = $depth;
		return $this;
	}


	/**
	 * Restricts the search by size.
	 * @param  string  $operator  "[operator] [size] [unit]" example: >=10kB
	 * @return static
	 */
	public function size(string $operator, ?int $size = null): self
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

		return $this->filter(fn(RecursiveDirectoryIterator $file): bool => self::compare($file->getSize(), $operator, $size));
	}


	/**
	 * Restricts the search by modified time.
	 * @param  string  $operator  "[operator] [date]" example: >1978-01-23
	 * @param  string|int|\DateTimeInterface  $date
	 * @return static
	 */
	public function date(string $operator, $date = null): self
	{
		if (func_num_args() === 1) { // in $operator is predicate
			if (!preg_match('#^(?:([=<>!]=?|<>)\s*)?(.+)$#Di', $operator, $matches)) {
				throw new Nette\InvalidArgumentException('Invalid date predicate format.');
			}

			[, $operator, $date] = $matches;
			$operator = $operator ?: '=';
		}

		$date = DateTime::from($date)->format('U');
		return $this->filter(fn(RecursiveDirectoryIterator $file): bool => self::compare($file->getMTime(), $operator, $date));
	}


	/**
	 * Compares two values.
	 */
	public static function compare($l, string $operator, $r): bool
	{
		switch ($operator) {
			case '>':
				return $l > $r;
			case '>=':
				return $l >= $r;
			case '<':
				return $l < $r;
			case '<=':
				return $l <= $r;
			case '=':
			case '==':
				return $l == $r;
			case '!':
			case '!=':
			case '<>':
				return $l != $r;
			default:
				throw new Nette\InvalidArgumentException("Unknown operator $operator.");
		}
	}
}
