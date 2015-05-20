Nette Utility Classes
=====================

[![Downloads this Month](https://img.shields.io/packagist/dm/nette/utils.svg)](https://packagist.org/packages/nette/utils)
[![Build Status](https://travis-ci.org/nette/utils.svg?branch=v2.3)](https://travis-ci.org/nette/utils)

Nette\Object: Strict classes
----------------------------

PHP gives a huge freedom to developers, which makes it a perfect language for making mistakes. But you can stop this bad behavior and start writing applications without hardly discoverable mistakes. Do you wonder how? It's really simple -- you just need to have stricter rules.

Can you find an error in this example?

```php
class Circle
{
	public $radius;

	public function getArea()
	{
		return $this->radius * $this->radius * M_PI;
	}

}

$circle = new Circle;
$circle->raduis = 10;
echo $circle->getArea(); // 10² * π ≈ 314
```

On the first look it seems that code will print out 314; but it returns 0. How is this even possible? Accidentaly, `$circle->radius` was mistyped to `raduis`. Just a small typo, which will give you a hard time correcting it, because PHP does not say a thing when something is wrong. Not even a Warning or Notice error message. Because PHP does not think it is an error.

The mentioned mistake could be corrected immediately, if class `Circle` would be descendant of [api:Nette\Object]:

```php
class Circle extends Nette\Object
{
	...
```

Whereas the former code executed successfully (although it contained an error), the latter did not:

![](http://files.nette.org/git/doc-2.1/debugger-circle.png)

Class `Nette\Object` made `Circle` more strict and threw an exception when you tried to access an undeclared property. And `Tracy\Debugger` displayed error message about it. Line of code with fatal typo is now highlighted and error message has meaningful description: *Cannot write to an undeclared property Circle::$raduis*. Programmer can now fix the mistake he might have otherwise missed and which could be a real pain to find later.

One of many remarkable abilities of `Nette\Object` is throwing exceptions when accessing undeclared members.

```php
$circle = new Circle;
echo $circle->undeclared; // throws Nette\MemberAccessException
$circle->undeclared = 1; // throws Nette\MemberAccessException
$circle->unknownMethod(); // throws Nette\MemberAccessException
```

But it has much more to offer!


Properties, getters a setters
-----------------------------

In modern object oriented languages *property* describes members of class, which look like variables but are represented by methods. When reading or assigning values to those "variables", methods are called instead (so-called getters and setters). It is really useful feature, which allows us to control the access to these variables. Using this we can validate inputs or postpone the computation of values of these variables to the time when it is actually accessed.

Any class that is a descendant of `Nette\Object` acquires the ability to imitate properties. Only thing you need to do is to keep simple convention:

- Getter and setter have to be *public* methods.
- Getter's name is `getXyz()` or `isXyz()`, setter's is `setXyz()`
- Getter and setter are optional, so it is possible to have *read-only* or *write-only* properties
- Names of properties are case-sensitive (first letter being an exception)

We will make use of properties in the class Circle to make sure variable `$radius` contains only non-negative numbers:

```php
class Circle extends Nette\Object
{
	private $radius; // not public anymore!

	public function getRadius()
	{
		return $this->radius;
	}

	public function setRadius($radius)
	{
		// sanitizing value before saving it
		$this->radius = max(0.0, (float) $radius);
	}

	public function getArea()
	{
		return $this->radius * $this->radius * M_PI;
	}

	public function isVisible()
	{
		return $this->radius > 0;
	}

}

$circle = new Circle;
// the classic way using method calls
$circle->setRadius(10); // sets circle's radius
echo $circle->getArea(); // gets circle's area

// the alternative way using properties
$circle->radius = 10; // calls setRadius()
echo $circle->area; // calls getArea()
echo $circle->visible; // calls $circle->isVisible()
```

Properties are mostly a syntactic sugar to beautify the code and make programmer's life easier. You do not have to use them, if you don't want to.

Events
------

Now we are going to create functions, which will be called when border radius changes. Let's call it `change` event and those functions event handlers:

```php
class Circle extends Nette\Object
{
	/** @var array */
	public $onChange;

	public function setRadius($radius)
	{
		// call events in onChange
		$this->onChange($this, $this->radius, $radius);

		$this->radius = max(0.0, (float) $radius);
	}
}

$circle = new Circle;

// adding an event handler
$circle->onChange[] = function($circle, $oldValue, $newValue) {
	echo 'there was a change!';
};

$circle->setRadius(10);
```

There is another syntactic sugar in `setRadius`'s code. Instead of iteration on `$onChange` array and calling each method one by one with unreliable (does not report if callback has any errors) function [php:call_user_func], you just have to write simple `onChange(...)` and given parameters will be handed over to the handlers.

Extension methods
-----------------

Do you need to add a new method to an existing object or class at runtime? **Extension methods** is just what you need.

```php
// declaration of future method Circle::getCircumference()
Circle::extensionMethod('getCircumference', function (Circle $that) {
	return $that->radius * 2 * M_PI;
});

$circle = new Circle;
$circle->radius = 10;
echo $circle->getCircumference(); // ≈ 62.8
```

Extensions methods can also take parameters. They don't break encapsulation, because they only have access to the public members of the class. You can also connect them with interfaces, therefore every class implementing that interface will have that method available.
