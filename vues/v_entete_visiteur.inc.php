<?php
/**
 * \brief Vue Entête Visiteur
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
                                   <li <?php

if (!$uc || $uc == 'accueil') {
                                    ?>class="active" <?php
                                }
                                ?>>
                                    <a href="index.php">
                                        <span class="glyphicon glyphicon-home"></span>
                                        Accueil
                                    </a>
                                </li>
                                <li <?php

if ($uc == 'gererFrais') {
                                    ?>class="active"<?php
                                }
                                ?>>
                                    <a href="index.php?uc=gererFrais&action=saisirFrais">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                        Renseigner la fiche de frais
                                    </a>
                                </li>
                                <li <?php

if ($uc == 'etatFrais') {
                                    ?>class="active"<?php
                                }
                                ?>>
                                    <a href="index.php?uc=etatFrais&action=selectionnerMois">
                                        <span class="glyphicon glyphicon-list-alt"></span>
                                        Afficher mes fiches de frais
                                    </a>
                                </li>
                                <li <?php

if ($uc == 'deconnexion') {
                                    ?>class="active"<?php
                                }
                                ?>>
                                    <a href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                        <span class="glyphicon glyphicon-log-out"></span>
                                        Déconnexion
                                    </a>
