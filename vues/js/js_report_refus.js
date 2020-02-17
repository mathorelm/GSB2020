/**
 * Fonctions auxiliaires pour vue "Validation Frais Hors Forfait Comptable"
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

	/**
	 * Insère "REPORT : " sur la ligne demandée puis provoque l'envoi du formulaire
	 * pour mise à jour. Gère la limitation à 100 caractères de la BDD.
	 * @param iD : index de la ligne HF à reporter (fourni par la page HTML)
	 * @returns null
	 */
	function reporterLigne(id) {
		/*libelleTest = "REPORT : " + document.getElementById("HFlibelle"+id).value;
		if (libelleTest.length > 100) {
			document.getElementById("HFlibelle"+id).value = libelleTest.substr(9,100);
		} else {
			document.getElementById("HFlibelle"+id).value = libelleTest;
		}
		document.forms["form"+iD].submit();*/
		//alert('Déclenchement report ! id='+id);
	};
	/**
	 * Insère "REFUSE : " sur la ligne demandée puis provoque l'envoi du formulaire
	 * pour mise à jour. Gère la limitation à 100 caractères de la BDD.
	 * @param iD : index de la ligne HF à reporter (fourni par la page HTML)
	 * @returns null
	 */
	function refuserLigne(id) {
		/*libelleTest = "REFUSE : " + document.getElementById("HFlibelle"+id).value;
		if (libelleTest.length > 100) {
			document.getElementById("HFlibelle"+id).value = libelleTest.substr(9,100);
		} else {
			document.getElementById("HFlibelle"+id).value = libelleTest;
		}
		document.forms["form"+id].submit();*/
		//alert('Déclenchement refus ! id='+id);
	};

	window.addEventListener("load", function() {
		let tabButtonsReporter = window.document.querySelectorAll('button[name="Reporter"]');
		let tabButtonsRefuser = window.document.querySelectorAll('button[name="Refuser"]');
		for (let i=0;i<tabButtonsReporter.length;i++) {
			tabButtonsReporter[i].addEventListener("click",reporterLigne(i));
			tabButtonsRefuser[i].addEventListener("click", refuserLigne(i));
		}
	});