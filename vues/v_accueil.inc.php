<?php
/**
 * Vue Accueil
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

<div id="accueil">
	<h2>
		Gestion des frais<small> -
            <?php
            echo ucfirst($_SESSION['metier']) . ' : ' . $_SESSION['prenom'] . ' ' .
                $_SESSION['nom']?></small>
	</h2>
</div>
<div class="row">
	<div class="col-md-12">
        <?php

if ($_SESSION['metier'] == "visiteur") {
            ?> <div
			class="panel panel-primary"><?php
        }
        ?>
        <?php

if ($_SESSION['metier'] == "comptable") {
            ?> <div
				class="panel panel-warning"><?php
        }
        ?>
            <div class="panel-heading">
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-bookmark"></span> Navigation
					</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-12 col-md-12">
                        <?php

if ($_SESSION['metier'] == "visiteur") {
                            ?>
                            <a
								href="index.php?uc=gererFrais&action=saisirFrais"
								class="btn btn-success btn-lg" role="button"> <span
								class="glyphicon glyphicon-pencil"></span> <br>Renseigner la
								fiche de frais
							</a> <a href="index.php?uc=etatFrais&action=selectionnerMois"
								class="btn btn-primary btn-lg" role="button"> <span
								class="glyphicon glyphicon-list-alt"></span> <br>Afficher mes
								fiches de frais
							</a>
                        <?php

}
                        ?>
                           <?php

if ($_SESSION['metier'] == "comptable") {
                            ?>
                            <a
								href="index.php?uc=valideFrais&action=selectionnerUtilisateur"
								class="btn btn-warning btn-lg" role="button"> <span
								class="glyphicon glyphicon-ok"></span> <br>Valider la fiche de
								frais
							</a> <a href="index.php?uc=suivreFrais&action=afficherSuivi"
								class="btn btn-warning btn-lg" role="button"> <span
								class="glyphicon glyphicon-euro"></span> <br>Suivre les
								paiements
							</a>
                           <?php

}
                        ?>
                    </div>
					</div>
				</div>
			</div>
		</div>
	</div>