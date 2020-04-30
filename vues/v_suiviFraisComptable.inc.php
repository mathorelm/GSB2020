<?php
/**
 * Vue supervision des mises en paiement du comptable
 *
 * PHP Version 7
 *
 * \package   GSB
 * \author    Réseau CERTA <contact@reseaucerta.org>
 * \author    Louis-Marin Mathorel <gsb2020@free.fr>
 * \copyright 2017 Réseau CERTA
 * \license   Réseau CERTA
 * \version   GIT: <0>
 * \link      http://gsb2020.org Contexte « Laboratoire GSB »
 */
?>

<?php
$jour_actuel = (int) date('j');
$mois_actuel = (int) date('n');
$mois_precedent = (int) date('n', strtotime('-1 month'));
?>
<script src="/vues/js/js_suiviFraisComptable.js"></script>
<div class="row">
	<h2>Supervision des mises en paiement des fiches</h2>
</div>
<div class="row">
	<H3>Balance actuelle du compte de paiement :
	<span <?php

if ($balance < 0) {
    echo 'style="background:red;color:white"';
}
;
?> >
	<?php

echo $balance;
?> </span> euros.</H3>
	<H3>Balance estimée au 30 du mois          :
	<span <?php

if (($balance - $montantMP) < 0) {
    echo 'style="background:red;color:white"';
}
;
?> >
	<?php
echo ($balance - $montantMP);
?> </span> euros.</H3>
	<p> Fiche en retard en <a style="background:red;color:white">rouge</a>. Le classement va des fiches les plus anciennes aux plus jeunes.</p>
</div>
<hr/>
<div class="row">
	<div class="col-sm-4 text-center">
		fiches validées : <?php

echo count($lesfichesVA);
?>
		<br/>
		Montant total : <?php

echo $montantVA;
?> euros.
		<hr/>
		<?php

foreach ($lesfichesVA as $uneFiche) {
    ?>
			<div class="card">
				<?php

    $mois_fiche = (int) substr($uneFiche['mois'], 4, 2);
    if (($mois_fiche != $mois_precedent) && ($jour_actuel <= $limiteVAVersMP)) {
        $couleur = "red";
    } else {
        $couleur = "green";
    }
    ?>
				<h5 class="card-header" style='background:<?php

    echo $couleur?>;color:white'>
					<strong>FICHE</strong> <?php
    echo $uneFiche['mois'] . ' - ' . $uneFiche['nom'] . ' ' . $uneFiche['prenom'];
    ?>
				</h5>
				<div class="card-body text-left">
					<h6 class="card-title">Statut : <?php
    echo $uneFiche['statut'] . ' (' . dateAnglaisVersFrancais($uneFiche['date']) .
        ')';
    ?></h6>
					<p class="card-text">Montant : <?php
    echo $uneFiche['montant'] . ' euros ';
    ?></p>
				</div>
				<div class="card-footer text-right">
				<form action="index.php?uc=suivreFrais&action=VAversCL" method="post">
					<input	id="visiteur2<?php
    echo $uneFiche['id'] . $uneFiche['mois']?>
                        " name="hdVisiteur" type="hidden"	value="
                        <?php

    echo $uneFiche['id'];
    ?>">
					<input	id="mois2<?php
    echo $uneFiche['id'] . $uneFiche['mois']?>" name="hdMois" type="hidden"	value="
                        <?php

    echo $uneFiche['mois'];
    ?>">
					<button class="btn btn-danger btn-sm" type="submit" data-toggle="tooltip" data-placement="top" title="invalider la fiche">
					<?php

    echo chr(38) . '#60;' . chr(38) . '#60;' . chr(38) . '#60;'?></button>
				</form>
				<form action="index.php?uc=suivreFrais&action=VAversMP" method="post">
					<input	id="visiteur<?php

    echo $uneFiche['id'] . $uneFiche['mois']?>
					" name="hdVisiteur" type="hidden"	value="
					<?php

    echo $uneFiche['id'];
    ?>">
					<input	id="mois<?php

    echo $uneFiche['id'] . $uneFiche['mois']?>" name="hdMois" type="hidden"	value="
					<?php

    echo $uneFiche['mois'];
    ?>">
					<button class="btn btn-success btn-sm" type="submit" data-toggle="tooltip" data-placement="top" title="forcer mise en paiement">>>></button>
				</form>
				</div>
			</div>
			<hr/>
		<?php
}
;
?>
	</div>
	<div class="col-sm-4 text-center">
		fiches mises en paiement : <?php

