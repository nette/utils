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



$el = Html::el('img')->src('image.gif')->alt('');
dump( (string) $el );
dump( $el->startTag() );
dump( $el->endTag() );

$el = Html::el('img')->src('image.gif')->alt('')->setText(NULL)->setText('any content');
dump( (string) $el );
dump( $el->startTag() );
dump( $el->endTag() );

Html::$xhtml = FALSE;
dump( (string) $el );

$el = Html::el('img')->setSrc('image.gif')->setAlt('alt1')->setAlt('alt2');
dump( (string) $el );
dump( $el->getSrc() );
dump( $el->getTitle() );
dump( $el->getAlt() );
$el->addAlt('alt3');
dump( (string) $el );

$el->style = 'float:left';
$el->class = 'three';
$el->lang = '';
$el->title = '0';
$el->checked = TRUE;
$el->selected = FALSE;
$el->name = 'testname';
$el->setName('span');
dump( (string) $el );

// setText vs. setHtml
dump( (string) Html::el('p')->setText('Hello &ndash; World'), 'setText' );
dump( (string) Html::el('p')->setHtml('Hello &ndash; World'), 'setHtml' );

// email obfuscate
dump( (string) Html::el('a')->href('mailto:dave@example.com'), 'mailto' );

// href with query
dump( (string) Html::el('a')->href('file.php', array('a' => 10)), 'href' );





__halt_compiler();

------EXPECT------
string(30) "<img src="image.gif" alt="" />"

string(30) "<img src="image.gif" alt="" />"

string(0) ""

string(30) "<img src="image.gif" alt="" />"

string(30) "<img src="image.gif" alt="" />"

string(0) ""

string(28) "<img src="image.gif" alt="">"

string(32) "<img src="image.gif" alt="alt2">"

string(9) "image.gif"

NULL

string(4) "alt2"

string(37) "<img src="image.gif" alt="alt2 alt3">"

string(120) "<span src="image.gif" alt="alt2 alt3" style="float:left" class="three" lang="" title="0" checked name="testname"></span>"

setText: string(30) "<p>Hello &amp;ndash; World</p>"

setHtml: string(26) "<p>Hello &ndash; World</p>"

mailto: string(42) "<a href="mailto:dave&#64;example.com"></a>"

href: string(28) "<a href="file.php?a=10"></a>"
