<?php
require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
use PHPUnit\Framework\TestCase;

class pdogsbincTUTest extends TestCase
{

    public function testConstructOK()
    {
        $this->assertNull(PdoGsb::$monPdo);
        $monPdo = new PdoGsb();
        $this->assertNotNull(PdoGsb::$monPdo);
    }

    public function testDestructOK()
    {
        unset($monPdo);
        $this->assertNull(PdoGsb::$monPdo);
    }

    public function testgetPdoGsbOK()
    {
        $this->assertNull(PdoGsb::$monPdoGsb);
        PdoGsb::getPdoGsb();
        $this->assertNotNull(PdoGsb::$monPdoGsb);
    }

    public function testgetInfosVisiteurOK()
    {
        $expect_id = 'm11';
        $expect_nom = "Mathorel";
        $expect_prenom = "Louis";
        $expect_metier = "comptable";

        $monPdo = new PdoGsb();
        $resultat = $monPdo->getInfosVisiteur('mathorel', 'mathorel');
        $this->assertEquals($expect_id, $resultat['id']);
        $this->assertEquals($expect_nom, $resultat['nom']);
        $this->assertEquals($expect_prenom, $resultat['prenom']);
        $this->assertEquals($expect_metier, $resultat['metier']);
        unset($monPdo);
    }

    public function testgetInfosVisiteurKO()
    {
        $monPdo = new PdoGsb();
        $resultat = $monPdo->getInfosVisiteur('', '');
        $this->assertEmpty($resultat);
        unset($monPdo);
    }

    public function testgetNomVisiteurOK()
    {
        $id = 'm11';
        $expect_nom = "Mathorel";
        $expect_prenom = "Louis";
        $monPdo = new PdoGsb();
        $resultat = $monPdo->getNomVisiteur($id);
        $this->assertEquals($expect_nom, $resultat['nom']);
        $this->assertEquals($expect_prenom, $resultat['prenom']);
        unset($monPdo);
    }

    public function testgetNomVisiteurKO()
    {
        $id = '1234';
        $expect_nom = "";
        $expect_prenom = "";
        $monPdo = new PdoGsb();
        $resultat = $monPdo->getNomVisiteur($id);
        $this->assertEquals($expect_nom, $resultat['nom']);
        $this->assertEquals($expect_prenom, $resultat['prenom']);
        unset($monPdo);
    }

    public function testgetLesFraisHorsForfaitOK()
    {
        $idVisiteur = 'm11';
        $mois = '202001';
        $monPdo = new PdoGsb();
        $this->AssertNotEmpty(
            $monPdo->getLesFraisHorsForfait($idVisiteur, $mois));
        unset($monPdo);
    }

    public function testgetLesFraisHorsForfaitIDKO()
    {
        $idVisiteur = '1234';
        $mois = '202001';
        $monPdo = new PdoGsb();
        $this->AssertEmpty($monPdo->getLesFraisHorsForfait($idVisiteur, $mois));
        unset($monPdo);
    }

    public function testgetLesFraisHorsForfaitMOISKO()
    {
        $idVisiteur = '1234';
        $mois = '999999';
        $monPdo = new PdoGsb();
        $this->AssertEmpty($monPdo->getLesFraisHorsForfait($idVisiteur, $mois));
        unset($monPdo);
    }

    public function testgetNbjustificatifsOK()
    {
        $idVisiteur = 'm11';
        $mois = '202001';
        $monPdo = new PdoGsb();
        $this->AssertThat($monPdo->getNbjustificatifs($idVisiteur, $mois),
            $this->istype('string'));
        unset($monPdo);
    }

    public function testgetNbjustificatifsIDKO()
    {
        $idVisiteur = '1234';
        $mois = '202001';
        $monPdo = new PdoGsb();
        $this->AssertEmpty($monPdo->getNbjustificatifs($idVisiteur, $mois));
        unset($monPdo);
    }

    public function testgetnbJustificatifsMOISKO()
    {
        $idVisiteur = '1234';
        $mois = '999999';
        $monPdo = new PdoGsb();
        $this->AssertEmpty($monPdo->getNbjustificatifs($idVisiteur, $mois));
        unset($monPdo);
    }

    public function testgetLesFraisForfaitOK()
    {
        $idVisiteur = 'm11';
        $mois = '202001';
        $monPdo = new PdoGsb();
        $this->AssertNotEmpty($monPdo->getLesFraisForfait($idVisiteur, $mois));
        unset($monPdo);
    }

    public function testgetLesFraisForfaitIDKO()
    {
        $idVisiteur = '1234';
        $mois = '202001';
        $monPdo = new PdoGsb();
        $this->AssertEmpty($monPdo->getLesFraisForfait($idVisiteur, $mois));
        unset($monPdo);
    }

    public function testgetLesFraisForfaitMOISKO()
    {
        $idVisiteur = '1234';
        $mois = '999999';
        $monPdo = new PdoGsb();
        $this->AssertEmpty($monPdo->getLesFraisForfait($idVisiteur, $mois));
        unset($monPdo);
    }

    public function testgetLesIdFrais()
    {
        $monPdo = new PdoGsb();
        $this->AssertNotEmpty($monPdo->getLesidFrais());
        unset($monPdo);
    }




}

