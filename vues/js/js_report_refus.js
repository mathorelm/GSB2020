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
	libelleTest = "REPORT : " + laLigne;
	if (libelleTest.length > 100) {
		document.getElementById("txtHFlibelle" + id).value = libelleTest.substring(9,
		100);
	} else {
		document.getElementById("txtHFlibelle" + id).value = libelleTest;
	}
	document.forms["form" + id].submit();
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
	for (let i = 0; i < tabButtonsReporter.length; i++) {
		tabButtonsReporter[i].addEventListener("click", reporterLigne);
		tabButtonsRefuser[i].addEventListener("click", refuserLigne);
		}

	}
});