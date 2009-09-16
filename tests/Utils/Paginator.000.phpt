<?php

/**
 * Test: Nette\Paginator Base:0 Page:3 test.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Paginator;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$paginator = new Paginator;
$paginator->itemCount = 7;
$paginator->itemsPerPage = 6;
$paginator->base = 0;
$paginator->page = 3;

dump( $paginator->page );
dump( $paginator->pageCount );
dump( $paginator->firstPage );
dump( $paginator->lastPage );
dump( $paginator->offset );
dump( $paginator->countdownOffset );
dump( $paginator->length );



__halt_compiler();

------EXPECT------
int(1)

int(2)

int(0)

int(1)

int(6)

int(0)

int(1)
