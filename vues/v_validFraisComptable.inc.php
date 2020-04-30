<?php
/**
 * Vue Formulaire modification fiche frais du comptable
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

<script src="/vues/js/js_dynamique_valideFrais.js"></script>
<div class="row">
    <h2>Valider la fiche de frais <?php

echo $moisFiche . ' - ' . $nomPrenom['nom'] . ' ' . $nomPrenom['prenom']?>
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-xs-4">
        <form method="post"
            action="index.php?uc=valideFrais&action=corrigerFraisForfait"
            >
            <fieldset>
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite'];
                    ?>
                    <div class="form-group">
                    	<label for="idFrais<?php

echo $idFrais?>">
                    	<?php

echo $libelle;
                    if ($idFrais == "KM") {
                        $vehicule = $pdo->getVehicule($idVisiteur);
                        echo "<br/>";
                        echo " (" . strtoupper($vehicule['carburant']) . " " .
                            $vehicule['puissance_admin'] . " CV)";
                    }
                    ?>
                        </label>
                    <input type="text" id="txtIdFrais<?php

echo $idFrais?>" name="txtlesFrais[<?php

echo $idFrais?>]" size="8" maxlength="5"
                        value="<?php

echo $quantite?>" class="form-control" required>
                	</div>
                <?php

}
                ?>
                <input id="hdIdNom" name="hdIdNom" type="hidden" value="<?php

echo $idVisiteur?>">
                <input id="hdMois" name="hdMois" type="hidden" value="<?php

echo $moisFiche?>">
                <button class="btn btn-success" type="submit" id="cmdCorrigerForfait" disabled>Corriger</button>
                <button id="brReinit" class="btn btn-danger" type="reset">Réinitialiser</button>
            </fieldset>
        </form>
    </div>
</div>

