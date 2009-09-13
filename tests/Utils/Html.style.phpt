<?php

/**
 * Test: Html style & class attribute.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */

/*use Nette\Web\Html;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$el = Html::el('div');
$el->style[] = 'text-align:right';
$el->style[] = NULL;
$el->style[] = 'background-color: blue';
$el->class[] = 'one';
$el->class[] = NULL;
$el->class[] = 'two';

dump( (string) $el );

$el->style = NULL;
$el->style['text-align'] = 'left';
$el->style['background-color'] = 'green';
dump( (string) $el );

// append
$el = Html::el('div');
$el->style('color', 'white');
$el->style('background-color', 'blue');

$el->class = 'one';
$el->class('', TRUE);
$el->class('two', TRUE);

$el->id('my', TRUE);
dump( (string) $el );

// append II
$el = Html::el('div');
$el->style[] = 'text-align:right';
$el->style('', TRUE);
$el->style('background-color: blue', TRUE);
dump( (string) $el );

// append III
$el = Html::el('div');
$el->class('top', TRUE);
$el->class('active', TRUE);
dump( (string) $el );

$el->class('top', NULL);
$el->class('active', FALSE);
dump( (string) $el );



__halt_compiler();

------EXPECT------
string(75) "<div style="text-align:right;background-color: blue" class="one two"></div>"

string(74) "<div style="text-align:left;background-color:green" class="one two"></div>"

string(77) "<div style="color:white;background-color:blue" class="one two" id="my"></div>"

string(59) "<div style="text-align:right;background-color: blue"></div>"

string(30) "<div class="top active"></div>"

string(11) "<div></div>"
