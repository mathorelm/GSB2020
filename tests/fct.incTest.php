<?php
//equire_once 'includes/fct.inc.php';
//require_once 'includes/class.pdogsb.inc.php';
use PHPUnit\Framework\TestCase;

class fctincTest extends TestCase

{

    public function testDateFrancaisVersAnglais()
    {
        $this . assertEquals('2019-12-01', dateFrancaisVersAnglais('01/12/2019'));
    }

    public function testDateAnglaisVersFrancais()
    {
        $this . assertEquals('01/12/2019', dateAnglaisVersFrancais('2019-12-01'));
    }
}

