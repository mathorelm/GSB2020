<?php
/**
 * Gestion de la déconnexion
 *
 * PHP Version 7
 *
 * /package   GSB
 * /author    Réseau CERTA <contact@reseaucerta.org>
 * /author    José GIL <jgil@ac-nice.fr>
 * /copyright 2017 Réseau CERTA
 * /license   Réseau CERTA
 * /version   GIT: <0>
 * /link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
    case 'demandeDeconnexion':
        include 'vues/v_deconnexion.inc.php';
        addLogEvent(
            'Déconnexion de : ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] .
            ' (IP = ' . $_SERVER['REMOTE_ADDR'] . ')');
        break;
    case 'valideDeconnexion':
        if (estConnecte()) {
            include 'vues/v_deconnexion.inc.php';
        } else {
            ajouterErreur("Vous n'êtes pas connecté");
            include 'vues/v_erreurs.inc.php';
            include 'vues/v_connexion.inc.php';
        }
        break;
    default:
        include 'vues/v_connexion.inc.php';
        break;
}
