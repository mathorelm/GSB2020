<?php
/**
 * \brief Gestion de l'affichage des frais
 *
 * PHP Version 7
 *
 * \package   GSB
 * \author    Réseau CERTA <contact@reseaucerta.org>
 * \author    José GIL <jgil@ac-nice.fr>
 * \copyright 2017 Réseau CERTA
 * \license   Réseau CERTA
 * \version   GIT: <0>
 * \link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = $_SESSION['idVisiteur'];
switch ($action) {
    case 'selectionnerMois':
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        include 'vues/v_listeMois.inc.php';
        break;
    case 'voirEtatFrais':
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
        $moisASelectionner = $leMois;
        include 'vues/v_listeMois.inc.php';
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        // GENERATION PDF
        if ($lesInfosFicheFrais['idEtat'] == "RB") {
            if ($lesInfosFicheFrais['etatPDF']) {
                $donnees_visiteur = $pdo->getNomVisiteur($idVisiteur);
                $nom_visiteur = $donnees_visiteur['nom'];
                $lien_pdf = 'PDF/' . $lesFraisHorsForfait[0]['mois'] . '-' .
                    $nom_visiteur . '.pdf';
            } else {
                $lien_pdf = genererPDF($pdo, $lesFraisHorsForfait,
                    $lesFraisForfait, $lesInfosFicheFrais);
                if ($lien_pdf != "") {
                    $pdo->setPdfTraite($idVisiteur, $leMois);
                }
            }
        } else {
            $lien_pdf = "";
        }
        // FIN GENERATION
        include 'vues/v_etatFrais.inc.php';
        break;
    default:
        include 'vues/v_accueil.inc.php';
}
