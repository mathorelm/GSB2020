<?php
require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
use PHPUnit\Framework\TestCase;

class pdogsbincTITest extends TestCase
{

    // ---------------------------------------------------------------------------
    // ATTENTION : Les modifications sur la base de données sont réelles concernant
    // Le statut des fiches !
    // Ces tests doivent être exécutés uniquement sur une BDD de DEVELOPPEMENT !
    // ---------------------------------------------------------------------------
    // Jeu de données pour le visiteur
    private $nom = "PHPUNIT";

    private $prenom = "M.";

    private $login = "phpunit";

    private $id = "phpu";

    private $mdp = "phpunit";

    private $metier = "1";

    private $adresse = "/vendor/phpunit";

    private $cp = "00000";

    private $ville = "OMEN01";

    private $dateembauche = "2020-01-01";

    private $vehicule = "D4";

    private $puissanceAdmin = 4;

    private $accesPdo = null;

    // Jeu de données de forfaits pour insertion
    // mois doit toujours etre égal à -1 mois et mois suivant à +1 mois
    private $mois = '202003';

    private $moisprecedent = '202002';

    private $moissuivant = '202005';

    private $FraisForfait = [
        "ETP" => 20,
        "KM" => 100,
        "NUI" => 10,
        "REP" => 20
    ];

    private $totalFFTheo = (100 * 0.52) + (20 * 110) + (10 * 80) + (20 * 25);

    private $HF1 = [
        'libelle' => 'PHPUNIT frais 1',
        'date' => '01/01/2020',
        'montant' => 100
    ];

    private $HF2 = [
        'libelle' => 'PHPUNIT frais 2',
        'date' => '02/01/2020',
        'montant' => 100
    ];

    private $HF3 = [
        'libelle' => 'PHPUNIT frais 3',
        'date' => '03/01/2020',
        'montant' => 100
    ];

    private $HF4 = [
        'libelle' => 'PHPUNIT frais 4',
        'date' => '04/01/2020',
        'montant' => 100
    ];

    private $HFReport = [
        'libelle' => 'REPORTE : PHPUNIT frais 2',
        'date' => '02/01/2020',
        'montant' => 100
    ];

    private $totalHFTheo = 400;

    public function setUp(): void
    {
        $this->accesPdo = new pdoGsb();
        // placer des mois à date actuelle - 11 mois et - 10 mois
        // $this->mois = str_replace('-','/',date('d-m-Y'),strtotime('-11 month'));
        // $this->moissuivant = str_replace('-','/',date('d-m-Y'),strtotime('+1 month')) ;
    }

    public function tearDown(): void
    {
        unset($this->accesPdo);
    }

    public function detruireDonnees()
    {
        // Supprimer les entrées dans la BDD : frais forfait
        $requetePrepare = PdoGSB::$monPdo->prepare(
            "DELETE FROM lignefraisforfait WHERE idvisiteur = '" . $this->id .
            "'");
        $requetePrepare->execute();
        // frais hors forfait
        $requetePrepare = PdoGSB::$monPdo->prepare(
            "DELETE FROM lignefraishorsforfait WHERE idvisiteur = '" . $this->id .
            "'");
        $requetePrepare->execute();
        // fiche frais
        $requetePrepare = PdoGSB::$monPdo->prepare(
            "DELETE FROM fichefrais WHERE
        idvisiteur = '" . $this->id . "'");
        $requetePrepare->execute();
    }

    public function creerVisiteurFictif()
    {
        $requete = "INSERT INTO personnels
            (id,metier,nom,prenom,login,mdp,adresse,cp,ville,dateembauche,vehicule)
            VALUES ('" . $this->id . "','" . $this->metier . "','" . $this->nom .
            "','" . $this->prenom . "','" . $this->login . "','" . $this->mdp .
            "','" . $this->adresse . "','" . $this->cp . "','" . $this->ville .
            "','" . $this->dateembauche . "','" . $this->vehicule . "')";
        $requetePrepare = PdoGsb::$monPdo->prepare($requete);
        $retour = $requetePrepare->execute();
        $this->accesPdo->crypterMotsDePasse();
    }

    public function detruireVisiteurFictif()
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            "DELETE FROM personnels WHERE id='" . $this->id . "'");
        $requetePrepare->execute();
    }



