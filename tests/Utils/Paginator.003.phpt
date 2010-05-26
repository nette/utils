<?php

/**
 * Test: Nette\Paginator Base:0 Page:-1 Count:-1 test.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Paginator;



require __DIR__ . '/../NetteTest/initialize.php';



$paginator = new Paginator;
$paginator->itemCount = -1;
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



__halt_compiler() ?>

------EXPECT------
int(0)

int(0)

int(0)

int(0)

int(0)

int(0)

int(0)
