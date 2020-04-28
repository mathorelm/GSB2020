<?php
/**
 * Gestion de la validation des frais
 *
 * PHP Version 7
 *
 * /package   GSB
 * /author    Louis-Marin MATHOREL <gsb2020@free.fr>
 * /copyright 2017 Réseau CERTA
 * /license   Réseau CERTA
 * /version   GIT: <0>
 * /link      http://gsb2020.free.fr Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
// Clôturer les fiches de frais du mois précédent
$pdo->clotureFichesMoisPrecedent();
$lesVisiteurs = $pdo->getLesVisiteurs();
$i = 0;
foreach ($lesVisiteurs as $unVisiteur) {
    $unJeuDonnees = $pdo->getLesMoisAValider($unVisiteur['id']);
    if ($unJeuDonnees != null) {
        foreach ($unJeuDonnees as $Donnee) {
            $TouslesMois[$i] = array(
                'ID' => $unVisiteur['id'],
                'mois' => $Donnee['mois'],
                'numAnnee' => $Donnee['numAnnee'],
                'numMois' => $Donnee['numMois']
            );
            $i ++;
        }
        ;
    } else {
        unset($lesVisiteurs[array_search($unVisiteur, $lesVisiteurs)]);
    }
    ;
}
switch ($action) {
    case 'selectionnerUtilisateur':
        include 'vues/v_listeVisiteurs.inc.php';
        break;
    case 'voirListeFrais':
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs',
            FILTER_SANITIZE_STRING);
        $nomPrenom = $pdo->getNomVisiteur($idVisiteur);
        $moisFiche = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $moisFiche);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,
            $moisFiche);
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $moisFiche);
        include 'vues/v_listeVisiteurs.inc.php';
        include 'vues/v_validFraisComptable.inc.php';
        include 'vues/v_validFraisHFComptable.inc.php';
        break;
    case "corrigerFraisForfait":
        $idVisiteur = filter_input(INPUT_POST, 'idNom', FILTER_SANITIZE_STRING);
        $nomPrenom = $pdo->getNomVisiteur($idVisiteur);
        $moisFiche = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_STRING);
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT,
            FILTER_FORCE_ARRAY);
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $moisFiche, $lesFrais);
            ajouterInfo(
                'La modification des frais forfaitisés à été prise en compte ! ');
            include 'vues/v_info.inc.php';
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.inc.php';
        }
        ;
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $moisFiche);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,
            $moisFiche);
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $moisFiche);
        include 'vues/v_listeVisiteurs.inc.php';
        include 'vues/v_validFraisComptable.inc.php';
        include 'vues/v_validFraisHFComptable.inc.php';
        break;
    case "corrigerFraisHF":
        $idVisiteur = filter_input(INPUT_POST, 'idNom', FILTER_SANITIZE_STRING);
        $nomPrenom = $pdo->getNomVisiteur($idVisiteur);
        $moisFiche = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_STRING);
        $idFiche = filter_input(INPUT_POST, 'idFiche', FILTER_SANITIZE_STRING);
        $dateFrais = dateAnglaisVersFrancais(
            filter_input(INPUT_POST, 'HFdate', FILTER_SANITIZE_STRING));
        $libelle = filter_input(INPUT_POST, 'HFlibelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'HFmontant', FILTER_VALIDATE_FLOAT);
        if (nbErreurs() != 0) {
            include 'vues/v_erreurs.inc.php';
        } else {
            if (substr($libelle, 0, 6) == "REPORT") {
                // traitement spécifique : suppression mois actuel + insertion mois suivant
                $pdo->reporteFraisHorsForfait($idVisiteur, $moisFiche, $libelle,
                    $dateFrais, $montant, $idFiche);
                ajouterInfo("Report de la ligne au mois suivant.");
            } else {
                $pdo->majFraisHorsForfait($idVisiteur, $moisFiche, $libelle,
                    $dateFrais, $montant, $idFiche);
                ajouterInfo(
                    "La modification demandée a été effectuée pour '" . $libelle .
                    "' (" . $montant . "€) à la date du " . $dateFrais);
            }
            include 'vues/v_info.inc.php';
        }
        ;
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $moisFiche);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,
            $moisFiche);
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $moisFiche);
        include 'vues/v_listeVisiteurs.inc.php';
        include 'vues/v_validFraisComptable.inc.php';
        include 'vues/v_validFraisHFComptable.inc.php';
        break;

    case "validerFiche":
        $nbJustificatifs = filter_input(INPUT_POST, 'nbJustificatifs',
            FILTER_SANITIZE_STRING);
        $idVisiteur = filter_input(INPUT_POST, 'idNom', FILTER_SANITIZE_STRING);
        $moisFiche = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_STRING);
        $pdo->majNbJustificatifs($idVisiteur, $moisFiche, $nbJustificatifs);
        $pdo->valideSommeFrais($idVisiteur, $moisFiche);
        $pdo->majEtatFicheFrais($idVisiteur, $moisFiche, "VA");
        ajouterInfo("La validation a été effectuée !");
        include 'vues/v_info.inc.php';
        header('Location: index.php');
        break;
}
