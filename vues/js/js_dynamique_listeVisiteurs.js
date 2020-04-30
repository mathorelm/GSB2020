/**
 * Fonctions auxiliaires pour vue "affichage liste visiteurs"
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

function afficheListeMois() {

	mesVisiteurs = document.getElementById("lstVisiteurs");
	mesMois = document.getElementById("lstMois");
	monChoix = mesVisiteurs.options[mesVisiteurs.selectedIndex].value;
	// permet de rendre visible le select "LstMois"
	mesMois.style.display = "block";
	mesMois.selectedIndex = 0;
	marqueurchoix = false;
	//cacher les éléments qui ne correspondent pas à l'ID sélectionné.
	for (let i = 0; i < document.getElementById("lstMois").options.length; i++) {
		if (mesMois.options[i].label != monChoix) {
			mesMois.options[i].style.display = "none";
		} else {
			mesMois.options[i].style.display = "block";
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

window.addEventListener("load", function() {
	window.document.querySelector("#lstVisiteurs").addEventListener("change",
			afficheListeMois);
	window.document.querySelector("#lstMois").addEventListener("change",
			declencheFiche);
	document.getElementById("lstMois").style.display = "none";
});