<?php
/**
 * Vue Liste des visiteurs.
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

<?php
// TODO passer le script dans un fichier JS
?>
<script>
        function afficheListeMois() {

            mesVisiteurs = document.getElementById("lstVisiteurs");
            mesMois = document.getElementById("lstMois");
            monChoix = mesVisiteurs.options[mesVisiteurs.selectedIndex].value;
            // permet de rendre visible le select "LstMois"
            mesMois.style.display = "block";
            mesMois.selectedIndex = 0;
            marqueurchoix = false;
            //cacher les éléments qui ne correspondent pas à l'ID sélectionné.
            for (let i = 0; i<document.getElementById("lstMois").options.length;i++) {
                if (mesMois.options[i].label!=monChoix) {
                    mesMois.options[i].style.display="none";
                } else {
                    mesMois.options[i].style.display="block";
                    marqueurchoix = true;
                }
            }
            mesMois.options[0].style.display = "block";
        }
        function declencheFiche() {
            mesVisiteurs = document.getElementById("lstVisiteurs");
            mesMois = document.getElementById("lstMois");
            monChoix = mesVisiteurs.options[mesVisiteurs.selectedIndex].value;
            maFiche = mesMois.options[mesMois.selectedIndex].value;
            document.forms["formulaire"].submit();
            }

        window.addEventListener("load",function() {
            window.document.querySelector("#lstVisiteurs").addEventListener("change",afficheListeMois);
            window.document.querySelector("#lstMois").addEventListener("change",declencheFiche);
            document.getElementById("lstMois").style.display = "none";
            });

</script>
<div class="row">
	<div class="col-md-4">
		<h5>Sélectionner un visiteur :</h5>
	</div>
	<div class="col-md-4">
		<form id="formulaire"
			action="index.php?uc=valideFrais&action=voirListeFrais" method="post"
			>
			<div class="form-group">
				<select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
					<option label="entete" value="0" disabled selected>...</option>
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $unId = $unVisiteur['id'];
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                        ?>
                         <option value="<?php

echo $unId?>">
                         <?php

echo $nom . ' ' . $prenom?>
                         </option>
                    <?php

}
                    ?>
                </select>

			<select id="lstMois" name="lstMois" class="form-control">
				<option label="entete" value="0" disabled selected>...</option>
                    <?php
                    foreach ($TouslesMois as $unMois) {
                        $unID = $unMois['ID'];
                        $leMois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        ?>
                        <option label="<?php

echo $unID?>"
                                value="<?php

echo $leMois?>">
                        <?php

echo $numMois . '/' . $numAnnee?>
                        </option>
                    <?php

}
                    ?>
                </select>

		</div>
	</form>
</div>