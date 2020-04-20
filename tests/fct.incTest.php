<?php
session_start();
require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';

class fctincTest extends \PHPUnit\Framework\TestCase

{

    // Vérification des connections
    public function testEstConnecteNON()
    {
        $this->assertFalse(estConnecte());
    }

    public function testEstConnecteOUI()
    {
        // La donnée proposée est non présente en base
        connecter("001", "Testeur", "PHPUNIT", "3");
        $this->assertTrue(estConnecte());
    }

    public function testdeconnecter() {
        deconnecter();
        $this->assertEquals(PHP_SESSION_NONE,session_status());
    }

    public function testDateFrancaisVersAnglais()
    {
        $this->assertEquals('2019-12-01', dateFrancaisVersAnglais('01/12/2019'));
    }

    public function testDateAnglaisVersFrancais()
    {
        $this->assertEquals('01/12/2019', dateAnglaisVersFrancais('2019-12-01'));
    }

    public function testgetMoisReussi()
    {
        $mois = array(
            '01/01/2020',
            '02/02/2020',
            '03/03/2020',
            '04/04/2020',
            '05/05/2020',
            '06/06/2020',
            '07/07/2020',
            '08/08/2020',
            '09/09/2020',
            '10/10/2020',
            '11/11/2020',
            '12/12/2020',
            '01/1/2020'
        );
        $attendu = array(
            '202001',
            '202002',
            '202003',
            '202004',
            '202005',
            '202006',
            '202007',
            '202008',
            '202009',
            '202010',
            '202011',
            '202012',
            '202001'
        );
        for ($leMois = 1; $leMois < 14; $leMois ++) {
            $calculMois = getMois($mois[$leMois - 1]);
            $this->assertEquals($attendu[$leMois - 1], $calculMois);
        }
    }

    public function testgetMoisErreur()
    {
        // Ce test devrait provoquer une erreur plutôt que vérifier que le retour
        // n'est pas bien mis en forme. --> modification fonction ?
        $calculMois = getMois('30 janvier 2020');
        $this->assertStringNotMatchesFormat('%d%d%d%d%d%d', $calculMois);
        $calculMois = getMois('30/01/20');
        $this->assertStringNotMatchesFormat('%d%d%d%d%d%d', $calculMois);
    }

    public function testinverseMoisReussi()
    {
        $mois = array(
            '202001',
            '202002',
            '202003',
            '202004',
            '202005',
            '202006',
            '202007',
            '202008',
            '202009',
            '202010',
            '202011',
            '202012'
        );
        $attendu = array(
            '01/2020',
            '02/2020',
            '03/2020',
            '04/2020',
            '05/2020',
            '06/2020',
            '07/2020',
            '08/2020',
            '09/2020',
            '10/2020',
            '11/2020',
            '12/2020'
        );
        for ($leMois = 1; $leMois < 13; $leMois ++) {
            $calculMois = inverseMois($mois[$leMois - 1]);
            $this->assertEquals($attendu[$leMois - 1], $calculMois);
        }
    }

    public function testinverseMoisErreur()
    {
        // Ce test devrait provoquer une erreur plutôt que vérifier que le retour
        // n'est pas bien mis en forme. --> modification fonction ?
        $calculMois = inverseMois('01/2020');
        $this->assertStringNotMatchesFormat('%d%d%e%d%d%d%d', $calculMois);
        $calculMois = inverseMois('janvier 2020');
        $this->assertStringNotMatchesFormat('%d%d%e%d%d%d%d', $calculMois);
    }

    public function testmoisEnLettreReussi()
    {
        $lesMois = [
            "janvier",
            "février",
            "mars",
            "avril",
            "mai",
            "juin",
            "juillet",
            "août",
            "septembre",
            "octobre",
            "novembre",
            "décembre"
        ];
        $moisATester = array(
            '202001',
            '202002',
            '202003',
            '202004',
            '202005',
            '202006',
            '202007',
            '202008',
            '202009',
            '202010',
            '202011',
            '202012'
        );
        for ($leMois = 1; $leMois < 13; $leMois ++) {
            $valeur = moisEnLettre($moisATester[$leMois - 1]);
            $this->assertEquals($lesMois[$leMois - 1] . ' 2020', $valeur);
        }
    }

