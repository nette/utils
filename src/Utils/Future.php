<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * Future value binding.
 */
class Future
{
	/** @var callable */
	private $resolver;

	private array $destinations = [];

	/** @var ?callable */
	private $defaultValueFactory;


	public function __construct(callable $resolver)
	{
		$this->resolver = $resolver;
	}


	final public function setDefaultValueFactory(callable $cb): static
	{
		$this->defaultValueFactory = $cb;
		return $this;
	}


	final public function bind(int|string|null $key, &$destination): static
	{
		if ($key !== null) {
			$this->destinations[$key][] = &$destination;
		}
		return $this;
	}


	final public function bindVar(int|string|null &$v): static
	{
		return $this->bind($v, $v);
	}


	final public function bindArrayValues(array &$values): static
	{
		foreach ($values as &$destination) {
			$this->bind($destination, $destination);
		}
		return $this;
	}


	final public function bindArrayKeys(array &$keys): static
	{
		foreach ($keys as $key => &$destination) {
			$this->bind($key, $destination);
		}
		return $this;
	}


	final public function bindArraysKey(string $key, array &$arrays): static
	{
		foreach ($arrays as &$item) {
			assert(is_array($item));
			$this->bind($item[$key], $item[$key]);
		}
		return $this;
	}


	final public function bindObjectsProperty(string $property, array $objects): static
	{
		foreach ($objects as $item) {
			assert(is_object($item));
			$this->bind($item->{$property}, $item->{$property});
		}
		return $this;
	}


	/**
	 * @throws Nette\UnexpectedValueException if resolver returns value of unexpected type
	 * @throws FutureException if resolver does not return all required values
	 */
	final public function resolve(): void
	{
		if (count($this->destinations) < 1) {
			return;
		}

		$values = ($this->resolver)(array_keys($this->destinations));
		if ($values instanceof \Traversable) {
			$values = iterator_to_array($values);

		} elseif (!is_array($values)) {
			throw new Nette\UnexpectedValueException("Resolver returned '" . get_debug_type($values) . "' but array or Traversable expected.");
		}

		if ($this->defaultValueFactory === null && count($diff = array_diff_key($this->destinations, $values))) {
			throw (new FutureException('Resolver did not return required items.'))->setMissingKeys(array_keys($diff));
		}

		foreach ($this->destinations as $key => $destinations) {
			$value = array_key_exists($key, $values)
				? $values[$key]
				: ($this->defaultValueFactory)($key);

			foreach ($destinations as &$destination) {
				$destination = $value;
			}

			unset($this->destinations[$key]);
		}
	}
}
