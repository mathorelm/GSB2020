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

//--------------------------------------------------------------
// Jeu de variables pour adaptation du comportement du programme
$balance = 14221.21;                //balance du compte de paiement
$limiteVAVersMP = 20;               //jour de mise en paiement
$limiteMPVersRB = 30;               //jour (présumé) de remboursement
//--------------------------------------------------------------

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

$pdo->mettreEnPaiementVAMoisPrecedent();
$pdo->rembourserMPMoisPrecedent();

$id_visiteur=filter_input(INPUT_POST,'visiteur',FILTER_SANITIZE_STRING);
$mois=filter_input(INPUT_POST,'mois',FILTER_SANITIZE_STRING);

switch ($action) {
    case 'afficherSuivi':
        break;
    case 'VAversMP':
        $pdo->majEtatFicheFrais($id_visiteur,$mois,'MP');
        break;
    case 'MPversVA':
        $pdo->majEtatFicheFrais($id_visiteur,$mois,'VA');
        break;
    case 'MPversRB':
        $pdo->majEtatFicheFrais($id_visiteur,$mois,'RB');
        break;
    case 'VAversCL':
        $pdo->majEtatFicheFrais($id_visiteur,$mois,'CL');
        break;
}
$lesfichesVA = $pdo->getLesFiches('VA');
$lesfichesMP = $pdo->getLesFiches('MP');
$lesfichesRB = $pdo->getLesFiches('RB');
//Déterminer les montants totaux concernés.
$montantVA = CompterMontantTotal($lesfichesVA);
$montantMP = CompterMontantTotal($lesfichesMP);
$montantRB = CompterMontantTotal($lesfichesRB);
$ficheaffichee=CompterfichesPerimees($lesfichesRB);
include 'vues/v_suiviFraisComptable.php';