echo count($lesfichesMP);
?>
		<br/>
		Montant total : <?php

echo $montantMP;
?> euros.
		<hr/>
		<?php
foreach ($lesfichesMP as $uneFiche) {
    ?>
			<div class="card">
				<?php
    $mois_fiche = (int) substr($uneFiche['mois'], 4, 2);
    if (($mois_fiche != $mois_precedent) && ($jour_actuel <= $limiteMPVersRB)) {
        $couleur = "red";
    } else {
        $couleur = "green";
    }
    ?>
				<h5 class="card-header" style='background:<?php

    echo $couleur?>;color:white'>
					<strong>FICHE</strong> <?php

    echo $uneFiche['mois'] . ' - ' . $uneFiche['nom'] . ' ' . $uneFiche['prenom'];
    ?>
				</h5>
				<div class="card-body text-left">
					<h6 class="card-title">Statut : <?php
    echo $uneFiche['statut'] . ' (' . dateAnglaisVersFrancais($uneFiche['date']) .
        ')';
    ?>
                    </h6>
					<p class="card-text">Montant : <?php

    echo $uneFiche['montant'] . ' euros ';
    ?></p>
					</div>
				<div class="card-footer">
				<form action="index.php?uc=suivreFrais&action=MPversVA" method="post">
					<input	id="visiteur2<?php
    echo $uneFiche['id'] . $uneFiche['mois']?>" name="hdVisiteur" type="hidden"
                        value="<?php

    echo $uneFiche['id'];
    ?>">
					<input	id="mois2<?php

    echo $uneFiche['id'] . $uneFiche['mois']?>" name="hdMois" type="hidden"
						value="<?php

    echo $uneFiche['mois'];
    ?>">
					<button class="btn btn-danger btn-sm" type="submit" data-toggle="tooltip" data-placement="top" title="suspendre la mise en paiement (retour VA)">
					<?php

    echo chr(38) . '#60;' . chr(38) . '#60;' . chr(38) . '#60;'?></button>
				</form>
				<form action="index.php?uc=suivreFrais&action=MPversRB" method="post">
					<input	id="visiteur3<?php

    echo $uneFiche['id'] . $uneFiche['mois']?>" name="hdVisiteur" type="hidden"
					 		value="<?php

    echo $uneFiche['id'];
    ?>">
					<input	id="mois3<?php

    echo $uneFiche['id'] . $uneFiche['mois']?>" name="hdMois" type="hidden"
					     	value="<?php

    echo $uneFiche['mois'];
    ?>">
					<button class="btn btn-success btn-sm" type="submit" data-toggle="tooltip" data-placement="top" title="placer en archive (remboursée)">>>></button>
				</form>
				</div>
			</div>
			<hr/>
		<?php
}
;
?>
	</div>
	<div class="col-sm-4 text-center">
		fiches remboursées (1 an) : <?php

echo $ficheaffichee;
?>
		<br/>
		Montant total : <?php

echo $montantRB;
?> euros.
		<hr/>
		<?php
foreach ($lesfichesRB as $uneFiche) {
    if (!estDateDepassee(dateAnglaisVersFrancais($uneFiche['date']))) {
        ?>
			<div class="card">
				<h5 class="card-header" style='background:gray;color:white'>
					<strong>FICHE</strong> <?php

        echo $uneFiche['mois'] . ' - ' . $uneFiche['nom'] . ' ' .
            $uneFiche['prenom'];
        ?>
				</h5>
				<div class="card-body text-left">
					<h6 class="card-title">Statut : <?php

        echo $uneFiche['statut'] . ' (' .
            dateAnglaisVersFrancais($uneFiche['date']) . ')';
        ?></h6>
					<p class="card-text">Montant : <?php

        echo $uneFiche['montant'] . ' euros ';
        ?></p>
				</div>
			</div>
			<hr/>
		<?php
    }
    ;
}
;
?>
	</div>
</div>

