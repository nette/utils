<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;


/**
 * HTML helper.
 *
 * <code>
 * $el = Html::el('a')->href($link)->setText('Nette');
 * $el->class = 'myclass';
 * echo $el;
 *
 * echo $el->startTag(), $el->endTag();
 * </code>
 */
class Html implements \ArrayAccess, \Countable, \IteratorAggregate, IHtmlString
{
	use Nette\SmartObject;

	/** @var array  element's attributes */
	public $attrs = [];

	/** @var bool  use XHTML syntax? */
	public static $xhtml = false;

	/** @var array  empty (void) elements */
	public static $emptyElements = [
		'img' => 1, 'hr' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'area' => 1, 'embed' => 1, 'keygen' => 1,
		'source' => 1, 'base' => 1, 'col' => 1, 'link' => 1, 'param' => 1, 'basefont' => 1, 'frame' => 1,
		'isindex' => 1, 'wbr' => 1, 'command' => 1, 'track' => 1,
	];

	/** @var array  of Html | string nodes */
	protected $children = [];

	/** @var string  element's name */
	private $name;

	/** @var bool  is element empty? */
	private $isEmpty;


	/**
	 * Static factory.
	 * @param  string element name (or null)
	 * @param  array|string element's attributes or plain text content
	 * @return static
	 */
	public static function el($name = null, $attrs = null)
	{
		$el = new static;
		$parts = explode(' ', (string) $name, 2);
		$el->setName($parts[0]);

		if (is_array($attrs)) {
			$el->attrs = $attrs;

		} elseif ($attrs !== null) {
			$el->setText($attrs);
		}

		if (isset($parts[1])) {
			foreach (Strings::matchAll($parts[1] . ' ', '#([a-z0-9:-]+)(?:=(["\'])?(.*?)(?(2)\\2|\s))?#i') as $m) {
				$el->attrs[$m[1]] = isset($m[3]) ? $m[3] : true;
			}
		}

		return $el;
	}


	/**
	 * Changes element's name.
	 * @param  string
	 * @param  bool  Is element empty?
	 * @return static
	 * @throws Nette\InvalidArgumentException
	 */
	public function setName($name, $isEmpty = null)
	{
		if ($name !== null && !is_string($name)) {
			throw new Nette\InvalidArgumentException(sprintf('Name must be string or null, %s given.', gettype($name)));
		}

		$this->name = $name;
		$this->isEmpty = $isEmpty === null ? isset(static::$emptyElements[$name]) : (bool) $isEmpty;
		return $this;
	}


	/**
	 * Returns element's name.
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * Is element empty?
	 * @return bool
	 */
	public function isEmpty()
	{
		return $this->isEmpty;
	}


	/**
	 * Sets multiple attributes.
	 * @param  array
	 * @return static
	 */
	public function addAttributes(array $attrs)
	{
		$this->attrs = array_merge($this->attrs, $attrs);
		return $this;
	}


	/**
	 * Appends value to element's attribute.
	 * @param  string
	 * @param  string|array value to append
	 * @param  string|bool  value option
	 * @return static
	 */
	public function appendAttribute($name, $value, $option = true)
	{
		if (is_array($value)) {
			$prev = isset($this->attrs[$name]) ? (array) $this->attrs[$name] : [];
			$this->attrs[$name] = $value + $prev;

		} elseif ((string) $value === '') {
			$tmp = &$this->attrs[$name]; // appending empty value? -> ignore, but ensure it exists

		} elseif (!isset($this->attrs[$name]) || is_array($this->attrs[$name])) { // needs array
			$this->attrs[$name][$value] = $option;

		} else {
			$this->attrs[$name] = [$this->attrs[$name] => true, $value => $option];
		}
		return $this;
	}


	/**
	 * Sets element's attribute.
	 * @param  string
	 * @param  mixed
	 * @return static
	 */
	public function setAttribute($name, $value)
	{
		$this->attrs[$name] = $value;
		return $this;
	}


	/**
	 * Returns element's attribute.
	 * @param  string
	 * @return mixed
	 */
	public function getAttribute($name)
	{
		return isset($this->attrs[$name]) ? $this->attrs[$name] : null;
	}


	/**
	 * Unsets element's attribute.
	 * @param  string
	 * @return static
	 */
	public function removeAttribute($name)
	{
		unset($this->attrs[$name]);
		return $this;
	}


	/**
	 * Unsets element's attributes.
	 * @return static
	 */
	public function removeAttributes(array $attributes)
	{
		foreach ($attributes as $name) {
			unset($this->attrs[$name]);
		}
		return $this;
	}


	/**
	 * Overloaded setter for element's attribute.
	 * @param  string    HTML attribute name
	 * @param  mixed     HTML attribute value
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->attrs[$name] = $value;
	}


	/**
	 * Overloaded getter for element's attribute.
	 * @param  string    HTML attribute name
	 * @return mixed     HTML attribute value
	 */
	public function &__get($name)
	{
		return $this->attrs[$name];
	}


