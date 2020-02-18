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
 * @link      http://gsb2020.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

switch ($action) {
    case 'afficherSuivi':
        //Récupérer les données de la base pour les ordonner par colonnes
        $lesfichesVA = $pdo->getLesFiches('VA');

        $lesfichesMP = $pdo->getLesFiches('MP');
        $lesfichesRB = $pdo->getLesFiches('RB');
        include 'vues/v_suiviFraisComptable.php';
        break;
}
