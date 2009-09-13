<?php

/**
 * Test: Paginator Base:0 Page: -1 PerPage: 7 test.
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
$paginator->itemsPerPage = 7;
$paginator->base = 0;
$paginator->page = -1;

dump( $paginator->page );
dump( $paginator->pageCount );
dump( $paginator->firstPage );
dump( $paginator->lastPage );
dump( $paginator->offset );
dump( $paginator->countdownOffset );
dump( $paginator->length );



__halt_compiler();

------EXPECT------
int(0)

int(1)

int(0)

int(0)

int(0)

int(0)

int(7)
