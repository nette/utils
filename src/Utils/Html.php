<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2008 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette::Web
 * @version    $Id$
 */

/*namespace Nette::Web;*/



require_once dirname(__FILE__) . '/../Object.php';



/**
 * HTML helper.
 *
 * <code>
 * $anchor = Html::el('a')->href($link)->setText('Nette');
 * $el->class = 'myclass';
 * echo $el;
 *
 * echo $el->startTag(), $el->endTag();
 * </cod>
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette::Web
 * @property   mixed element's attributes
 */
class Html extends /*Nette::*/Object implements /*::*/ArrayAccess, /*::*/Countable, /*::*/IteratorAggregate
{
	/** @var string  element's name */
	private $name;

	/** @var bool  is element empty? */
	private $isEmpty;

	/** @var array  element's attributes */
	public $attrs = array();

	/** @var array  of Html | string nodes */
	protected $children = array();

	/** @var bool  use XHTML syntax? */
	public static $xhtml = TRUE;

	/** @var array  empty elements */
	public static $emptyElements = array('img'=>1,'hr'=>1,'br'=>1,'input'=>1,'meta'=>1,'area'=>1,
		'base'=>1,'col'=>1,'link'=>1,'param'=>1,'basefont'=>1,'frame'=>1,'isindex'=>1,'wbr'=>1,'embed'=>1);



	/**
	 * Static factory.
	 * @param  string element name (or NULL)
	 * @param  array|string element's attributes (or textual content)
	 * @return Html
	 */
	public static function el($name = NULL, $attrs = NULL)
	{
		$el = new /**/self/**/ /*static*/;
		$parts = explode(' ', $name);
		$el->setName(array_shift($parts));
		if (is_array($attrs)) {
			$el->attrs = $attrs;
		} elseif ($attrs !== NULL) {
			$el->setText($attrs);
		}
		foreach ($parts as $pair) {
			$pair = explode('=', $pair, 2);
			$el->attrs[$pair[0]] = isset($pair[1]) ? trim($pair[1], '"') : TRUE;
		}
		return $el;
	}



	/**
	 * Changes element's name.
	 * @param  string
	 * @param  bool  Is element empty?
	 * @return Html  provides a fluent interface
	 * @throws ::InvalidArgumentException
	 */
	final public function setName($name, $empty = NULL)
	{
		if ($name !== NULL && !is_string($name)) {
			throw new /*::*/InvalidArgumentException("Name must be string or NULL.");
		}

		$this->name = $name;
		$this->isEmpty = $empty === NULL ? isset(self::$emptyElements[$name]) : (bool) $empty;
		return $this;
	}



	/**
	 * Returns element's name.
	 * @return string
	 */
	final public function getName()
	{
		return $this->name;
	}



	/**
	 * Is element empty?
	 * @return bool
	 */
	final public function isEmpty()
	{
		return $this->isEmpty;
	}



	/**
	 * Overloaded setter for element's attribute.
	 * @param  string    HTML attribute name
	 * @param  mixed     HTML attribute value
	 * @return void
	 */
	final public function __set($name, $value)
	{
		$this->attrs[$name] = $value;
	}



	/**
	 * Overloaded getter for element's attribute.
	 * @param  string    HTML attribute name
	 * @return mixed     HTML attribute value
	 */
	final public function &__get($name)
	{
		return $this->attrs[$name];
	}



	/**
	 * Overloaded unsetter for element's attribute.
	 * @param  string    HTML attribute name
	 * @return void
	 */
	final public function __unset($name)
	{
		unset($this->attrs[$name]);
	}



	/**
	 * Overloaded setter for element's attribute.
	 * @param  string  HTML attribute name
	 * @param  array   (string) HTML attribute value & (bool) append?
	 * @return Html  provides a fluent interface
	 */
	final public function __call($m, $args)
	{
		if (empty($args[1]) || !isset($this->attrs[$m])) { // set
			$this->attrs[$m] = $args[0];

		} elseif ($args[0] == NULL) {
			// append empty value -> ignore

		} elseif (is_array($this->attrs[$m])) { // append to array
			$this->attrs[$m][] = $args[0];

		} else { // append to string
			$this->attrs[$m] .= ' ' . $args[0];
		}
		return $this;
	}



