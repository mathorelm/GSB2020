<?php
/**
 * Vue supervision des mises en paiement du comptable
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    Louis-Marin Mathorel <gsb2020@free.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://gsb2020.org Contexte « Laboratoire GSB »
 */
?>

<div class="row">
	<h2>Supervision des mises en paiement des fiches</h2>
</div>
<div class="row">
	<div class="col-sm-4 text-center">
		Nb de fiches validées : <?php echo count($lesfichesVA);?>
		<br/>
		Montant total : <?php echo $montantVA;?> euros.
		<hr/>
		<?php foreach($lesfichesVA as $uneFiche) {
		          ?>
			<div class="card" style='border:solid'>
				<h5 class="card-header" style='background:gray;color:white'>
					<strong>FICHE</strong> <?php echo $uneFiche['mois'].' - '.$uneFiche['nom'].' '.$uneFiche['prenom'];?>
				</h5>
				<div class="card-body text-left">
					<h6 class="card-title">Statut : <?php echo $uneFiche['statut']. ' ('.dateAnglaisVersFrancais($uneFiche['date']).')';?></h6>
					<p class="card-text">Montant : <?php echo $uneFiche['montant'].' euros ';?></p>
				</div>
			</div>
			<hr/>
		<?php };?>
	</div>
	<div class="col-sm-4 text-center">
		Nb de fiches mises en paiement : <?php echo count($lesfichesMP);?>
		<br/>
		Montant total : <?php echo $montantMP;?> euros.
		<hr/>
		<?php foreach($lesfichesMP as $uneFiche) {
		          ?>
			<div class="card" style='border:solid'>
				<h5 class="card-header" style='background:gray;color:white'>
					<strong>FICHE</strong> <?php echo $uneFiche['mois'].' - '.$uneFiche['nom'].' '.$uneFiche['prenom'];?>
				</h5>
				<div class="card-body text-left">
					<h6 class="card-title">Statut : <?php echo $uneFiche['statut']. ' ('.dateAnglaisVersFrancais($uneFiche['date']).')';?></h6>
					<p class="card-text">Montant : <?php echo $uneFiche['montant'].' euros ';?></p>
				</div>
			</div>
			<hr/>
		<?php };?>
	</div>
	<div class="col-sm-4 text-center">
		Nb de fiches remboursées (1 an) : <?php echo $ficheaffichee;?>
		<br/>
		Montant total : <?php echo $montantRB;?> euros.
		<hr/>
		<?php foreach($lesfichesRB as $uneFiche) {
		    //TODO : limiter l'affichage à un an
		    if (!estDateDepassee(dateAnglaisVersFrancais($uneFiche['date']))) {
		          ?>
			<div class="card" style='border:solid'>
				<h5 class="card-header" style='background:gray;color:white'>
					<strong>FICHE</strong> <?php echo $uneFiche['mois'].' - '.$uneFiche['nom'].' '.$uneFiche['prenom'];?>
				</h5>
				<div class="card-body text-left">
					<h6 class="card-title">Statut : <?php echo $uneFiche['statut']. ' ('.dateAnglaisVersFrancais($uneFiche['date']).')';?></h6>
					<p class="card-text">Montant : <?php echo $uneFiche['montant'].' euros ';?></p>
				</div>
			</div>
			<hr/>
		<?php }; };?>
	</div>
</div>
