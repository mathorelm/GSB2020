<?php

/**
 * Fonctions pour l'application GSB
 *
 * PHP Version 7
 *
 * /package   GSB
 * /author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * /author    José GIL <jgil@ac-nice.fr>
 * /copyright 2017 Réseau CERTA
 * /license   Réseau CERTA
 * /version   GIT: <0>
 * /link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

/**
 * Teste si un quelconque visiteur est connecté
 *
 * @return vrai ou faux
 */
function estConnecte()
{
    return isset($_SESSION['idVisiteur']);
}

/**
 * Enregistre dans une variable session les infos d'un visiteur
 *
 * @param String $idVisiteur
 *            ID du visiteur
 * @param String $nom
 *            Nom du visiteur
 * @param String $prenom
 *            Prénom du visiteur
 * @param String $metier
 *            Code représentant le métier exercé
 * @return null
 */
function connecter($idVisiteur, $nom, $prenom, $metier)
{
    $_SESSION['idVisiteur'] = $idVisiteur;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['metier'] = $metier;
}

/**
 * Détruit la session active
 *
 * @return null
 */
function deconnecter()
{
    session_destroy();
}

/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais
 * aaaa-mm-jj
 *
 * @param String $maDate
 *            au format jj/mm/aaaa
 *
 * @return Date au format anglais aaaa-mm-jj
 */
function dateFrancaisVersAnglais($maDate)
{
    @list ($jour, $mois, $annee) = explode('/', $maDate);
    return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
}

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format
 * français jj/mm/aaaa
 *
 * @param String $maDate
 *            au format aaaa-mm-jj
 *
 * @return Date au format format français jj/mm/aaaa
 */
function dateAnglaisVersFrancais($maDate)
{
    @list ($annee, $mois, $jour) = explode('-', $maDate);
    $date = $jour . '/' . $mois . '/' . $annee;
    return $date;
}

/**
 * Retourne le mois au format aaaamm selon le jour dans le mois
 *
 * @param String $date
 *            au format jj/mm/aaaa
 *
 * @return String Mois au format aaaamm
 */
function getMois($date)
{
    @list ($jour, $mois, $annee) = explode('/', $date);
    unset($jour);
    if (strlen($mois) == 1) {
        $mois = '0' . $mois;
    }
    return $annee . $mois;
}

/**
 * Inverse la présentation du libellé aaaamm en mm/aaaa
 *
 * @param string $leLibelle
 *            au format aaaamm
 * @return string le mois au format mm/aaaa
 */
function inverseMois(string $leLibelle): string
{
    $annee = substr($leLibelle, 0, 4);
    $mois = substr($leLibelle, 4, 2);
    return $mois . "/" . $annee;
}

/**
 * Retour le mois en Français (en minuscule) avec l'année
 *
 * @param string $leLibelle
 *            au format aaaamm
 * @return string de type juillet 2020
 */
function moisEnLettre(string $leLibelle): string
{
    $lesMois = [
        "janvier",
        "février",
        "mars",
        "avril",
        "mai",
        "juin",
        "juillet",
        "août",
        "septembre",
        "octobre",
        "novembre",
        "décembre"
    ];
    $annee = substr($leLibelle, 0, 4);
    $mois = substr($leLibelle, 4, 2);
    return $lesMois[$mois - 1] . " " . $annee;
}

/* gestion des erreurs */

/**
 * Indique si une valeur est un entier positif ou nul
 *
 * @param Integer $valeur
 *            Valeur
 *
 * @return Boolean vrai ou faux
 */
