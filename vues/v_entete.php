<?php
/**
 * Vue Entête
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
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title> 
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./styles/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="./styles/style.css" rel="stylesheet">
        <?php if (isset($_SESSION['metier'])) {?><link href="./styles/surcharge_<?php echo $_SESSION['metier']?>.css" rel="stylesheet"><?php }?>
    </head>
    <body>
        <div class="container">
            <?php
            $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
            if ($estConnecte) {
            ?>
            	<div class="header">
                	<div class="row vertical-align">
                    	<div class="col-md-4">
                        	<h1>
                            	<img src="./images/logo.jpg" class="img-responsive" 
                                 	 alt="Laboratoire Galaxy-Swiss Bourdin" 
                                     title="Laboratoire Galaxy-Swiss Bourdin">
                        	</h1>
                    	</div>
                    	<div class="col-md-8">
                        	<ul class="nav nav-pills pull-right" role="tablist">
                            	<?php include_once 'vues/v_entete_' . $_SESSION['metier'] . '.php'?>                            
                        	</ul>
                    	</div>
                	</div>
            	</div>
            <?php } else { 
            ?>   
                <h1>
                    <img src="./images/logo.jpg"
                         class="img-responsive center-block"
                         alt="Laboratoire Galaxy-Swiss Bourdin"
                         title="Laboratoire Galaxy-Swiss Bourdin">
                </h1>
            <?php }
