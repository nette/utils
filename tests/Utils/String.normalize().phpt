<?php

/**
 * Test: Nette\String::normalize()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\String;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



dump( String::normalize("\r\nHello  \r  World \n\n") ); // "Hello\n  World"



__halt_compiler();

------EXPECT------
string(13) "Hello
  World"
