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
<?php $lignes_a_justifier = count($lesFraisHorsForfait);?>
<script>
	function reporterLigne(iD) {
		libelleTest = "REPORT : " + document.getElementById("HFlibelle"+iD).value;
		if (libelleTest.length > 100) {
			document.getElementById("HFlibelle"+iD).value = libelleTest.substr(0,100);
		} else {
			document.getElementById("HFlibelle"+iD).value = libelleTest;
		}		
		document.forms["form"+iD].submit();
	};
	function refuserLigne(iD) {		
		libelleTest = "REFUSE : " + document.getElementById("HFlibelle"+iD).value;
		if (libelleTest.length > 100) {
			document.getElementById("HFlibelle"+iD).value = libelleTest.substr(0,100);
		} else {
			document.getElementById("HFlibelle"+iD).value = libelleTest;
		}		
		document.forms["form"+iD].submit();
	};	
</script>
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
                <form method="post" action="index.php?uc=valideFrais&action=corrigerFraisHF" name="form<?php echo $id?>">                	
                <tr>
                    <td>
                    	<input type="date" id="HFdate<?php echo $id?>" name="HFdate" value="<?php echo $date ?>"
                    			class="form-control" required>
                    </td>
                    <td>
                    	<input type="text" id="HFlibelle<?php echo $id?>" name="HFlibelle" value="<?php echo $libelle ?>"
                    			class="form-control" required>
                    </td>
                    <td> 
                    	<input type="text" id="HFmontant<?php echo $id?>" name="HFmontant" value="<?php echo $montant ?>"
                    			class="form-control" pattern="[0-9]+(\.[0-9]+)?" required>
                    </td>
                    <td> 
                    	 <input id="idNom<?php echo $id?>" name="idNom" type="hidden" value="<?php echo $id_visiteur?>">
                         <input id="mois<?php echo $id?>" name="mois" type="hidden" value="<?php echo $mois_fiche?>"> 
                    	 <input id="idFiche<?php echo $id?>" name="idFiche" type="hidden" value="<?php echo $id?>">
                    	 <button class="btn btn-success" type="submit" id="Corriger<?php echo $id?>">Corriger</button>
            			 <button class="btn btn-warning" type="button" id="Reporter<?php echo $id?>" onclick="reporterLigne(<?php echo $id?>)">Reporter</button>           			
          	         	 <button class="btn" type="button" id="Refuser<?php echo $id?>" onclick="refuserLigne(<?php echo $id?>)" <?php if (substr($libelle,0,6)=='REFUSE') 
          	         	 {echo ' disabled';$lignes_a_justifier = $lignes_a_justifier - 1;} ?>>Refuser</button>            			 
            			 <button class="btn btn-danger" type="reset">Réinitialiser</button>
                    </td>
                    </form>
            <?php } ?>          		
            </tbody>  
        </table>
    </div>
</div>
<div class="row">    
       <div class="col-xs-4">
       <form method="post" action="index.php?uc=valideFrais&action=validerFiche" name="form_validation">                      
            <div class = "form-group">
            	<label for="txtNbJustificatifs">Nombre de justificatifs (sauf lignes refusées) :</label> 
            	<input type="text" id="txtNbJustificatifs" name="nbJustificatifs"
            			size = "2" maxlength="5" value="<?php echo $nbJustificatifs ?>"
            			class="form-control" pattern="[<?php echo $lignes_a_justifier?>]" required>
            	<input id="idNom" name="idNom" type="hidden" value="<?php echo $id_visiteur?>">
                <input id="mois" name="mois" type="hidden" value="<?php echo $mois_fiche?>">             			
            </div>                      
            <button class="btn btn-success" type="submit" id="Validation">Valider la Fiche</button>
        </form>
     </div>
</div>