	/**
	 * Overloaded tester for element's attribute.
	 * @param  string    HTML attribute name
	 * @return bool
	 */
	public function __isset($name)
	{
		return isset($this->attrs[$name]);
	}


	/**
	 * Overloaded unsetter for element's attribute.
	 * @param  string    HTML attribute name
	 * @return void
	 */
	public function __unset($name)
	{
		unset($this->attrs[$name]);
	}


	/**
	 * Overloaded setter for element's attribute.
	 * @param  string  HTML attribute name
	 * @param  array   (string) HTML attribute value or pair?
	 * @return mixed
	 */
	public function __call($m, $args)
	{
		$p = substr($m, 0, 3);
		if ($p === 'get' || $p === 'set' || $p === 'add') {
			$m = substr($m, 3);
			$m[0] = $m[0] | "\x20";
			if ($p === 'get') {
				return isset($this->attrs[$m]) ? $this->attrs[$m] : null;

			} elseif ($p === 'add') {
				$args[] = true;
			}
		}

		if (count($args) === 0) { // invalid

		} elseif (count($args) === 1) { // set
			$this->attrs[$m] = $args[0];

		} else { // add
			$this->appendAttribute($m, $args[0], $args[1]);
		}

		return $this;
	}


	/**
	 * Special setter for element's attribute.
	 * @param  string path
	 * @param  array query
	 * @return static
	 */
	public function href($path, $query = null)
	{
		if ($query) {
			$query = http_build_query($query, '', '&');
			if ($query !== '') {
				$path .= '?' . $query;
			}
		}
		$this->attrs['href'] = $path;
		return $this;
	}


	/**
	 * Setter for data-* attributes. Booleans are converted to 'true' resp. 'false'.
	 * @return static
	 */
	public function data($name, $value = null)
	{
		if (func_num_args() === 1) {
			$this->attrs['data'] = $name;
		} else {
			$this->attrs["data-$name"] = is_bool($value) ? json_encode($value) : $value;
		}
		return $this;
	}


	/**
	 * Sets element's HTML content.
	 * @param  IHtmlString|string
	 * @return static
	 * @throws Nette\InvalidArgumentException
	 */
	public function setHtml($html)
	{
		if (is_array($html)) {
			throw new Nette\InvalidArgumentException(sprintf('Textual content must be a scalar, %s given.', gettype($html)));
		}
		$this->removeChildren();
		$this->children[] = (string) $html;
		return $this;
	}


	/**
	 * Returns element's HTML content.
	 * @return string
	 */
	public function getHtml()
	{
		return implode('', $this->children);
	}


	/**
	 * Sets element's textual content.
	 * @param  IHtmlString|string
	 * @return static
	 */
	public function setText($text)
	{
		if (!$text instanceof IHtmlString) {
			$text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
		}
		return $this->setHtml($text);
	}


	/**
	 * Returns element's textual content.
	 * @return string
	 */
	public function getText()
	{
		return html_entity_decode(strip_tags($this->getHtml()), ENT_QUOTES, 'UTF-8');
	}


	/**
	 * Adds new element's child.
	 * @param  IHtmlString|string  Html node or raw HTML string
	 * @return static
	 */
	public function addHtml($child)
	{
		return $this->insert(null, $child);
	}


	/**
	 * Appends plain-text string to element content.
	 * @param  IHtmlString|string|int|float
	 * @return static
	 */
	public function addText($text)
	{
		if (!$text instanceof IHtmlString) {
			$text = htmlspecialchars((string) $text, ENT_NOQUOTES, 'UTF-8');
		}
		return $this->insert(null, $text);
	}


	/**
	 * Creates and adds a new Html child.
	 * @param  string  elements's name
	 * @param  array|string element's attributes or raw HTML string
	 * @return static  created element
	 */
	public function create($name, $attrs = null)
	{
		$this->insert(null, $child = static::el($name, $attrs));
		return $child;
	}


	/**
	 * Inserts child node.
	 * @param  int|null position or null for appending
	 * @param  IHtmlString|string Html node or raw HTML string
	 * @param  bool
	 * @return static
	 * @throws Nette\InvalidArgumentException
	 */
	public function insert($index, $child, $replace = false)
	{
		if ($child instanceof IHtmlString || is_scalar($child)) {
			$child = $child instanceof self ? $child : (string) $child;
			if ($index === null) { // append
				$this->children[] = $child;

			} else { // insert or replace
				array_splice($this->children, (int) $index, $replace ? 1 : 0, [$child]);
			}

		} else {
			throw new Nette\InvalidArgumentException(sprintf('Child node must be scalar or Html object, %s given.', is_object($child) ? get_class($child) : gettype($child)));
		}

		return $this;
	}