    public function testestEntierPositifVRAI()
    {
        $test = 13;
        $this->assertTrue(estEntierPositif($test));
    }

    public function testestEntierPositifFAUX()
    {
        $test = - 13;
        $this->assertFalse(estEntierPositif($test));
        $test = 0.1;
        $this->assertFalse(estEntierPositif($test));
    }

    public function testestEntierPositifNULL()
    {
        $test = 0;
        $this->assertTrue(estEntierPositif($test));
    }

    public function testestTableauEntiersVRAI()
    {
        $tableau = array(
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            0,
            1234,
            567890
        );
        $this->assertTrue(estTableauEntiers($tableau));
    }

    public function testestTableauEntiersFAUX()
    {
        $tableau = array(
            1,
            - 2,
            3,
            4,
            5.5,
            6,
            7,
            8,
            9,
            0,
            1234,
            567890
        );
        $this->assertFalse(estTableauEntiers($tableau));
    }

    public function testestDateDepasseeVRAI()
    {
        // déterminer une date de plus d'un an
        $dateActuelle = date('d/m/Y');
        @list ($jour, $mois, $annee) = explode('/', $dateActuelle);
        $annee = $annee - 2;
        $anPasse = $annee . $mois . $jour;
        $this->assertTrue(estDateDepassee($anPasse));
    }

    public function testestDateDepasseeFAUX()
    {
        $this->assertFalse(estDateDepassee(date('d/m/Y')));
    }

    public function testestDateValideVRAI()
    {
        $laDate = "01/01/2020";
        $this->assertTrue(estDateValide($laDate));
        $laDate = "01/01/20";
        $this->assertTrue(estDateValide($laDate));
        $laDate = "1/1/20";
        $this->assertTrue(estDateValide($laDate));
    }

    public function testestDateValideFAUX()
    {
        $laDate = "01 janvier 2020";
        $this->assertFalse(estDateValide($laDate));
        $laDate = "012020";
        $this->assertFalse(estDateValide($laDate));
        $laDate = "01/01/01/2020";
        $this->assertFalse(estDateValide($laDate));
        $laDate = "-0.1/-9.5";
        $this->assertFalse(estDateValide($laDate));
        $laDate = "45/50/80";
        $this->assertFalse(estDateValide($laDate));
    }

    public function testLesQteFraisValidesVRAI()
    {
        $lesFrais = array(
            1,
            2,
            3,
            4,
            5
        );
        $this->assertTrue(lesQteFraisValides($lesFrais));
    }

    public function testLesQteFraisValidesFAUX()
    {
        $lesFrais = array(
            1,
            - 1,
            0.5,
            4,
            5
        );
        $this->assertFalse(lesQteFraisValides($lesFrais));
    }

    public function testvalideInfosFraisToutOK()
    {
        $dateFrais = date('d/m/Y');
        $libelle = "Un Libellé";
        $montant = 1250.0;
        valideInfosFrais($dateFrais, $libelle, $montant);
        $this->assertCount(0, $_REQUEST);
    }

    public function testvalideInfosFraisDateKO()
    {
        $dateFrais = '01 janvier 2020';
        $libelle = "Un Libellé";
        $montant = 1250.0;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        valideInfosFrais($dateFrais, $libelle, $montant);
        $this->assertCount(1, $_REQUEST['erreurs']);
        unset($_REQUEST);
        unset($_SERVER['REMOTE_ADDR']);
    }

    public function testvalideInfosFraisDatePerimee()
    {
        // déterminer une date de plus d'un an
        @list ($jour, $mois, $annee) = explode('/', date('d/m/Y'));
        $annee = $annee - 2;
        $dateFrais = $jour . "/" . $mois . "/" . $annee;
        // passer le test
        $libelle = "un Libellé";
        $montant = 1250.0;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        valideInfosFrais($dateFrais, $libelle, $montant);
        $this->assertCount(1, $_REQUEST['erreurs']);
        unset($_REQUEST);
        unset($_SERVER['REMOTE_ADDR']);
    }

