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
        $lesfichesVA = $pdo->getLesFiches('VA');
        $lesfichesMP = $pdo->getLesFiches('MP');
        $lesfichesRB = $pdo->getLesFiches('RB');
        //Déterminer les montants totaux concernés.
        $montantVA = CompterMontantTotal($lesfichesVA);
        $montantMP = CompterMontantTotal($lesfichesMP);
        $montantRB = CompterMontantTotal($lesfichesRB);
        $ficheaffichee=CompterfichesPerimees($lesfichesRB);
        include 'vues/v_suiviFraisComptable.php';
        break;
}
