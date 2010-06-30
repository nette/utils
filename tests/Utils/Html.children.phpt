<?php

/**
 * Test: Nette\Web\Html basic usage.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */

use Nette\Web\Html;



require __DIR__ . '/../initialize.php';



// add
$el = Html::el('ul');
$el->create('li')->setText('one');
$el->add( Html::el('li')->setText('two') )->class('hello');
T::dump( (string) $el );

// with indentation
T::dump( $el->render(2), 'indentation' );


$el = Html::el(NULL);
$el->add( Html::el('p')->setText('one') );
$el->add( Html::el('p')->setText('two') );
T::dump( (string) $el );

T::note("==> Get child:");
T::dump(isset($el[1]), 'Child1');
T::dump( (string) $el[1]);
T::dump(isset($el[2]), 'Child2');


T::note("==> Iterator:");
$el = Html::el('select');
$el->create('optgroup')->label('Main')->create('option')->setText('sub one')->create('option')->setText('sub two');
$el->create('option')->setText('Item');
T::dump( (string) $el );

foreach ($el as $name => $child) {
	T::dump( $child instanceof Html ? $child->getName() : "'$child'" );
}

T::note("==> Deep iterator:");
foreach ($el->getIterator(TRUE) as $name => $child) {
	T::dump( $child instanceof Html ? $child->getName() : "'$child'" );
}



__halt_compiler() ?>

------EXPECT------
"<ul class="hello"><li>one</li><li>two</li></ul>"

indentation: "
		<ul class="hello">
			<li>one</li>

			<li>two</li>
		</ul>
	"

"<p>one</p><p>two</p>"

==> Get child:

Child1: TRUE

"<p>two</p>"

Child2: FALSE

==> Iterator:

"<select><optgroup label="Main"><option>sub one<option>sub two</option></option></optgroup><option>Item</option></select>"

"optgroup"

"option"

==> Deep iterator:

"optgroup"

"option"

"'sub one'"

"option"

"'sub two'"

"option"

"'Item'"