    public function testcreationVisiteur()
    {
        // Il ne doit pas y avoir de visiteur qui possède ce couple $login/$mdp
        $retour = $this->accesPdo->getInfosVisiteur($this->login, $this->mdp);
        $this->assertNull($retour);
        // Le visiteur ne doit pas apparaître dans la liste des visiteurs
        $retour = $this->accesPdo->getLesVisiteurs();
        $this->assertNotContains($this->nom, $retour);
        $this->assertNotContains($this->prenom, $retour);
        // L'ID du visiteur ne doit pas permettre de donner son nom
        $retour = $this->accesPdo->getNomVisiteur($this->id);
        $this->assertFalse($retour);
        // créer le visiteur fictif
        $this->creerVisiteurFictif();
        $retour = $this->accesPdo->getInfosVisiteur($this->login, $this->mdp);
        // il doit y avoir un visiteur maintenant.
        $this->assertEquals($this->nom, $retour['nom']);
        $this->assertEquals($this->prenom, $retour['prenom']);
        // Le visiteur doit apparaître dans la liste des visiteurs
        $retour = $this->accesPdo->getLesVisiteurs();
        $this->assertContains(
            [
                'id' => $this->id,
                'nom' => $this->nom,
                'prenom' => $this->prenom
            ], $retour);
        // L'ID du visiteur doit permettre de donner son nom
        $retour = $this->accesPdo->getNomVisiteur($this->id);
        $this->assertContains($this->nom, $retour);
        $this->assertContains($this->prenom, $retour);
        // tester la récupération du véhicule
        $retour = $this->accesPdo->getVehicule($this->id);
        $this->assertEquals($this->puissanceAdmin, $retour['puissance_admin']);
    }

    public function testVisiteurAucuneFiche()
    {
        // Vérifier qu'il n'a pas de fiche de frais ouverte
        $retour = $this->accesPdo->getLesMoisDisponibles($this->id);
        $this->assertEmpty($retour);
        // Vérifier qu'il n'a pas de fiche de frais à Valider, donc avec statut = 'CL'
        $retour = $this->accesPdo->getLesMoisAValider($this->id);
        $this->assertEmpty($retour);
    }

    public function testCreationFiches()
    {
        // Créer une fiche de Frais Forfait : elle ne doit pas exister !
        $retour = $this->accesPdo->estPremierFraisMois($this->id, $this->mois);
        $this->assertTrue($retour);
        // Il n'y a donc pas d'infos
        $retour = $this->accesPdo->getLesInfosFicheFrais($this->id, $this->mois);
        $this->assertEmpty($retour);
        // Je peux créer la fiche FORFAIT
        $retour = $this->accesPdo->creeNouvellesLignesFrais($this->id,
            $this->mois);
        // Je vérifie qu'elle existe
        $retour = $this->accesPdo->estPremierFraisMois($this->id, $this->mois);
        $this->assertFalse($retour);
        // Je récupère des infos ?
        $retour = $this->accesPdo->getLesInfosFicheFrais($this->id, $this->mois);
        $this->assertNotEmpty($retour);
        // Vérifier le flag PDF
        $this->assertEquals(0,$retour['etatPDF']);
        $this->accesPdo->setPDFtraite($this->id,$this->mois);
        //Vérifier que c'est bon
        $retour = $this->accesPdo->getLesInfosFicheFrais($this->id,$this->mois);
        $this->assertEquals(1,$retour['etatPDF']);
        //Je crée également une fiche "mois précédent", vide et non testée.
        $retour = $this->accesPdo->creeNouvellesLignesFrais($this->id,
            $this->moisprecedent);
    }

    public function testAjoutDonneesFiches()
    {
        // Ajouter des frais
        $retour = $this->accesPdo->majFraisForfait($this->id, $this->mois,
            $this->FraisForfait);
        $this->assertNotEquals(0, $retour);
        // Vérifier si les frais ont été écrits
        $retour = $this->accesPdo->getLesFraisForfait($this->id, $this->mois);
        $this->assertEquals($this->FraisForfait['ETP'], $retour[0]['quantite']);
        $this->assertEquals($this->FraisForfait['KM'], $retour[1]['quantite']);
        $this->assertEquals($this->FraisForfait['NUI'], $retour[2]['quantite']);
        $this->assertEquals($this->FraisForfait['REP'], $retour[3]['quantite']);
        // Vérifier qu'il n'y a pas de justificatifs
        $retour = $this->accesPdo->getNbjustificatifs($this->id, $this->mois);
        $this->assertEquals(0, $retour);
        // Insérer un justificatif
        $retour = $this->accesPdo->majNbJustificatifs($this->id, $this->mois, 2);
        $this->assertTrue($retour);
        // Vérifier si il a bien été écrit
        $retour = $this->accesPdo->getNbjustificatifs($this->id, $this->mois);
        $this->assertEquals(2, $retour);
        // Ajouter des frais hors forfait
        $this->accesPdo->creeNouveauFraisHorsForfait($this->id, $this->mois,
            $this->HF1['libelle'], $this->HF1['date'], $this->HF1['montant']);
        $this->accesPdo->creeNouveauFraisHorsForfait($this->id, $this->mois,
            $this->HF2['libelle'], $this->HF2['date'], $this->HF2['montant']);
        $this->accesPdo->creeNouveauFraisHorsForfait($this->id, $this->mois,
            $this->HF3['libelle'], $this->HF3['date'], $this->HF3['montant']);
        $this->accesPdo->creeNouveauFraisHorsForfait($this->id, $this->mois,
            $this->HF4['libelle'], $this->HF4['date'], $this->HF4['montant']);
        // Vérifier que les frais ont été insérés
        $retour = $this->accesPdo->getLesFraisHorsForfait($this->id, $this->mois);
        $this->assertArraySubset($this->HF1, $retour[0]);
        $this->assertArraySubset($this->HF2, $retour[1]);
        $this->assertArraySubset($this->HF3, $retour[2]);
        $this->assertArraySubset($this->HF4, $retour[3]);
    }

