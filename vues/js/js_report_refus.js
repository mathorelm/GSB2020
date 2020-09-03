/**
 * \brief Fonctions auxiliaires pour vue "Validation Frais Hors Forfait Comptable"
 *
 * \details fonctions JavaScript
 *
 * \category  PPE
 * \package   GSB
 * \author    Réseau CERTA <contact@reseaucerta.org>
 * \author    Louis-Marin Mathorel <gsb2020@free.fr>
 * \copyright 2017 Réseau CERTA
 * \license   Réseau CERTA
 * \version   GIT: <0>
 * \link      http://gsb2020.org
 */

/**
 * \brief Insère "REPORT : " sur la ligne demandée puis provoque l'envoi du
 * formulaire pour mise à jour. Gère la limitation à 100 caractères de la BDD.
 *
 * \param iD : index de la ligne HF à reporter (fourni par la page HTML)
 * \returns null
 */
function reporterLigne(evenement) {
	let id = evenement.srcElement.id;
	let laLigne = document.getElementById("txtHFlibelle"+id).value;
	if (laLigne.substring(0,6)!="REFUSE") {
		libelleTest = "REPORT : " + laLigne;
		if (libelleTest.length > 100) {
			document.getElementById("txtHFlibelle" + id).value = libelleTest.substring(9,
				100);
		} else {
			document.getElementById("txtHFlibelle" + id).value = libelleTest;
		}
		document.forms["form" + id].submit();
	} else {
		alert('Impossible de reporter un frais refusé.');
	};
};
/**
 * \brief Insère "REFUSE : " sur la ligne demandée puis provoque l'envoi du
 * formulaire pour mise à jour. Gère la limitation à 100 caractères de la BDD.
 *
 * \param iD : index de la ligne HF à reporter (fourni par la page HTML)
 * \returns null
 */
function refuserLigne(evenement) {
	let id = evenement.srcElement.id;
	libelleTest = "REFUSE : " + document.getElementById("txtHFlibelle" + id).value;
	if (libelleTest.length > 100) {
		document.getElementById("txtHFlibelle" + id).value = libelleTest.substring(9,
				100);
	} else {
		document.getElementById("txtHFlibelle" + id).value = libelleTest;
	}
	document.forms["form" + id].submit();
};

window.addEventListener("load", function() {
	let tabButtonsReporter = window.document
			.querySelectorAll('button[name="cmdReporter"]');
	let tabButtonsRefuser = window.document
			.querySelectorAll('button[name="cmdRefuser"]');
	let tabTextHFlibelle = window.document.querySelectorAll('text[name="txtHFlibelle"]');
	for (let i = 0; i < tabButtonsReporter.length; i++) {
		let leFrais = tabTextHFlibelle[i].value;
		if (leFrais.substring(0,6)!="REFUSE") {
			//ajouter la possibilité de reporter si non refusé
			tabButtonsReporter[i].addEventListener("click", reporterLigne);
			tabButtonsRefuser[i].addEventListener("click", refuserLigne);
		} else {
			//impossible d'intervenir pour modifier la mention "REFUSE"
			tabTextHFlibelle[i].readonly = true;
			//cacher les boutons
			tabButtonsReporter[i].visible = false;
			tabButtonsRefuser[i].visible = false;
		}

	}
});