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
                    $quantite = $unFrais['quantite']; ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="8" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control" required
                               onChange="document.getElementById('corrigerForfait').disabled=false;"
                        >                                               
                    </div>
                    <?php
                }
                ?>
                <input id="idNom" name="idNom" type="hidden" value="<?php echo $id_visiteur?>">
                <input id="mois" name="mois" type="hidden" value="<?php echo $mois_fiche?>">
                <button class="btn btn-success" type="submit" id="corrigerForfait" disabled=true>Corriger</button>
                <button class="btn btn-danger" type="reset" onclick="document.getElementById('corrigerForfait').disabled=true;">Réinitialiser</button>
            </fieldset>
        </form>
    </div>
</div>

