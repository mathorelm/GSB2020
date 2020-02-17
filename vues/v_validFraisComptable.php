<?php
/**
 * Vue Formulaire modification fiche frais du comptable
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
 * @link      http://gsb2020.free.fr Contexte « Laboratoire GSB »
 */
?>
<script type="text/javascript"
	src="/vues/js/js_dynamique_valideFrais.js"></script>
<div class="row">
	<h2>Valider la fiche de frais
        <?php echo $mois_fiche . ' - ' . $nom_prenom['nom'] . ' ' . $nom_prenom['prenom'] ?>
    </h2>
	<h3>Eléments forfaitisés</h3>
	<div class="col-xs-4">
		<form method="post"
			action="index.php?uc=valideFrais&action=corrigerFraisForfait"
			role="form">
			<fieldset>
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite'];
                    ?>
                    <div class="form-group">
					<label for="idFrais<?php echo $idFrais?>"><?php echo $libelle ?></label>
					<input type="text" id="idFrais<?php echo $idFrais?>"
						name="lesFrais[<?php echo $idFrais ?>]" size="8" maxlength="5"
						value="<?php echo $quantite ?>" class="form-control" required>
				</div>
                    <?php
                }
                ?>
                <input id="idNom" name="idNom" type="hidden"
					value="<?php echo $id_visiteur?>"> <input id="mois" name="mois"
					type="hidden" value="<?php echo $mois_fiche?>">
				<button class="btn btn-success" type="submit" id="corrigerForfait"
					disabled>Corriger</button>
				<button id="reinit" class="btn btn-danger" type="reset">Réinitialiser</button>
			</fieldset>
		</form>
	</div>
</div>

