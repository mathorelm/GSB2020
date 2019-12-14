<?php
/**
 * Gestion de la validation des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Louis-Marin MATHOREL <gsb2020@free.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://gsb2020.free.fr Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = $_SESSION['idVisiteur'];
//Clôturer les fiches de frais du mois précédent
//clotureFichesMoisPrecedent();
switch ($action) {
    case 'afficheFrais':
        //TODO Forcer à la sélection d'un nom, d'une fiche avant de l'afficher. JS ?
        //include 'vues/v_afficheFrais.php';
        phpinfo();
        break;
}