function estEntierPositif($valeur)
{
    return preg_match('/[^0-9]/', $valeur) == 0;
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 *
 * @param Array $tabEntiers
 *            Un tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function estTableauEntiers($tabEntiers)
{
    $boolReturn = true;
    foreach ($tabEntiers as $unEntier) {
        if (!estEntierPositif($unEntier)) {
            $boolReturn = false;
        }
    }
    return $boolReturn;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 *
 * @param String $dateTestee
 *            Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateDepassee($dateTestee)
{
    $dateActuelle = date('d/m/Y');
    @list ($jour, $mois, $annee) = explode('/', $dateActuelle);
    $annee --;
    $anPasse = $annee . $mois . $jour;
    @list ($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
    return ($anneeTeste . $moisTeste . $jourTeste < $anPasse);
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 *
 * @param String $date
 *            Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateValide($date)
{
    $tabDate = explode('/', $date);
    $dateOK = true;
    if (count($tabDate) != 3) {
        $dateOK = false;
    } else {
        if (!estTableauEntiers($tabDate)) {
            $dateOK = false;
        } else {
            if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                $dateOK = false;
            }
        }
    }
    return $dateOK;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques
 *
 * @param Array $lesFrais
 *            Tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function lesQteFraisValides($lesFrais)
{
    return estTableauEntiers($lesFrais);
}

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais
 * et le montant
 *
 * Des message d'erreurs sont ajoutés au tableau des erreurs
 *
 * @param String $dateFrais
 *            Date des frais
 * @param String $libelle
 *            Libellé des frais
 * @param Float $montant
 *            Montant des frais
 *
 * @return null
 */
function valideInfosFrais($dateFrais, $libelle, $montant)
{
    if ($dateFrais == '') {
        ajouterErreur('Le champ date ne doit pas être vide');
    } else {
        if (!estDatevalide($dateFrais)) {
            ajouterErreur('Date invalide');
        } else {
            if (estDateDepassee($dateFrais)) {
                ajouterErreur(
                    "date d'enregistrement du frais dépassé, plus de 1 an");
            }
        }
    }
    if ($libelle == '') {
        ajouterErreur('Le champ description ne peut pas être vide');
    }
    if ($montant == '') {
        ajouterErreur('Le champ montant ne peut pas être vide');
    } elseif (!is_numeric($montant)) {
        ajouterErreur('Le champ montant doit être numérique');
    }
}

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 *
 * @param String $msg
 *            Libellé de l'erreur
 *
 * @return null
 */
function ajouterErreur(string $msg)
{
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    $_REQUEST['erreurs'][] = $msg;
    addLogEvent(
        'Erreur ("' . $msg . '") de ' . $_SESSION['prenom'] . ' ' .
        $_SESSION['nom'] . ' (IP = ' . $_SERVER['REMOTE_ADDR']);
}

/**
 * Retoune le nombre de lignes du tableau des erreurs
 *
 * @return Integer le nombre d'erreurs
 */
function nbErreurs()
{
    if (!isset($_REQUEST['erreurs'])) {
        return 0;
    } else {
        return count($_REQUEST['erreurs']);
    }
/**
 * Ajoute le libellé d'une information au tableau des informations
 *
 * @param String $msg
 *            Libellé de l'information
 *
 * @return null
 */
}

function ajouterInfo($msg)
{
    if (!isset($_REQUEST['infos'])) {
        $_REQUEST['infos'] = array();
    }
    $_REQUEST['infos'][] = $msg;
    addLogEvent(
        'Info ("' . $msg . '") de ' . $_SESSION['prenom'] . ' ' .
        $_SESSION['nom'] . ' (IP = ' . $_SERVER['REMOTE_ADDR']);
}

/**
 * Retoune le nombre de lignes du tableau des informations
 *
 * @return Integer le nombre d'informations
 */
function nbInfos()
{
    if (!isset($_REQUEST['infos'])) {
        return 0;
    } else {
        return count($_REQUEST['infos']);
    }
}

/**
 * Retourne le nombre de fiches de plus d'un an dans le tableau passé en paramètre
 *
 * @param
 *            array tableau de fiches de visiteurs
 * @return integer nb de fiches périmées
 */
function compterFichesPerimees(array $tableauDeFiches): int
{
    // Comptabiliser les fiches de plus de 1 an
    $compteur = 0;
    foreach ($tableauDeFiches as $uneFiche) {
        if (!estDateDepassee(dateAnglaisVersFrancais($uneFiche['date']))) {
            $compteur ++;
        }
    }
    return $compteur;
}

/**
 * Retourne le montant total des fiches dans le tableau passé en paramètre
 *
 * @param
 *            array tableau de fiches de visiteurs
 * @return float montant total
 */
function compterMontantTotal(array $tableauDeFiches): float
{
    // Comptabiliser les fiches de plus de 1 an
    $montant = 0;
    foreach ($tableauDeFiches as $uneFiche) {
        if (!estDateDepassee(dateAnglaisVersFrancais($uneFiche['date']))) {
            $montant += (float) $uneFiche['montant'];
        }
    }
    return $montant;
}

/**
 * Ajoute l'événement (avec TimeStamp) au fichier GSB2020.log
 *
 * @param string $event
 */
function addLogEvent($event)
{
    $time = date("D, d M Y H:i:s");
    $time = "[" . $time . "] ";
    $event = $time . $event . "\n";
    file_put_contents("GSB2020.log", $event, FILE_APPEND);
}

/**
 * Emettre un email vers l'adresse gsb2020/free.fr avec le log en cours.
 * A l'issue, vider le log.
 *
 * @return Boolean $ret True si mail partie, False sinon
 */
function envoyerLeLog()
{
    // ----------------------------------
    // Construction de l'entête
    // ----------------------------------
    // On choisi généralement de construire une frontière générée aléatoirement
    // comme suit. (le document pourra ainsi etre attache dans un autre mail
    // dans le cas d'un transfert par exemple)
    $boundary = "-----=" . md5(uniqid(rand()));

    // Ici, on construit un entête contenant les informations
    // minimales requises.
    // Version du format MIME utilisé
    $header = "MIME-Version: 1.0\r\n";
    // Type de contenu. Ici plusieurs parties de type different "multipart/mixed"
    // Avec un frontière définie par $boundary
    $header .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    $header .= "\r\n";

    // --------------------------------------------------
    // Construction du message proprement dit
    // --------------------------------------------------

    // Pour le cas, où le logiciel de mail du destinataire
    // n'est pas capable de lire le format MIME de cette version
    // Il est de bon ton de l'en informer
    // REM: Ce message n'apparaît pas pour les logiciels sachant lire ce format
    $msg = "Je vous informe que ceci est un message au format MIME 1.0 multipart/mixed.\r\n";

    // ---------------------------------
    // 1ère partie du message
    // Le texte
    // ---------------------------------
    // Chaque partie du message est séparée par une frontière
    $msg .= "--$boundary\r\n";

    // Et pour chaque partie on en indique le type
    $msg .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
    // Et comment il sera codé
    $msg .= "Content-Transfer-Encoding:8bit\r\n";
    // Il est indispensable d'introduire une ligne vide entre l'entête et le texte
    $msg .= "\r\n";
    // Enfin, on peut écrire le texte de la 1ère partie
    $msg .= "Ceci est un mail avec un fichier joint\r\n";
    $msg .= "\r\n";

    // ---------------------------------
    // 2nde partie du message
    // Le fichier
    // ---------------------------------
    // Tout d'abord lire le contenu du fichier
    $file = 'GSB2020.log';
    $fp = fopen($file, 'rb'); // b c'est pour les windowsiens
    $attachment = fread($fp, filesize($file));
    fclose($fp);

    // puis convertir le contenu du fichier en une chaîne de caractères
    // certe totalement illisible mais sans caractères exotiques
    // et avec des retours à la ligne tout les 76 caractères
    // pour être conforme au format RFC 2045
    $attachment = chunk_split(base64_encode($attachment));

    // Ne pas oublier que chaque partie du message est séparée par une frontière
    $msg .= "--$boundary\r\n";
    // Et pour chaque partie on en indique le type
    $msg .= "Content-Type: image/gif; name=\"$file\"\r\n";
    // Et comment il sera codé
    $msg .= "Content-Transfer-Encoding: base64\r\n";
    // Petit plus pour les fichiers joints
    // Il est possible de demander à ce que le fichier
    // soit si possible affiché dans le corps du mail
    $msg .= "Content-Disposition: inline; filename=\"$file\"\r\n";
    // Il est indispensable d'introduire une ligne vide entre l'entête et le texte
    $msg .= "\r\n";
    // C'est ici que l'on insère le code du fichier lu
    $msg .= $attachment . "\r\n";
    $msg .= "\r\n\r\n";

    // voilà, on indique la fin par une nouvelle frontière
    $msg .= "--$boundary--\r\n";

    $destinataire = 'gsb2020@free.fr';
    $expediteur = 'ne-pas-repondre@gsb2020.org';
    $reponse = 'gsb2020@free.fr';
    try {
        $ret = mail($destinataire, 'GSB2020 : transmission du log', $msg,
            "Reply-to: $reponse\r\nFrom: $expediteur\r\n" . $header);
        if ($ret == 1) {
            unlink('gsb2020.log');
            return true;
        }
    }
    catch (Exception $e) {
        addLogEvent("Erreur d'envoi du log à gsb2020@free.fr");
    }
    return false;
}

/**
 * Génère un fichier PDF contenant les informations de la fiche de frais
 *
 * @param object $pdo
 *            passage du pointeur pdo pour accéder à ses fonctions
 * @param array $lesFraisHorsForfait
 *            tableau associatif comportant les frais HF
 * @param array $lesFraisForfait
 *            tableau associatif comportant les frais forfaitisés
 * @param array $lesInfosFicheFrais
 *            informations concernant la fiche de frais
 * @return string
 */
function genererPDF($pdo, array $lesFraisHorsForfait, array $lesFraisForfait,
    array $lesInfosFicheFrais): string
{
    if (!defined('FPDF_FONTPATH')) {
        define('FPDF_FONTPATH', 'styles/fonts');
    }
    setlocale(LC_TIME, "fr_FR", "French"); // Pour affichage des dates en français
                                           // Préparation de l'action : mise en place des variables nécessaires à la page
    include_once './includes/fpdf.php';
    $id_visiteur = $lesFraisHorsForfait[0]['idvisiteur'];
    $donnees_visiteur = $pdo->getNomVisiteur($id_visiteur);
    $nom_visiteur = $donnees_visiteur['nom'];
    $prenom_visiteur = $donnees_visiteur['prenom'];
    $nr_fiche = $lesFraisHorsForfait[0]['mois'];
    $fichier_PDF = $nr_fiche . '-' . $nom_visiteur . '.pdf';
    $mois_annee = inverseMois($nr_fiche);
    $mois_lettre = moisEnLettre($nr_fiche);
    $vehicule = $pdo->getVehicule($id_visiteur);
    // TODO définir le mois /année en lettre : Juillet 2019
    $montant_valide = $lesInfosFicheFrais['montantValide'];
    $date_validation = $lesInfosFicheFrais['dateModif'];
    // Génération avec FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetMargins(25, 25);
    $pdf->Image('images/logo.jpg', 89, 20, 32, 20);
    // Titre
    $pdf->setDrawColor(130, 163, 206); // Bleu Clair GSB
    $pdf->SetFont('times', 'B', 16);
    $pdf->SetXY(30, 60);
    $pdf->setTextColor(130, 163, 206);
    $pdf->Cell(160, 6, "REMBOURSEMENT DE FRAIS ENGAGES", "B", 1, "C");
    $pdf->setTextColor(0, 0, 0);
    // En tête
    $pdf->setDrawColor(0, 0, 0);
    $pdf->SetFont('', '', 12);
    $pdf->SetXY(40, 74);
    $pdf->Cell(48, 8, "Visiteur", 0, 0, "L");
    $pdf->Cell(34, 8, $id_visiteur, 0, 0, "L");
    $pdf->Cell(60, 8, $prenom_visiteur . ' ' . strtoupper($nom_visiteur), 0, 0,
        "L");
    $pdf->SetXY(40, 82);
    $pdf->Cell(48, 8, "Mois", 0, 0, "L");
    $pdf->Cell(34, 8, utf8_decode(ucfirst($mois_lettre)), 0, 0, "L");
    // Frais forfaitaires
    $pdf->SetDrawColor(82, 127, 192); // Bleu Foncé GSB
    $pdf->SetFont('', 'I', 12);
    $pdf->SetXY(40, 98);
    $pdf->Cell(48, 8, "Frais Forfaitaires", 0, 0, "C");
    $pdf->Cell(34, 8, utf8_decode("Quantité"), 0, 0, "C");
    $pdf->Cell(34, 8, "Montant unitaire", 0, 0, "C");
    $pdf->Cell(26, 8, "Total", 0, 0, "C");
    // Extraction de la BDD
    $pdf->SetFont('', '', 12);
    $pdf->Rect(40, 98, 142, 32);
    $index = 0;
    foreach ($lesFraisForfait as $unFrais) {
        if ($unFrais['idfrais'] == "KM") {
            $unFrais['montant_unitaire'] = $vehicule['indemnite'];
        }
        $pdf->SetXY(40, ($index * 8) + 106);
        $pdf->Cell(48, 8, utf8_decode($unFrais['libelle']), 1, 0, "L");
        $pdf->Cell(34, 8, $unFrais['quantite'], 1, 0, "R");
        // Données non récupérées !
        $pdf->Cell(34, 8, $unFrais['montant_unitaire'], 1, 0, "R");
        $pdf->Cell(26, 8,
            number_format(
                (float) $unFrais['quantite'] * $unFrais['montant_unitaire'], 2,
                ".", ""), 1, 0, "R");
        $index ++;
    }
    // Ajout mention indemnité kilométrique
    $pdf->SetFont('', 'I', 8);
    $pdf->SetXY(40, 138);
    $pdf->Cell(48, 8,
        utf8_decode("Note : Vous avez déclaré un véhicule ") .
        strtoupper($vehicule['carburant']) . " de " .
        $vehicule['puissance_admin'] . " CV");
    // Autres Frais
    $pdf->SetFont('', 'I', 12);
    $pdf->SetDrawColor(82, 127, 192); // Bleu Foncé GSB
    $pdf->SetXY(40, 146);
    $pdf->Cell(160, 8, "Autres Frais", 0, 0, "C");
    $pdf->SetXY(40, 154);
    $pdf->Cell(48, 8, "Date", 0, 0, "C");
    $pdf->Cell(68, 8, utf8_decode("Libellé"), 0, 0, "C");
    $pdf->Cell(26, 8, "Montant", 0, 0, "C");
    $pdf->SetFont('', '', 12);
    // Extraction données BDD
    $index = 0;
    foreach ($lesFraisHorsForfait as $unFrais) {
        $pdf->SetXY(40, ($index * 8) + 162);
        $pdf->Cell(48, 8, $unFrais['date'], 1, 0, "L");
        $pdf->Cell(68, 8, utf8_decode($unFrais['libelle']), 1, 0, "L");
        $pdf->Cell(26, 8, $unFrais['montant'], 1, 0, "R");
        $index ++;
    }
    $pdf->Rect(40, 154, 142, $pdf->GetY() - 146);
    // affichage du total
    // Se repositionner en fonction de la dernière ligne écrite
    $pdf->SetXY($pdf->GetX() - 60, $pdf->GetY() + 16);
    $pdf->Cell(34, 8, "TOTAL " . $mois_annee, 1, 0, "L");
    $pdf->Cell(26, 8, $montant_valide, 1, 0, "R");
    // Tracer le cadre extérieur
    $pdf->Rect(30, 60, 160, ($pdf->GetY() + 16) - 60);
    // Attache de signature
    // Se repositionner en dynamique
    $pdf->SetXY($pdf->GetX() - 60, $pdf->GetY() + 32);
    $date1 = date('Y-m-d');
    $date = strftime("%d/%m/%Y", strtotime($date1));
    $pdf->Cell(60, 8, utf8_decode("Fait à Paris, le " . $date), 0, 1, "L");
    $pdf->SetXY(122, $pdf->GetY());
    $pdf->Cell(60, 8, "Vu l'agent comptable", 0, 1, "L");
    $pdf->Image('images/signature.jpg', $pdf->GetX() + 100, $pdf->getY(), 32, 20);

    $pdf->Output('F', 'PDF/' . $fichier_PDF);
    return "PDF/" . $fichier_PDF;
}