    public function testTotalDesFrais()
    {
        // Tenter les calculs.
        $totalFFCalcule = $this->accesPdo->effectueTotalFraisForfait($this->id,
            $this->mois);
        $this->assertEquals($this->totalFFTheo, $totalFFCalcule);
        $totalHFCalcule = $this->accesPdo->effectueTotalFraisHF($this->id,
            $this->mois);
        $this->assertEquals($this->totalHFTheo, $totalHFCalcule);
        // Refaire le même avec la validation totale.
        // Tester la somme validée
        $lesInfos = $this->accesPdo->getLesInfosFicheFrais($this->id,
            $this->mois);
        $this->assertEquals(0, $lesInfos['montantValide']);
        // mettre à jour
        $this->accesPdo->valideSommeFrais($this->id, $this->mois);
        // Vérifier la mise à jour
        $lesInfos = $this->accesPdo->getLesInfosFicheFrais($this->id,
            $this->mois);
        $this->assertEquals(($this->totalFFTheo + $this->totalHFTheo),
            $lesInfos['montantValide']);
    }

    public function testModifierFichesFrais()
    {
        // Modifier un Frais au Forfait
        $this->FraisForfait['ETP'] = 21; // ajoute 110 au total final
        $retour = $this->accesPdo->majFraisForfait($this->id, $this->mois,
            $this->FraisForfait);
        $this->assertNotEquals(0, $retour);
        // Modifier un frais hors forfait : ajouter 100 au premier frais.
        $lesFraisHF = $this->accesPdo->getLesFraisHorsForfait($this->id,
            $this->mois);
        $this->assertNotEmpty($lesFraisHF);
        $this->accesPdo->majFraisHorsForfait($this->id, $this->mois,
            $lesFraisHF[0]['libelle'], $lesFraisHF[0]['date'], 200,
            $lesFraisHF[0]['id']);
        // Vérifier si l'ensemble est pris en comtpe dans le total des frais.
        // mettre à jour
        $this->accesPdo->valideSommeFrais($this->id, $this->mois);
        // Vérifier la mise à jour
        $lesInfos = $this->accesPdo->getLesInfosFicheFrais($this->id,
            $this->mois);
        $this->assertEquals(($this->totalFFTheo + $this->totalHFTheo + 210),
            $lesInfos['montantValide']);
    }

    public function testSuppressionReport()
    {
        // Supprimer un frais HF
        $lesFraisHF = $this->accesPdo->getLesFraisHorsForfait($this->id,
            $this->mois);
        $this->assertNotEmpty($lesFraisHF);
        $this->accesPdo->supprimerFraisHorsForfait($lesFraisHF[0]['id']);
        // Vérifier qu'il n'y a plus que 3 frais HF en liste
        $lesFraisHF = $this->accesPdo->getLesFraisHorsForfait($this->id,
            $this->mois);
        $this->assertCount(3, $lesFraisHF);
        // reporter un frais HF
        $lesFraisHF = $this->accesPdo->getLesFraisHorsForfait($this->id,
            $this->mois);
        $this->accesPdo->reporteFraisHorsForfait($this->id, $this->mois,
            'REPORTE : ' . $lesFraisHF[0]['libelle'], $lesFraisHF[0]['date'],
            $lesFraisHF[0]['montant'], $lesFraisHF[0]['id']);
        // Vérifier qu'il n'y a plus que 2 frais HF en liste
        $lesFraisHF = $this->accesPdo->getLesFraisHorsForfait($this->id,
            $this->mois);
        $this->assertCount(2, $lesFraisHF);
        // Vérifier qu'il existe déjà une nouvelle fiche pour le mois suivant
        $retour = $this->accesPdo->estPremierFraisMois($this->id,
            $this->moissuivant);
        $this->assertFalse($retour);
        // Je récupère des infos ?
        $retour = $this->accesPdo->getLesInfosFicheFrais($this->id,
            $this->moissuivant);
        $this->assertNotEmpty($retour);
        // Le FraisHF du mois suivant commence-t-il par REPORTE et dispose-t-il des mêmes infos ?
        $lesFraisHF = $this->accesPdo->getLesFraisHorsForfait($this->id,
            $this->moissuivant);
        // un seul frais
        $this->assertCount(1, $lesFraisHF);
        // vérification des informations
        $this->assertArraySubset($this->HFReport, $lesFraisHF[0]);
    }

