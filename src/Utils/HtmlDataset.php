<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Utils;

use Nette;


/**
 * HTML dataset helper.
 *
 * @author     Petr Morávek <petr@pada.cz>
 */
class HtmlDataset extends Nette\Object implements \ArrayAccess, \Countable, \IteratorAggregate, IHtmlString
{
	/** @var array */
	private $data = array();


	public function __construct($data = array())
	{
		foreach ($data as $name => $value) {
			$this->data[$this->dataKey($name)] = $value;
		}
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		$s = '';
		foreach ($this->data as $k => $v) {
			if ($v === NULL) {
				continue;
			}
			if (!is_string($v)) {
				$v = Json::encode($v);
			}
			$q = strpos($v, '"') === FALSE ? '"' : "'";
			$v = $q . str_replace(array('&', $q), array('&amp;', $q === '"' ? '&quot;' : '&#39;'), $v) . $q;
			$s .= ' data-' . strtolower(preg_replace('#(.)(?=[A-Z])#', '$1-', $k)) . '=' . $v;
		}
		return ltrim($s);
	}


	/**
	 * Overloaded setter for data attribute.
	 * @param  string    data attribute name
	 * @param  mixed     data attribute value
	 * @return void
	 */
	public function __set($name, $value)
	{
		if ($value === NULL) {
			unset($this->data[$this->dataKey($name)]);
		} else {
			$this->data[$this->dataKey($name)] = $value;
		}
	}


	/**
	 * Overloaded getter for data attribute.
	 * @param  string    data attribute name
	 * @return mixed     data attribute value
	 */
	public function &__get($name)
	{
		return $this->data[$this->dataKey($name)];
	}


	/**
	 * Overloaded tester for data attribute.
	 * @param  string    data attribute name
	 * @return bool
	 */
	public function __isset($name)
	{
		return isset($this->data[$this->dataKey($name)]);
	}


	/**
	 * Overloaded unsetter for data attribute.
	 * @param  string    data attribute name
	 * @return void
	 */
	public function __unset($name)
	{
		unset($this->data[$this->dataKey($name)]);
	}


	/**
	 * Inserts (replaces) content for data attribute (\ArrayAccess implementation).
	 * @param  string name
	 * @param  mixed data
	 * @return void
	 */
	public function offsetSet($name, $value)
	{
		$this->$name = $value;
	}


	/**
	 * Returns content of data attribute (\ArrayAccess implementation).
	 * @param  string name
	 * @return mixed
	 */
	public function offsetGet($name)
	{
		return $this->$name;
	}


	/**
	 * Does data attribute exist? (\ArrayAccess implementation).
	 * @param  string name
	 * @return bool
	 */
	public function offsetExists($name)
	{
		return isset($this->$name);
	}


	/**
	 * Removes data attribute (\ArrayAccess implementation).
	 * @param  int name
	 * @return void
	 */
	public function offsetUnset($name)
	{
		unset($this->$name);
	}


	/**
	 * Required by the \Countable interface.
	 * @return int
	 */
	public function count()
	{
		return count($this->data);
	}


	/**
	 * Returns an iterator over all items.
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->data);
	}


	/**
	 * Returns camelCase data key.
	 * @param  string
	 * @return string
	 */
	private function dataKey($s)
	{
		if (strpos($s, '-') === FALSE) {
			return strtolower(substr($s, 0, 1)) . substr($s, 1);
		}

		return preg_replace_callback(
			'#-([a-z])#',
			function ($m) {
				return strtoupper($m[1]);
			},
			strtolower($s)
		);
	}

}
