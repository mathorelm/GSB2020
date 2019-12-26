<?php
/**
 * Vue Liste des frais hors forfait
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>
<hr>
<div class="row">
    <div class="panel panel-warning">
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
                $date = dateFrancaisVersAnglais($unFraisHorsForfait['date']);
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id']; ?>           
                <form method="post"
						action="index.php?uc=valideFrais&action=corrigerFraisHF"
						role="form">                	
                <tr>
                    <td> <input type="date" id="HFdate" name="HFdate" value="<?php echo $date ?>"
                    			class="form-control" id="text" required>
                    </td>
                    <td> <input type="text" id="HFlibelle" name="HFlibelle" value="<?php echo $libelle ?>"
                    			class="form-control" id="text" required>
                    </td>
                    <td> <input type="text" ld="HFmontant" name="HFmontant" value="<?php echo $montant ?>"
                    			class="form-control" id="text" required>
                    </td>
                    <td><input id="idNom" name="idNom" type="hidden" value="<?php echo $id_visiteur?>">
                        <input id="mois" name="mois" type="hidden" value="<?php echo $mois_fiche?>"> 
                    	<input id="idFiche" name="idFiche" type="hidden" value="<?php echo $id?>">
                    	<button class="btn btn-success" type="submit>">Corriger</button>
            			 <button class="btn btn-danger" type="reset">Réinitialiser</button>
                    </td>
                </tr>
                </form>
                <?php
            }
            ?>
            </tbody>  
        </table>
    </div>
</div>
<div class="row">    
       <div class="col-xs-4>"
       <form method="post" 
        	  action="index.php?uc=valideFrais&action=modifierNbJustificatifs" 
              role="form">                      
            <div class = "form-group">
            	<label for="txtNbJustificatifs">Nombre de justificatifs :</label> 
            	<input type="text" id="txtNbJustificatifs" name="nbJustificatifs"
            			size = "2" maxlength="5" value="<?php echo $nbJustificatifs ?>"
            			class="form-control" id="text" required>            			
            </div>                       
            <button class="btn btn-success" type="submit">Valider</button>
            <button class="btn btn-danger" type="reset">Réinitialiser</button>          
        </form>
     </div>
</div>