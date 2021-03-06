<?php
/**
 * \brief Vue État de Frais
 *
 * PHP Version 7
 *
 * \package   GSB
 * \author    Réseau CERTA <contact@reseaucerta.org>
 * \author    José GIL <jgil@ac-nice.fr>
 * \copyright 2017 Réseau CERTA
 * \license   Réseau CERTA
 * \version   GIT: <0>
 * \link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>

<hr>
<div class="panel panel-primary">
	<div class="panel-heading">Fiche de frais du mois
        <?php

        echo $numMois . '-' . $numAnnee?> : </div>
	<div class="panel-body">
		<strong><em>Etat :</em></strong> <?php

echo $libEtat?>
        depuis le <?php

        echo $dateModif?> <br> <strong><em>Montant validé :</em></strong> <?php

        echo $montantValide?>
        <?php

        if ($lien_pdf != "") {
            ?>
            <div class="row text-right">
            <i>Téléchargez votre PDF :</i> <a
				href="<?php

            echo $lien_pdf?>"> <img class="pdf" src="./images/pdf.jpg" alt="Cliquez ici">
			</a>
		</div>
        <?php
        }
        ?>
    </div>
</div>
<div class="panel panel-info">
	<div class="panel-heading">Eléments forfaitisés</div>
	<table class="table table-bordered table-responsive">
		<tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $libelle = $unFraisForfait['libelle'];
                ?>
                <th> <?php

                echo $libelle?></th>
                <?php
            }
            ?>
        </tr>
		<tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite'];
                ?>
                <td class="qteForfait"><?php

                echo $quantite?> </td>
                <?php
            }
            ?>
        </tr>
	</table>
</div>
<div class="panel panel-info">
	<div class="panel-heading">Descriptif des éléments hors forfait -
        <?php

        echo $nbJustificatifs?> justificatifs reçus</div>
	<table class="table table-bordered table-responsive">
		<tr>
			<th class="date">Date</th>
			<th class="libelle">Libellé</th>
			<th class='montant'>Montant</th>
		</tr>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = $unFraisHorsForfait['libelle'];
            $montant = $unFraisHorsForfait['montant'];
            ?>
            <tr>
			<td><?php

            echo $date?></td>
			<td><?php

            echo $libelle?></td>
			<td><?php

            echo $montant?></td>
		</tr>
            <?php
        }
        ?>
    </table>
</div>