<?php
require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
use PHPUnit_Framework_TestCase;

class fctincTest extends PHPUnit_Framework_TestCase

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