	/**
	 * Inserts (replaces) child node (\ArrayAccess implementation).
	 * @param  int|null position or null for appending
	 * @param  Html|string Html node or raw HTML string
	 * @return void
	 */
	public function offsetSet($index, $child)
	{
		$this->insert($index, $child, true);
	}


	/**
	 * Returns child node (\ArrayAccess implementation).
	 * @param  int
	 * @return static|string
	 */
	public function offsetGet($index)
	{
		return $this->children[$index];
	}


	/**
	 * Exists child node? (\ArrayAccess implementation).
	 * @param  int
	 * @return bool
	 */
	public function offsetExists($index)
	{
		return isset($this->children[$index]);
	}


	/**
	 * Removes child node (\ArrayAccess implementation).
	 * @param  int
	 * @return void
	 */
	public function offsetUnset($index)
	{
		if (isset($this->children[$index])) {
			array_splice($this->children, (int) $index, 1);
		}
	}


	/**
	 * Returns children count.
	 * @return int
	 */
	public function count()
	{
		return count($this->children);
	}


	/**
	 * Removes all children.
	 * @return void
	 */
	public function removeChildren()
	{
		$this->children = [];
	}


	/**
	 * Iterates over elements.
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->children);
	}


	/**
	 * Returns all children.
	 * @return array
	 */
	public function getChildren()
	{
		return $this->children;
	}


	/**
	 * Renders element's start tag, content and end tag.
	 * @param  int
	 * @return string
	 */
	public function render($indent = null)
	{
		$s = $this->startTag();

		if (!$this->isEmpty) {
			// add content
			if ($indent !== null) {
				$indent++;
			}
			foreach ($this->children as $child) {
				if ($child instanceof self) {
					$s .= $child->render($indent);
				} else {
					$s .= $child;
				}
			}

			// add end tag
			$s .= $this->endTag();
		}

		if ($indent !== null) {
			return "\n" . str_repeat("\t", $indent - 1) . $s . "\n" . str_repeat("\t", max(0, $indent - 2));
		}
		return $s;
	}


	public function __toString()
	{
		try {
			return $this->render();
		} catch (\Exception $e) {
		} catch (\Throwable $e) {
		}
		trigger_error('Exception in ' . __METHOD__ . "(): {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}", E_USER_ERROR);
	}


	/**
	 * Returns element's start tag.
	 * @return string
	 */
	public function startTag()
	{
		if ($this->name) {
			return '<' . $this->name . $this->attributes() . (static::$xhtml && $this->isEmpty ? ' />' : '>');

		} else {
			return '';
		}
	}


	/**
	 * Returns element's end tag.
	 * @return string
	 */
	public function endTag()
	{
		return $this->name && !$this->isEmpty ? '</' . $this->name . '>' : '';
	}


	/**
	 * Returns element's attributes.
	 * @return string
	 * @internal
	 */
	public function attributes()
	{
		if (!is_array($this->attrs)) {
			return '';
		}

		$s = '';
		$attrs = $this->attrs;
		foreach ($attrs as $key => $value) {
			if ($value === null || $value === false) {
				continue;

			} elseif ($value === true) {
				if (static::$xhtml) {
					$s .= ' ' . $key . '="' . $key . '"';
				} else {
					$s .= ' ' . $key;
				}
				continue;

			} elseif (is_array($value)) {
				if (strncmp($key, 'data-', 5) === 0) {
					$value = Json::encode($value);

				} else {
					$tmp = null;
					foreach ($value as $k => $v) {
						if ($v != null) { // intentionally ==, skip nulls & empty string
							// composite 'style' vs. 'others'
							$tmp[] = $v === true ? $k : (is_string($k) ? $k . ':' . $v : $v);
						}
					}
					if ($tmp === null) {
						continue;
					}

					$value = implode($key === 'style' || !strncmp($key, 'on', 2) ? ';' : ' ', $tmp);
				}

			} elseif (is_float($value)) {
				$value = rtrim(rtrim(number_format($value, 10, '.', ''), '0'), '.');

			} else {
				$value = (string) $value;
			}

			$q = strpos($value, '"') === false ? '"' : "'";
			$s .= ' ' . $key . '=' . $q
				. str_replace(
					['&', $q, '<'],
					['&amp;', $q === '"' ? '&quot;' : '&#39;', self::$xhtml ? '&lt;' : '<'],
					$value
				)
				. (strpos($value, '`') !== false && strpbrk($value, ' <>"\'') === false ? ' ' : '')
				. $q;
		}

		$s = str_replace('@', '&#64;', $s);
		return $s;
	}


	/**
	 * Clones all children too.
	 */
	public function __clone()
	{
		foreach ($this->children as $key => $value) {
			if (is_object($value)) {
				$this->children[$key] = clone $value;
			}
		}
	}
}
