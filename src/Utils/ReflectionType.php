<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;


/**
 * PHP type reflection.
 */
final class ReflectionType
{
	/** @var array */
	private $types;

	/** @var string  '', |, & */
	private $kind;


	/** @internal */
	public function __construct(array $types, bool $intersection = false)
	{
		$this->types = $types;
		if ($intersection) {
			$this->kind = '&';
		} elseif (count($types) === 1 || (count($types) === 2 && $types[1] === 'null')) {
			$this->kind = '';
		} else {
			$this->kind = '|';
		}
	}


	public function __toString(): string
	{
		return $this->kind === ''
			? (isset($this->types[1]) ? '?' : '') . $this->types[0]
			: implode($this->kind, $this->types);
	}


	public function getTypes(): array
	{
		return $this->types;
	}


	public function allows(string $type): bool
	{
		if ($this->isIntersection()) {
			return false;
		} elseif ($this->types === ['mixed']) {
			return true;
		}

		$builtin = Reflection::isBuiltinType($type);
		foreach ($this->types as $t) {
			if ($builtin ? strcasecmp($t, $type) === 0 : is_a($type, $t, true)) {
				return true;
			}
		}
		return false;
	}


	public function isUnion(): bool
	{
		return $this->kind === '|';
	}


	public function isIntersection(): bool
	{
		return $this->kind === '&';
	}


	public function isSingle(): bool
	{
		return $this->kind === '';
	}


	public function getSingleType(): ?string
	{
		return $this->kind === ''
			? $this->types[0]
			: null;
	}
}
