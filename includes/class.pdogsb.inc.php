<?php

/**
 * Classe d'accès aux données.
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL - CNED <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

/**
 * Classe d'accès aux données.
 *
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe
 *
 * PHP Version 7
 *
 * @category PPE
 * @package GSB
 * @author Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license Réseau CERTA
 * @version Release: 1.0
 * @link http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */
class PdoGsb
{

    static $serveur = 'mysql:host=localhost';
        
    static $bdd = 'dbname=gsb_frais';    

    static $user = 'userGsb';

    static $mdp = 'secret';

    static $monPdo;

    static $monPdoGsb = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct()
    {
        PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
        PdoGsb::$monPdo->query('SET CHARACTER SET utf8');
    }

    /**
     * Méthode destructeur appelée dès qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct()
    {
        PdoGsb::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb()
    {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;
    }

    /**
     * Retourne les informations d'un visiteur
     *
     * @param String $login
     *            Login du visiteur
     * @param String $mdp
     *            Mot de passe du visiteur
     *            
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login, $mdp)
    /*TODO Test si la fonction est exécutée avec un faux login / vrai mdp  --> erreur */
    /*TODO Test si la fonction est exécutée avec un faux login / faux mdp  --> erreur */
    /*TODO Test si la fonction est exécutée avec un vrai login / vrai mdp  --> erreur */
    {
        $requetePrepare = PdoGsb::$monPdo->prepare('SELECT personnels.id AS id, personnels.nom AS nom, ' . 'personnels.prenom AS prenom, ' . 'metiers.libelle AS metier ' . 'FROM personnels,metiers ' . 'WHERE personnels.login = :unLogin AND personnels.mdp = :unMdp' . ' AND personnels.metier = metiers.idMetiers');
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMdp', $mdp, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
    /**
     * Retourne le nom d'un visiteur en fonction de son ID.
     *
     * @param String $id
     *            ID du visiteur 
     *
     * @return le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getNomVisiteur($id)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare('SELECT personnels.nom AS nom, ' . 'personnels.prenom AS prenom FROM personnels WHERE personnels.id = :unId');
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);        
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
    
    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     *            
     * @return tous les champs des lignes de frais hors forfait sous la forme
     *         d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois)
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un mois erroné (mois non présent en BDD) --> pas d'erreur mais enregistrement vide ?*/
    {
        $requetePrepare = PdoGsb::$monPdo->prepare('SELECT * FROM lignefraishorsforfait ' . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur ' . 'AND lignefraishorsforfait.mois = :unMois');
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        for ($i = 0; $i < count($lesLignes); $i ++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     *            
     * @return le nombre entier de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $mois)
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un mois erroné (mois non présent en BDD) --> pas d'erreur mais enregistrement vide ?*/
    {
        $requetePrepare = PdoGsb::$monPdo->prepare('SELECT fichefrais.nbjustificatifs as nb FROM fichefrais ' . 'WHERE fichefrais.idvisiteur = :unIdVisiteur ' . 'AND fichefrais.mois = :unMois');
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     *            
     * @return l'id, le libelle et la quantité sous la forme d'un tableau
     *         associatif
     */
    public function getLesFraisForfait($idVisiteur, $mois)
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un mois erroné (mois non présent en BDD) --> pas d'erreur mais enregistrement vide ?*/
    {
        $requetePrepare = PdoGSB::$monPdo->prepare('SELECT fraisforfait.id as idfrais, ' . 'fraisforfait.libelle as libelle, ' . 'lignefraisforfait.quantite as quantite ' . 'FROM lignefraisforfait ' . 'INNER JOIN fraisforfait ' . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait ' . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur ' . 'AND lignefraisforfait.mois = :unMois ' . 'ORDER BY lignefraisforfait.idfraisforfait');
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return un tableau associatif
     */
    public function getLesIdFrais()
    {
        $requetePrepare = PdoGsb::$monPdo->prepare('SELECT fraisforfait.id as idfrais ' . 'FROM fraisforfait ORDER BY fraisforfait.id');
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     * @param Array $lesFrais
     *            tableau associatif de clé idFrais et
     *            de valeur la quantité pour ce frais
     *            
     * @return null
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais)
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un mois erroné (mois non présent en BDD) --> pas d'erreur mais enregistrement vide ?*/
    /*TODO Test sur une tableau $lesFrais non fourni --> comportement ?*/
    {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = PdoGSB::$monPdo->prepare('UPDATE lignefraisforfait ' . 'SET lignefraisforfait.quantite = :uneQte ' . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur ' . 'AND lignefraisforfait.mois = :unMois ' . 'AND lignefraisforfait.idfraisforfait = :idFrais');
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs
     *            Nombre de justificatifs
     *            
     * @return null
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un mois erroné (mois non présent en BDD) --> pas d'erreur mais enregistrement vide ?*/
    /*TODO Test sur une tableau $nbJustificatifs non fourni --> comportement ?*/
    {
        $requetePrepare = PdoGSB::$monPdo->prepare('UPDATE fichefrais ' . 'SET nbjustificatifs = :unNbJustificatifs ' . 'WHERE fichefrais.idvisiteur = :unIdVisiteur ' . 'AND fichefrais.mois = :unMois');
        $requetePrepare->bindParam(':unNbJustificatifs', $nbJustificatifs, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Insère dans ficheFrais le montant validé par le comptable
     * @param String $idVisiteur
     * @param String $mois
     */
    public function valideSommeFrais($idVisiteur,$mois) {
        $montantValide = 0;
        $montantValide = $this->effectueTotalFraisForfait($idVisiteur,$mois);
        $montantValide +=$this->effectueTotalFraisHF($idVisiteur,$mois);
        $requetePrepare = PdoGSB::$monPdo->prepare('UPDATE fichefrais ' . 'SET montantvalide = :unMontant ' . 'WHERE fichefrais.idvisiteur = :unIdVisiteur ' . 'AND fichefrais.mois = :unMois');
        $requetePrepare->bindParam(':unMontant', $montantValide, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Retourne le total des frais au forfait de la fiche (en exploitant le montant unitaire)
     * @param String $idVisiteur
     * @param String $mois
     * @return Float le total des frais forfait
     */
    public function effectueTotalFraisForfait($idVisiteur,$mois) {
        $maRequete = 'SELECT SUM(lignefraisforfait.quantite*fraisforfait.montant) as total';
        $maRequete = $maRequete . ' FROM lignefraisforfait JOIN fraisforfait';
        $maRequete = $maRequete . ' ON lignefraisforfait.idfraisforfait = fraisforfait.id';
        $maRequete = $maRequete . ' WHERE idvisiteur = :unIdVisiteur AND mois = :unMois';
        
        $requetePrepare = PdoGSB::$monPdo->prepare($maRequete);
        $requetePrepare->bindParam(':unIdVisiteur',$idVisiteur,PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois',$mois,PDO::PARAM_STR);
        $requetePrepare->execute();
        $TEST_retour = $requetePrepare->fetch();
        return (float) $TEST_retour['total'];
    }
    
    /**
     * Retourne le total des frais hors forfait (hors lignes REFUSE)
     * @param String $idVisiteur
     * @param String $mois
     * @return Float le total des frais hors forfait
     */
    public function effectueTotalFraisHF($idVisiteur,$mois) {
        $lesFraisHorsForfait = $this->getLesFraisHorsForfait($idVisiteur, $mois);
        $totalFrais = 0;
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $testlibelle=substr($unFraisHorsForfait['libelle'],0,6);
            if ($testlibelle<>"REFUSE") {
                $totalFrais += $unFraisHorsForfait['montant'];
            }
        }
        return (float) $totalFrais;
    }
    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     *            
     * @return vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois)
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un mois erroné (>mois actuel) --> erreur*/
    {
        $boolReturn = false;
        $requetePrepare = PdoGsb::$monPdo->prepare('SELECT fichefrais.mois FROM fichefrais ' . 'WHERE fichefrais.mois = :unMois ' . 'AND fichefrais.idvisiteur = :unIdVisiteur');
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (! $requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur
     *            ID du visiteur
     *            
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur)
    /*TODO Test sur un visiteur faux --> erreur*/    
    {
        $requetePrepare = PdoGsb::$monPdo->prepare('SELECT MAX(mois) as dernierMois ' . 'FROM fichefrais ' . 'WHERE fichefrais.idvisiteur = :unIdVisiteur');
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     *
     * Récupère le dernier mois en cours de traitement, met à 'CL' son champs
     * idEtat, crée une nouvelle fiche de frais avec un idEtat à 'CR' et crée
     * les lignes de frais forfait de quantités nulles
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     *            
     * @return null
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois)
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un mois erroné (mois non présent en BDD) --> pas d'erreur mais enregistrement vide ?*/   
    {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $requetePrepare = PdoGsb::$monPdo->prepare('INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,' . 'montantvalide,datemodif,idetat) ' . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')");
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = PdoGsb::$monPdo->prepare('INSERT INTO lignefraisforfait (idvisiteur,mois,' . 'idfraisforfait,quantite) ' . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)');
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais['idfrais'], PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     * @param String $libelle
     *            Libellé du frais
     * @param String $date
     *            Date du frais au format français jj//mm/aaaa
     * @param Float $montant
     *            Montant du frais
     *            
     * @return null
     */
    public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant
    )
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un paramètre passé vide --> erreur ?*/
    {
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$monPdo->prepare('INSERT INTO lignefraishorsforfait ' . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,' . ':unMontant) ');
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }
    
    /**
     * Met à jour la fiche avec le numéro $idFrais
     * @param String $idVisiteur
     * @param String $mois
     * @param String $libelle
     * @param String $date
     * @param Float $montant
     * @param Int $idFrais
     */
    public function majFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant,$idFrais)
    {
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$monPdo->prepare('UPDATE lignefraishorsforfait SET libelle=:unLibelle,date=:uneDateFr,montant=:unMontant WHERE id=:unIdFrais AND idvisiteur=:unIdVisiteur AND mois=:unMois');
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_INT);
        $requetePrepare->execute();
    }
    
   /**
    * Reporte les éléments passés en arguments sur le mois suivant. Crée la fiche du mois suivant si nécessaire.
    * @param String $id_visiteur
    * @param String $mois_fiche
    * @param String $libelle
    * @param String $dateFrais
    * @param Float $montant
    * @param Int $id_fiche
    */
    public function reporteFraisHorsForfait($id_visiteur, $mois_fiche, $libelle, $dateFrais, $montant,$id_fiche) 
    {
        //supprimer la ligne dans le mois actuel
        $this->supprimerFraisHorsForfait($id_fiche);
        //vérifier si il existe une fiche pour le mois suivant --> la créer avec frais à zéro en forfaitisé.
        $mois_suivant = date('d-m-Y', strtotime('+1 month'));
        $mois_suivant = str_replace("-","/",$mois_suivant);
        $mois_suivant=getMois($mois_suivant);
        if ($this->estPremierFraisMois($id_visiteur, $mois_suivant)) {
            //il n'y a pas de fiche...créer les frais forfaits à zéro
            $this->creeNouvellesLignesFrais($id_visiteur, $mois_suivant);            
        }
        $this->creeNouveauFraisHorsForfait($id_visiteur, $mois_suivant, $libelle, $dateFrais, $montant);          
    }
    
    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais
     *            ID du frais
     *            
     * @return null
     */
    public function supprimerFraisHorsForfait($idFrais)
    /*TODO Test sur une $idFrais erroné --> comportement ?*/
    {
        $requetePrepare = PdoGSB::$monPdo->prepare('DELETE FROM lignefraishorsforfait ' . 'WHERE lignefraishorsforfait.id = :unIdFrais');
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais
     *
     * @param String $idVisiteur
     *            ID du visiteur
     *            
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur)
    /*TODO Test sur un visiteur faux --> erreur*/    
    {
        $requetePrepare = PdoGSB::$monPdo->prepare('SELECT fichefrais.mois AS mois FROM fichefrais ' . 'WHERE fichefrais.idvisiteur = :unIdVisiteur ' . 'ORDER BY fichefrais.mois desc');
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }
    
    /**
     * Retourne les mois pour lesquel un visiteur a une fiche à valider
     *
     * @param String $idVisiteur
     *            ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisAValider($idVisiteur)
    /*TODO Test sur un visiteur faux --> erreur*/
    {
        $requetePrepare = PdoGSB::$monPdo->prepare('SELECT fichefrais.mois AS mois FROM fichefrais ' . "WHERE fichefrais.idvisiteur = :unIdVisiteur AND idetat = 'CL'" . 'ORDER BY fichefrais.mois desc');
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }
    /**
     * Retourne les visiteurs de la liste
     *
     * @return un tableau associatif comportant nom, prenom de tous les visiteurs
     */
    public function getLesVisiteurs()    
    {
        $requetePrepare = PdoGSB::$monPdo->prepare('SELECT personnels.id,personnels.nom,personnels.prenom from personnels WHERE metier=1 ORDER BY personnels.nom ASC');        
        $requetePrepare->execute();
        $lesVisiteurs = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $unID = $laLigne['id'];
            $nom = $laLigne['nom'];
            $prenom = $laLigne['prenom'];
            $lesVisiteurs[] = array(
                'id' => $unID,
                'nom' => $nom,
                'prenom' => $prenom                
            );
        }
        return $lesVisiteurs;
    }
    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     *            
     * @return un tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois)
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un mois erroné (mois non présent en BDD) --> pas d'erreur mais enregistrement vide ?*/
    {
        $requetePrepare = PdoGSB::$monPdo->prepare('SELECT fichefrais.idetat as idEtat, ' . 'fichefrais.datemodif as dateModif,' . 'fichefrais.nbjustificatifs as nbJustificatifs, ' . 'fichefrais.montantvalide as montantValide, ' . 'etat.libelle as libEtat ' . 'FROM fichefrais ' . 'INNER JOIN etat ON fichefrais.idetat = etat.id ' . 'WHERE fichefrais.idvisiteur = :unIdVisiteur ' . 'AND fichefrais.mois = :unMois');
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur
     *            ID du visiteur
     * @param String $mois
     *            Mois sous la forme aaaamm
     * @param String $etat
     *            Nouvel état de la fiche de frais
     *            
     * @return null
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat)
    /*TODO Test sur un visiteur faux --> erreur*/
    /*TODO Test sur un mois erroné (mois non présent en BDD) --> pas d'erreur mais enregistrement vide ?*/
    /*TODO Test sur $etat non fourni/erroné ? --> comportement ?*/
    {
        $requetePrepare = PdoGSB::$monPdo->prepare('UPDATE ficheFrais ' . 'SET idetat = :unEtat, datemodif = now() ' . 'WHERE fichefrais.idvisiteur = :unIdVisiteur ' . 'AND fichefrais.mois = :unMois');
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Script de clôture des fiches de frais du mois précédent.
     * Analyse la base de données et place idEtat à CL si mois = mois précédent
     *
     * @return null
     */
    public function clotureFichesMoisPrecedent()
    /*TODO Test qui balaie l'intégralité de la table est vérifie*/
    /*TODO qu'aucune fiche d'un mois précédent ne reste "CR"*/
    {
        //retourne le mois précédent
        $laDate = date('d-m-Y', strtotime('-1 month'));
        $laDate = str_replace("-","/",$laDate);
        $moisPrecedent = getMois($laDate);
        $requetePrepare = PdoGSB::$monPdo->prepare('UPDATE ficheFrais ' . "SET idetat = 'CL', datemodif = now() " . "WHERE fichefrais.mois = :unMois AND idetat = 'CR'");
        $requetePrepare->bindParam(':unMois', $moisPrecedent, PDO::PARAM_STR);
        $requetePrepare->execute();
        
    }
}
