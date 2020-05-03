<?php
/**
 *\brief  Vue Formulaire liste de frais hors forfait
 *
 * \details PHP Version 7
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

$lignesAjustifier = count($lesFraisHorsForfait);
?>
<script src="/vues/js/js_report_refus.js"></script>
<hr>
<div class="row">
    <div class="panel panel-warning">
        <div class="panel-heading">Descriptif des éléments hors forfait - <?php

        echo count($lesFraisHorsForfait)?> entrée(s) listée(s).</div>
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
                        name="form<?php

                echo $id?>">

                    <td><input type="date" id="dateHFdate<?php

                echo $id?>" name="dateHFdate"
                        value="<?php

                echo $date?>" class="form-control" required>
                    </td>

                    <td><input type="text" id="txtHFlibelle<?php

                echo $id?>"
                        name="txtHFlibelle" value="<?php

                echo htmlspecialchars($libelle)?>"
                        class="form-control" required>
                    </td>

                    <td><input type="text" id="txtHFmontant<?php

                echo $id?>"
                        name="txtHFmontant" value="<?php

                echo $montant?>"
                        class="form-control" pattern="[0-9]+(\.[0-9]+)?" required>
                    </td>

                    <td><input id="hdIdNom<?php

                echo $id?>"
                    	name="hdIdNom" type="hidden" value="<?php

                echo $idVisiteur?>">
                    	<input id="hdMois<?php

                echo $id?>"
                    	name="hdMois" type="hidden" value="<?php

                echo $moisFiche?>">
                    	<input id="hdIdFiche<?php

                echo $id?>"
                    	name="hdIdFiche" type="hidden" value="<?php

                echo $id?>">
                        <button class="btn btn-success" type="submit"
                            id="cmdCorriger<?php

                echo $id?>">Corriger</button>
                        <button class="btn btn-warning" type="button" name="cmdReporter"
                            id="<?php

                echo $id?>">Reporter</button>
                        <button class="btn" type="button" name="cmdRefuser"
                        	id="<?php

                echo $id?>"
                            <?php
                if (substr($libelle, 0, 6) == 'REFUSE') {
                    echo ' disabled';
                    $lignesAjustifier = $lignesAjustifier - 1;
                }
                ?>>Refuser</button>
                        <button class="btn btn-danger" type="reset">Réinitialiser</button>
                    </td>
                    </form>
            <?php
            }
            ?>
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
                    name="txtNbJustificatifs" size="2" maxlength="5"
                    value="<?php

                    echo $nbJustificatifs?>" class="form-control"
                    pattern="[<?php

                    echo $lignesAjustifier?>]" required>
                    <input id="idNom" name="hdIdNom" type="hidden"
                    value="<?php

                    echo $idVisiteur?>">
                    <input id="mois" name="hdMois" type="hidden"
                    value="<?php

                    echo $moisFiche?>">
            </div>
            <button class="btn btn-success" type="submit" id="cmdValidation">Valider
                la Fiche</button>
        </form>
    </div>
</div>
