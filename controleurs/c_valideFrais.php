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
//Clôturer les fiches de frais du mois précédent
$pdo->clotureFichesMoisPrecedent();
switch ($action) {
    case 'afficheFrais':
        $lesVisiteurs = $pdo->getLesVisiteurs();  
        $i=0;
        foreach ($lesVisiteurs as $unVisiteur) {            
            $unJeuDonnees = $pdo->getLesMoisDisponibles($unVisiteur['id']);
            if (isset($unJeuDonnees)) {
                foreach ($unJeuDonnees as $Donnee) {
                    $TouslesMois[$i] = array(
                        'ID' => $unVisiteur['id'],
                        'mois' => $Donnee['mois'],
                        'numAnnee' => $Donnee['numAnnee'],
                        'numMois' => $Donnee['numMois']
                    );                    
                    $i++;
                };
            };
        }        
        include 'vues/v_listeVisiteurs.php';        
        break;
}