    public function testVisiteurAvecFiche()
    {
        // Vérifier qu'il n'a pas de fiche de frais ouverte
        $retour = $this->accesPdo->getLesMoisDisponibles($this->id);
        $this->assertNotEmpty($retour);
        // Vérifier qu'il n'a pas de fiche de frais à Valider, donc avec statut = 'CL'
        $retour = $this->accesPdo->getLesMoisAValider($this->id);
        $this->assertEmpty($retour);
    }

    public function testManipulationStatutFiche()
    {
        // Remettre les fiches à "CR"
        $this->accesPdo->majEtatficheFrais($this->id, $this->mois, 'CR');
        $this->accesPdo->majEtatficheFrais($this->id, $this->moissuivant, 'CR');
        // Vérifier que c'est bien passé
        $reponse = $this->accesPdo->getLesInfosficheFrais($this->id, $this->mois);
        $this->assertEquals('CR', $reponse['idEtat']);
        $reponse = $this->accesPdo->getLesInfosficheFrais($this->id,
            $this->moissuivant);
        $this->assertEquals('CR', $reponse['idEtat']);
        // Clôturer les fiches
        $this->accesPdo->clotureFichesMoisPrecedent();
        // Vérifier que c'est bien passé
        $reponse = $this->accesPdo->getLesInfosficheFrais($this->id, $this->mois);
        $this->assertEquals('CL', $reponse['idEtat']);
        //Vérifier la fonction LesMoisAValider
        $retour = $this->accesPdo->getLesMoisAValider($this->id);
        $this->assertNotEmpty($retour);
        // Valider une fiche
        $this->accesPdo->valideSommeFrais($this->id, $this->mois);
        // Vérifier que c'est bien passé
        $reponse = $this->accesPdo->getLesInfosficheFrais($this->id, $this->mois);
        $this->assertEquals('VA', $reponse['idEtat']);
        // Mettre en paiement la fiche : ça marchera car le mois est volontairement ancien
        $this->accesPdo->mettreEnPaiementVAMoisPrecedent(date("j"));
        // Vérifier que c'est bien passé
        $reponse = $this->accesPdo->getLesInfosficheFrais($this->id, $this->mois);
        $this->assertEquals('MP', $reponse['idEtat']);
        // Mettre en paiement la fiche : ça marchera car le mois est volontairement ancien
        $this->accesPdo->rembourserMPMoisPrecedent(date("j"));
        // Vérifier que c'est bien passé
        $reponse = $this->accesPdo->getLesInfosficheFrais($this->id, $this->mois);
        $this->assertEquals('RB', $reponse['idEtat']);
    }

    public function testDernierTest()
    {
        $this->detruireDonnees();
        // Vérifier qu'il n'y a plus de frais Forfait
        $retour = $this->accesPdo->getLesFraisForfait($this->id, $this->mois);
        $this->assertEmpty($retour);
        $retour = $this->accesPdo->getLesFraisForfait($this->id,
            $this->moissuivant);
        $this->assertEmpty($retour);
        // Vérifier qu'il n'y a plus de frais hors forfait
        $retour = $this->accesPdo->getLesFraisHorsForfait($this->id, $this->mois);
        $this->assertEmpty($retour);
        $retour = $this->accesPdo->getLesFraisHorsForfait($this->id,
            $this->moissuivant);
        $this->assertEmpty($retour);
        // Plus de fiche frais
        $retour = $this->accesPdo->getLesInfosficheFrais($this->id, $this->mois);
        $this->assertEmpty($retour);
        $this->detruireVisiteurFictif();
    }
}