	/**
	 * Special setter for element's attribute.
	 * @param  string path
	 * @param  array query
	 * @return Html  provides a fluent interface
	 */
	final public function href($path, $query = NULL)
	{
		if ($query) {
			$query = http_build_query($query, NULL, '&');
			if ($query !== '') $path .= '?' . $query;
		}
		$this->attrs['href'] = $path;
		return $this;
	}



	/**
	 * Sets element's HTML content.
	 * @param  string
	 * @throws ::InvalidArgumentException
	 * @return Html  provides a fluent interface
	 */
	final public function setHtml($html)
	{
		return $this->setText($html, TRUE);
	}



	/**
	 * Sets element's textual content.
	 * @param  string
	 * @param  bool is the string HTML encoded yet?
	 * @throws ::InvalidArgumentException
	 * @return Html  provides a fluent interface
	 */
	final public function setText($text, $isHtml = FALSE)
	{
		if ($text === NULL) {
			$text = '';

		} elseif (is_array($text)) {
			throw new /*::*/InvalidArgumentException("Textual content must be a scalar.");

		} else {
			$text = (string) $text;
		}

		if (!$isHtml) {
			$text = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $text);
		}

		$this->removeChildren();
		$this->children[] = $text;
		return $this;
	}



	/**
	 * Gets element's textual content.
	 * @return string
	 */
	final public function getText()
	{
		$s = '';
		foreach ($this->children as $child) {
			if (is_object($child)) return FALSE;
			$s .= $child;
		}
		return $s;
	}



	/**
	 * Adds new element's child.
	 * @param  Html|string child node
	 * @return Html  provides a fluent interface
	 */
	final public function add($child)
	{
		return $this->insert(NULL, $child);
	}



	/**
	 * Creates and adds a new Html child.
	 * @param  string  elements's name
	 * @param  array|string element's attributes (or textual content)
	 * @return Html  created element
	 */
	final public function create($name, $attrs = NULL)
	{
		$this->insert(NULL, $child = /**/self/**/ /*static*/::el($name, $attrs));
		return $child;
	}



	/**
	 * Inserts child node.
	 * @param  int
	 * @param  Html node
	 * @param  bool
	 * @return Html  provides a fluent interface
	 * @throws Exception
	 */
	public function insert($index, $child, $replace = FALSE)
	{
		if ($child instanceof Html || is_scalar($child)) {
			if ($index === NULL)  { // append
				$this->children[] = $child;

			} else { // insert or replace
				array_splice($this->children, (int) $index, $replace ? 1 : 0, array($child));
			}

		} else {
			throw new /*::*/InvalidArgumentException('Child node must be scalar or Html object.');
		}

		return $this;
	}



	/**
	 * Inserts (replaces) child node (::ArrayAccess implementation).
	 * @param  int
	 * @param  Html node
	 * @return void
	 */
	final public function offsetSet($index, $child)
	{
		$this->insert($index, $child, TRUE);
	}



	/**
	 * Returns child node (::ArrayAccess implementation).
	 * @param  int index
	 * @return mixed
	 */
	final public function offsetGet($index)
	{
		return $this->children[$index];
	}



	/**
	 * Exists child node? (::ArrayAccess implementation).
	 * @param  int index
	 * @return bool
	 */
	final public function offsetExists($index)
	{
		return isset($this->children[$index]);
	}



	/**
	 * Removes child node (::ArrayAccess implementation).
	 * @param  int index
	 * @return void
	 */
	public function offsetUnset($index)
	{
		if (isset($this->children[$index])) {
			array_splice($this->children, (int) $index, 1);
		}
	}



	/**
	 * Required by the ::Countable interface.
	 * @return int
	 */
	final public function count()
	{
		return count($this->children);
	}



	/**
	 * Removed all children.
	 * @return void
	 */
	public function removeChildren()
	{
		$this->children = array();
	}



	/**
	 * Iterates over a elements.
	 * @param  bool    recursive?
	 * @param  string  class types filter
	 * @return ::RecursiveIterator
	 */
	final public function getIterator($deep = FALSE)
	{
		if ($deep) {
			$deep = $deep > 0 ? /*::*/RecursiveIteratorIterator::SELF_FIRST : /*::*/RecursiveIteratorIterator::CHILD_FIRST;
			return new /*::*/RecursiveIteratorIterator(new RecursiveHtmlIterator($this->children), $deep);

		} else {
			return new RecursiveHtmlIterator($this->children);
		}
	}



	/**
	 * Returns all of children.
	 * return array
	 */
	final public function getChildren()
	{
		return $this->children;
	}



	/**
	 * Renders element's start tag, content and end tag.
	 * @param  int indent
	 * @return string
	 */
	final public function render($indent = NULL)
	{
		$s = $this->startTag();

		// empty elements are finished now
		if ($this->isEmpty) {
			return $s;
		}

		// add content
		if ($indent !== NULL) {
			$indent++;
		}
		foreach ($this->children as $child) {
			if (is_object($child)) {
				$s .= $child->render($indent);
			} else {
				$s .= $child;
			}
		}

		// add end tag
		$s .= $this->endTag();
		if ($indent !== NULL) {
			return "\n" . str_repeat("\t", $indent - 1) . $s . "\n" . str_repeat("\t", max(0, $indent - 2));
		}
		return $s;
	}



	/**
	 * Returns element's start tag.
	 * @return string
	 */
	final public function startTag()
	{
		if (!$this->name) {
			return '';
		}

		$s = '<' . $this->name;

		if (is_array($this->attrs)) {
			foreach ($this->attrs as $key => $value)
			{
				// skip NULLs and false boolean attributes
				if ($value === NULL || $value === FALSE) continue;

				// true boolean attribute
				if ($value === TRUE) {
					// in XHTML must use unminimized form
					if (self::$xhtml) $s .= ' ' . $key . '="' . $key . '"';
					// in HTML should use minimized form
					else $s .= ' ' . $key;
					continue;

				} elseif (is_array($value)) {

					// prepare into temporary array
					$tmp = NULL;
					foreach ($value as $k => $v) {
						// skip NULLs & empty string; composite 'style' vs. 'others'
						if ($v == NULL) continue;

						if (is_string($k)) $tmp[] = $k . ':' . $v;
						else $tmp[] = $v;
					}

					if (!$tmp) continue;
					$value = implode($key === 'style' ? ';' : ' ', $tmp);

				} else {
					$value = (string) $value;
				}

				// add new attribute
				$s .= ' ' . $key . '="'
					. str_replace(array('&', '"', '<', '>', '@'), array('&amp;', '&quot;', '&lt;', '&gt;', '&#64;'), $value)
					. '"';
			}
		}

		// finish start tag
		if (self::$xhtml && $this->isEmpty) {
			return $s . ' />';
		}
		return $s . '>';
	}



	final public function __toString()
	{
		return $this->render();
	}



	/**
	 * Returns element's end tag.
	 * @return string
	 */
	final public function endTag()
	{
		if ($this->name && !$this->isEmpty) {
			return '</' . $this->name . '>';
		}
		return '';
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






/**
 * Recursive HTML element iterator. See Html::getIterator().
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette::Web
 */
class RecursiveHtmlIterator extends /*::*/RecursiveArrayIterator
{

	/**
	 * The sub-iterator for the current element.
	 * @return ::RecursiveIterator
	 */
	public function getChildren()
	{
		return $this->current()->getIterator();
	}

}
