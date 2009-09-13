<?php

/**
 * Test: Html::__construct()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */

/*use Nette\Web\Html;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



dump( (string) Html::el('a lang=cs href="#" title="" selected')->setText('click') );

dump( (string) Html::el('a lang=hello world href="hello world" title="hello \'world"')->setText('click') );

dump( (string) Html::el('a lang=\'hello" world\' href="hello "world" title=0')->setText('click') );





__halt_compiler();

------EXPECT------
string(60) "<a lang="cs" href="#" title="" selected="selected">click</a>"

string(79) "<a lang="hello" world="world" href="hello world" title="hello 'world">click</a>"

string(75) "<a lang="hello&quot; world" href="hello " world="world" title="0">click</a>"
