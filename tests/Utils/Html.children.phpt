<?php

/**
 * Test: Html basic usage.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */

/*use Nette\Web\Html;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



// add
$el = Html::el('ul');
$el->create('li')->setText('one');
$el->add( Html::el('li')->setText('two') )->class('hello');
dump( (string) $el );

// with indentation
dump( $el->render(2), 'indentation' );


$el = Html::el(NULL);
$el->add( Html::el('p')->setText('one') );
$el->add( Html::el('p')->setText('two') );
dump( (string) $el );

section("Get child:");
dump(isset($el[1]), 'Child1');
dump( (string) $el[1]);
dump(isset($el[2]), 'Child2');


section("Iterator:");
$el = Html::el('select');
$el->create('optgroup')->label('Main')->create('option')->setText('sub one')->create('option')->setText('sub two');
$el->create('option')->setText('Item');
dump( (string) $el );

foreach ($el as $name => $child) {
	dump( $child instanceof Html ? $child->getName() : "'$child'" );
}

section("Deep iterator:");
foreach ($el->getIterator(TRUE) as $name => $child) {
	dump( $child instanceof Html ? $child->getName() : "'$child'" );
}



__halt_compiler();

------EXPECT------
string(47) "<ul class="hello"><li>one</li><li>two</li></ul>"

indentation: string(66) "
		<ul class="hello">
			<li>one</li>

			<li>two</li>
		</ul>
	"

string(20) "<p>one</p><p>two</p>"

==> Get child:

Child1: bool(TRUE)

string(10) "<p>two</p>"

Child2: bool(FALSE)

==> Iterator:

string(120) "<select><optgroup label="Main"><option>sub one<option>sub two</option></option></optgroup><option>Item</option></select>"

string(8) "optgroup"

string(6) "option"

==> Deep iterator:

string(8) "optgroup"

string(6) "option"

string(9) "'sub one'"

string(6) "option"

string(9) "'sub two'"

string(6) "option"

string(6) "'Item'"
