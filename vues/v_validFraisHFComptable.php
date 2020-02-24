<?php
/**
 * Vue Formulaire liste de frais hors forfait
 *
 * PHP Version 7
 *
 * \package   GSB
 * \author    Réseau CERTA <contact\reseaucerta.org>
 * \author    Louis-Marin Mathorel <gsb2020\free.fr>
 * \copyright 2017 Réseau CERTA
 * \license   Réseau CERTA
 * \version   GIT: <0>
 * \link      http://gsb2020.org Contexte « Laboratoire GSB »
 */
?>

<?php $lignes_a_justifier = count($lesFraisHorsForfait);?>
<script type="text/javascript" src="/vues/js/js_report_refus.js"></script>
<hr>
<div class="row">
	<div class="panel panel-warning">
		<div class="panel-heading">Descriptif des éléments hors forfait - <?php echo count($lesFraisHorsForfait)?> entrée(s) listée(s).</div>
		<table class="table table-bordered table-responsive">
			<thead>
				<tr>
					<th class="date">Date</th>
					<th class="libelle">Libellé</th>
					<th class="montant">Montant</th>
					<th class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $date = dateFrancaisVersAnglais($unFraisHorsForfait['date']);
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id'];
                ?>
                <tr>
					<form method="post"
						action="index.php?uc=valideFrais&action=corrigerFraisHF"
						name="form<?php echo $id?>">


					<td><input type="date" id="HFdate<?php echo $id?>" name="HFdate"
						value="<?php echo $date ?>" class="form-control" required></td>
					<td><input type="text" id="HFlibelle<?php echo $id?>"
						name="HFlibelle" value="<?php echo htmlspecialchars($libelle) ?>"
						class="form-control" required></td>
					<td><input type="text" id="HFmontant<?php echo $id?>"
						name="HFmontant" value="<?php echo $montant ?>"
						class="form-control" pattern="[0-9]+(\.[0-9]+)?" required></td>
					<td><input id="idNom<?php echo $id?>" name="idNom" type="hidden"
						value="<?php echo $id_visiteur?>"> <input
						id="mois<?php echo $id?>" name="mois" type="hidden"
						value="<?php echo $mois_fiche?>"> <input
						id="idFiche<?php echo $id?>" name="idFiche" type="hidden"
						value="<?php echo $id?>">
						<button class="btn btn-success" type="submit"
							id="Corriger<?php echo $id?>">Corriger</button>
						<button class="btn btn-warning" type="button" name="Reporter"
							id="<?php echo $id?>">Reporter</button>
						<button class="btn" type="button" name="Refuser"
							id="<?php echo $id?>"
							<?php if (substr($libelle,0,6)=='REFUSE'){echo ' disabled';$lignes_a_justifier = $lignes_a_justifier - 1;} ?>>Refuser</button>
						<button class="btn btn-danger" type="reset">Réinitialiser</button>
					</td>
					</form>
            <?php } ?>
            </tr>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<form method="post"
			action="index.php?uc=valideFrais&action=validerFiche"
			name="form_validation">
			<div class="form-group">
				<label for="txtNbJustificatifs">Nombre de justificatifs (sauf lignes
					refusées) :</label> <input type="text" id="txtNbJustificatifs"
					name="nbJustificatifs" size="2" maxlength="5"
					value="<?php echo $nbJustificatifs ?>" class="form-control"
					pattern="[<?php echo $lignes_a_justifier?>]" required> <input
					id="idNom" name="idNom" type="hidden"
					value="<?php echo $id_visiteur?>"> <input id="mois" name="mois"
					type="hidden" value="<?php echo $mois_fiche?>">
			</div>
			<button class="btn btn-success" type="submit" id="Validation">Valider
				la Fiche</button>
		</form>
	</div>
</div>