    public function testvalideInfosFraisLibelleKO()
    {
        $dateFrais = date('d/m/Y');
        $libelle = '';
        $montant = 1250.0;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        valideInfosFrais($dateFrais, $libelle, $montant);
        $this->assertCount(1, $_REQUEST['erreurs']);
        unset($_REQUEST);
        unset($_SERVER['REMOTE_ADDR']);
    }

    public function testvalideInfosFraisMontantKO()
    {
        $dateFrais = date('d/m/Y');
        $libelle = "Un Libellé";
        $montant = "Montant";
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        valideInfosFrais($dateFrais, $libelle, $montant);
        $this->assertCount(1, $_REQUEST['erreurs']);
        unset($_REQUEST);
        unset($_SERVER['REMOTE_ADDR']);
    }

    public function testvalideInfosFraisToutKO()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        valideInfosFrais('', '', '');
        $this->assertCount(3, $_REQUEST['erreurs']);
        unset($_REQUEST);
        unset($_SERVER['REMOTE_ADDR']);
    }

    public function testajouterErreur()
    {
        $msg = "test";
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        ajouterErreur($msg);
        $this->assertEquals("test", $_REQUEST['erreurs'][0]);
        unset($_REQUEST);
        unset($_SERVER['REMOTE_ADDR']);
    }

    public function testnbErreursSuperieurZero()
    {
        $nbteste = 5;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        for ($cpt = 0; $cpt < $nbteste; $cpt ++) {
            ajouterErreur('Erreur n°' . $cpt);
        }
        $this->assertEquals($nbteste, nbErreurs());
        unset($_REQUEST);
        unset($_SERVER['REMOTE_ADDR']);
    }

    public function testnbErreursEgalZero()
    {
        $this->assertEquals(0, nbErreurs());
    }

    public function testAjouterInfo()
    {
        $msg = "test";
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        ajouterInfo($msg);
        $this->assertEquals("test", $_REQUEST['infos'][0]);
        unset($_REQUEST);
        unset($_SERVER['REMOTE_ADDR']);
    }

    public function testajouterInfosSuperieurZero()
    {
        $nbteste = 5;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        for ($cpt = 0; $cpt < $nbteste; $cpt ++) {
            ajouterInfo('Erreur n°' . $cpt);
        }
        $this->assertEquals($nbteste, nbInfos());
        unset($_REQUEST);
        unset($_SERVER['REMOTE_ADDR']);
    }

    public function testnbInfosEgalZero()
    {
        $this->assertEquals(0, nbInfos());
    }

    public function testcompterFichesPerimeesOK()
    {
        $monPdo = NEW PdoGsb;
        $lesFichesRB = $monPdo->getlesFiches('RB');
        $this->assertNotEquals(0, compterFichesPerimees($lesFichesRB));
        unset($monPdo);
    }

    public function testcompterMontantTotal() {
        $tableauDeFiches = array();
        for ($cpt=0;$cpt<10;$cpt++) {
            $uneFiche['montant']=1000;
            $uneFiche['date']='2019-12-01';
            $tableauDeFiches[$cpt]=$uneFiche;
        }
        $this->assertEquals(10000,compterMontantTotal($tableauDeFiches));
    }

    public function testaddLogEvent() {
        $avant = filesize("GSB2020.LOG");
        addLogEvent("Test PHPUNIT");
        clearstatcache();
        $this->assertNotEquals($avant,filesize("GSB2020.LOG"));
    }

    public function testgenererPDF() {
        $pdo = NEW PdoGsb;
        $idvisiteur='m11';
        $mois = '202001';

        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idvisiteur,$mois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idvisiteur,$mois);
        $lesInfosFichesFrais = $pdo->getLesInfosfichefrais($idvisiteur,$mois);
        $visiteur = $pdo->getNomVisiteur($idvisiteur);
        $nom_visiteur=$visiteur['nom'];
        $leFichier = genererPDF($pdo,$lesFraisHorsForfait,$lesFraisForfait,$lesInfosFichesFrais);
        $this->assertFileExists('/PDF/'.$lesFraisHorsForfait[0]['mois'].'-'.$nom_visiteur.'.pdf');
        unset($monPdo);
        unlink('/PDF/'.$lesFraisHorsForfait[0]['mois'].'-'.$nom_visiteur.'.pdf');
    }
}

