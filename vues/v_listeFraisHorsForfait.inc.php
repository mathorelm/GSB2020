<?php
/**
 * Vue Liste des frais hors forfait
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
?>

<hr>
<div class="row">
	<div class="panel panel-info">
		<div class="panel-heading">Descriptif des éléments hors forfait</div>
		<table class="table table-bordered table-responsive">
			<thead>
				<tr>
					<th class="date">Date</th>
					<th class="libelle">Libellé</th>
					<th class="montant">Montant</th>
					<th class="action">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id'];
                ?>
                <tr>
					<td> <?php

echo $date?></td>
					<td> <?php

echo $libelle?></td>
					<td><?php

echo $montant?></td>
					<td><a
						href="index.php?uc=gererFrais&action=supprimerFrais&idFrais=<?php

echo $id?>"
						onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">Supprimer
							ce frais</a></td>
				</tr>
                <?php
            }
            ?>
            </tbody>
		</table>
	</div>
</div>
<div class="row">
	<h3>Nouvel élément hors forfait</h3>
	<div class="col-md-4">
		<form action="index.php?uc=gererFrais&action=validerCreationFrais"
			method="post" role="form">
			<div class="form-group">
				<label for="txtDate">Date (jj/mm/aaaa): </label> <input type="date"
					id="txtDate" name="dateFrais" class="form-control" id="text"
					required>
			</div>
			<div class="form-group">
				<label for="txtLibelle">Libellé</label> <input type="text"
					id="txtLibelle" name="libelle" class="form-control" id="text"
					required>
			</div>
			<div class="form-group">
				<label for="txtMontant">Montant : </label>
				<div class="input-group">
					<span class="input-group-addon">€</span> <input type="text"
						id="txtMontant" name="montant" class="form-control" value=""
						required>
				</div>
			</div>
			<button class="btn btn-success" type="submit">Ajouter</button>
			<button class="btn btn-danger" type="reset">Effacer</button>
		</form>
	</div>
</div>