<?php
/**
 * Gestion de la validation des frais
 *
 * PHP Version 7
 *
 * @package   GSB
 * @author    Louis-Marin MATHOREL <gsb2020@free.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://gsb2020.free.fr Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
//Clôturer les fiches de frais du mois précédent
$pdo->clotureFichesMoisPrecedent();
$lesVisiteurs = $pdo->getLesVisiteurs();
$i=0;
foreach ($lesVisiteurs as $unVisiteur) {
    $unJeuDonnees = $pdo->getLesMoisAValider($unVisiteur['id']);
    if ($unJeuDonnees !=null) {
        foreach ($unJeuDonnees as $Donnee) {
            $TouslesMois[$i] = array(
                'ID' => $unVisiteur['id'],
                'mois' => $Donnee['mois'],
                'numAnnee' => $Donnee['numAnnee'],
                'numMois' => $Donnee['numMois']
            );
            $i++;
        };
    } else {
        unset($lesVisiteurs[array_search($unVisiteur,$lesVisiteurs)]);
    };
}
switch ($action) {
    case 'selectionnerUtilisateur':
        include 'vues/v_listeVisiteurs.php';
        break;
    case 'voirListeFrais':
        $id_visiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $nom_prenom = $pdo->getNomVisiteur($id_visiteur);
        $mois_fiche = filter_input(INPUT_POST,'lstMois',FILTER_SANITIZE_STRING);
        $lesFraisForfait = $pdo->getLesFraisForfait($id_visiteur, $mois_fiche);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($id_visiteur, $mois_fiche);
        $nbJustificatifs = $pdo ->getNbjustificatifs($id_visiteur, $mois_fiche);
        include 'vues/v_listeVisiteurs.php';
        include 'vues/v_validFraisComptable.php';
        include 'vues/v_validFraisHFComptable.php';
        break;
    case "corrigerFraisForfait":
        $id_visiteur=filter_input(INPUT_POST,'idNom',FILTER_SANITIZE_STRING);
        $nom_prenom = $pdo->getNomVisiteur($id_visiteur);
        $mois_fiche=filter_input(INPUT_POST,'mois',FILTER_SANITIZE_STRING);
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($id_visiteur, $mois_fiche, $lesFrais);
            ajouterInfo('La modification des frais forfaitisés à été prise en compte ! ');
            include 'vues/v_info.php';
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        };
        $lesFraisForfait = $pdo->getLesFraisForfait($id_visiteur, $mois_fiche);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($id_visiteur, $mois_fiche);
        $nbJustificatifs = $pdo ->getNbjustificatifs($id_visiteur, $mois_fiche);
        include 'vues/v_listeVisiteurs.php';
        include 'vues/v_validFraisComptable.php';
        include 'vues/v_validFraisHFComptable.php';
        break;
    case "corrigerFraisHF":
        $id_visiteur=filter_input(INPUT_POST,'idNom',FILTER_SANITIZE_STRING);
        $nom_prenom = $pdo->getNomVisiteur($id_visiteur);
        $mois_fiche=filter_input(INPUT_POST,'mois',FILTER_SANITIZE_STRING);
        $id_fiche=filter_input(INPUT_POST,'idFiche',FILTER_SANITIZE_STRING);
        $dateFrais = dateAnglaisVersFrancais(filter_input(INPUT_POST, 'HFdate', FILTER_SANITIZE_STRING));
        $libelle = filter_input(INPUT_POST, 'HFlibelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'HFmontant', FILTER_VALIDATE_FLOAT);
        if (nbErreurs() != 0) {
            include 'vues/v_erreurs.php';
        } else {
            if (substr($libelle,0,6)=="REPORT") {
                //traitement spécifique : suppression mois actuel + insertion mois suivant
                $pdo->reporteFraisHorsForfait($id_visiteur, $mois_fiche, $libelle, $dateFrais, $montant,$id_fiche);
                ajouterInfo("Report de la ligne au mois suivant.");
            } else {
                $pdo->majFraisHorsForfait($id_visiteur, $mois_fiche, $libelle, $dateFrais, $montant,$id_fiche);
                ajouterInfo("La modification demandée a été effectuée pour '" . $libelle . "' (" . $montant . "€) à la date du " . $dateFrais);
            }
            include 'vues/v_info.php';
        };
        $lesFraisForfait = $pdo->getLesFraisForfait($id_visiteur, $mois_fiche);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($id_visiteur, $mois_fiche);
        $nbJustificatifs = $pdo ->getNbjustificatifs($id_visiteur, $mois_fiche);
        include 'vues/v_listeVisiteurs.php';
        include 'vues/v_validFraisComptable.php';
        include 'vues/v_validFraisHFComptable.php';
        break;

    case "validerFiche":
        $nbJustificatifs = filter_input(INPUT_POST,'nbJustificatifs',FILTER_SANITIZE_STRING);
        $id_visiteur = filter_input(INPUT_POST,'idNom',FILTER_SANITIZE_STRING);
        $mois_fiche = filter_input(INPUT_POST,'mois',FILTER_SANITIZE_STRING);
        $pdo->majNbJustificatifs($id_visiteur, $mois_fiche, $nbJustificatifs);
        $pdo->valideSommeFrais($id_visiteur,$mois_fiche);
        $pdo->majEtatFicheFrais($id_visiteur, $mois_fiche, "VA");
        ajouterInfo("La validation a été effectuée !");
        include('vues/v_info.php');
        header('Location: index.php');
        break;
}
