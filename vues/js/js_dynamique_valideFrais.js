/**
 * Fonctions auxiliaires pour vue "Validation Frais Comptable"
 *
 * JavaScript
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    Louis-Marin Mathorel <gsb2020@free.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://gsb2020.free.fr
 */
	
	
	function desactiverBoutonCorriger() {
		document.getElementById('corrigerForfait').disabled=true;
	}
	function sactiverBoutonCorriger() {
		document.getElementById('corrigerForfait').disabled=false;
	}
	
	window.addEventListener("load", function() {
		window.document.getElementById("#reinit").addEventListener("click", desactiverBoutonCorriger);
		window.document.getElementById(".form-control").addEventListener("click", activerBoutonCorriger);
	});