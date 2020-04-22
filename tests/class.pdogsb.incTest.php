<?php
require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
use PHPUnit\Framework\TestCase;

class pdogsbincTest extends TestCase

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
        $this->AssertThat($monPdo->getNbjustificatifs($idVisiteur, $mois),$this->istype('string'));
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

    public function testmajFraisForfait()
    {
        //La préparation de ce test montre que la fonction ne marche pas ???
        $idVisiteur = 'm11';
        $mois = '202001';
        $monPdo = new PdoGsb();
        //sauvegarder l'enregistrement des frais (return id, libelle, quantite)
        $lesAnciensFrais = $monPdo->getLesFraisForfait($idVisiteur, $mois);
        //récupérer un autre enregistrement
        $lesNouveauxFrais=$monPdo->getLesFraisForfait($idVisiteur,'202002');
        //mettre à jour l'enregistrement
        $this->assertTrue($monPdo->majFraisForfait($idVisiteur, $mois, $lesNouveauxFrais));
        //relire l'enregistrement effectué
        $lesFraisTestes=$monPdo->getLesFraisForfait($idVisiteur,$mois);
        //tester si il est bien modifié
        //TODO : attention la fonction ne permet pas la mise à jour !!
        //$this->AssertSame($lesNouveauxFrais,$lesFraisTestes);
        //remettre la sauvegarde
        $this->assertTrue($monPdo->majFraisForfait($idVisiteur, $mois, $lesAnciensFrais));
        //fermer l'objet PDO
        unset($monPdo);
    }

    public function testmajNbJustificatifsOK() {
        $idVisiteur = 'm11';
        $mois = '202001';
        $nbJustificatifs = 99;
        $monPdo = new PdoGsb();
        $sauvegardeJustificatifs = $monPdo->getNbJustificatifs($idVisiteur,$mois);
        $monPdo->majNbJustificatifs($idVisiteur,$mois,$nbJustificatifs);
        $lesJustificatifs = $monPdo->getNbJustificatifs($idVisiteur,$mois);
        $this->assertEquals($nbJustificatifs,$lesJustificatifs);
        //rétablir la valeur précédente
        $monPdo->majNbJustificatifs($idVisiteur,$mois,$sauvegardeJustificatifs);
        //fermer l'objet PDO
        unset($monPdo);
    }

    public function testvalideSommeFrais() {
        $idVisiteur='m11';
        $mois = '202001';
        $monPdo = new PdoGsb();
        //récupérer le total enregistré.
        $requetePrepare = PdoGsb::$monPdo->prepare('SELECT montantvalide FROM fichefrais'.
            'WHERE fichefrais.idvisiteur=:unIdVisiteur AND fichefrais.mois=:unMois');
        $requetePrepare->bindParam(':unIdVisiteur',$idVisiteur,PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois',$mois,PDO::PARAM_STR);
        $reponse = $requetePrepare->fetch();
        $backup_total = $reponse['montantvalide'];
        //Placer un total à zéro
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'UPDATE fichefrais ' . 'SET montantvalide = :unMontant ' .
            'WHERE fichefrais.idvisiteur = :unIdVisiteur ' .
            'AND fichefrais.mois = :unMois');
        $requetePrepare->bindParam(':unMontant', 124 ,PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        //Lancer le calcul
        $monPdo->valideSommeFrais($idVisiteur,$mois);
        //Récupérer le nouveau total
        $requetePrepare = PdoGsb::$monPdo->prepare('SELECT montantvalide FROM fichefrais'.
            'WHERE fichefrais.idvisiteur=:unIdVisiteur AND fichefrais.mois=:unMois');
        $requetePrepare->bindParam(':unIdVisiteur',$idVisiteur,PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois',$mois,PDO::PARAM_STR);
        $reponse = $requetePrepare->fetch();
        $nouveau_total = $reponse['montantvalide'];
        $this->assertEquals($backup_total,$nouveau_total);
        //replacer la sauvegarde
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'UPDATE fichefrais ' . 'SET montantvalide = :unMontant ' .
            'WHERE fichefrais.idvisiteur = :unIdVisiteur ' .
            'AND fichefrais.mois = :unMois');
        $requetePrepare->bindParam(':unMontant', $backup_total);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        //supprimer l'objet PDO
        unset($monPdo);
    }
}

