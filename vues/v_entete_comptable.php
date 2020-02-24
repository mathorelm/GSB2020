<?php
/**
 * Vue de l'entête du comptable
 *
 * PHP Version 7
 *
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author   Louis-Marin Mathorel <gsb2020@free.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>
                             	<li <?php if (!$uc || $uc == 'accueil') { ?>class="active" <?php } ?>>
                                	<a href="index.php">
                                    	<span class="glyphicon glyphicon-home"></span>
                                    	Accueil
                                	</a>
                            	</li>
                            	<li <?php if ($uc == 'valideFrais') { ?>class="active"<?php } ?>>
                                	<a href="index.php?uc=valideFrais&action=selectionnerUtilisateur">
                                    	<span class="glyphicon glyphicon-ok"></span>
                                    	Valider les fiches de Frais
                                	</a>
                            	</li>
                            	<li <?php if ($uc == 'suivreFrais') { ?>class="active"<?php } ?>>
                                	<a href="index.php?uc=suivreFrais&action=afficherSuivi">
                                    	<span class="glyphicon glyphicon-euro"></span>
                                    	Suivre le paiement des fiches de frais
                                	</a>
                            	</li>
                            	<li <?php if ($uc == 'deconnexion') { ?>class="active"<?php } ?>>
                                	<a href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                    	<span class="glyphicon glyphicon-log-out"></span>
                                    	Déconnexion
                                	</a>
                               	</li>

