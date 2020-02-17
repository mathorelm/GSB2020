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
	function reporterLigne(iD) {
		/*libelleTest = "REPORT : " + document.getElementById("HFlibelle"+iD).value;
		if (libelleTest.length > 100) {
			document.getElementById("HFlibelle"+iD).value = libelleTest.substr(9,100);
		} else {
			document.getElementById("HFlibelle"+iD).value = libelleTest;
		}		
		document.forms["form"+iD].submit();*/
		alert('Déclenchement report !');
	};
	/**
	 * Insère "REFUSE : " sur la ligne demandée puis provoque l'envoi du formulaire
	 * pour mise à jour. Gère la limitation à 100 caractères de la BDD.
	 * @param iD : index de la ligne HF à reporter (fourni par la page HTML)
	 * @returns null
	 */
	function refuserLigne(iD) {		
		/*libelleTest = "REFUSE : " + document.getElementById("HFlibelle"+iD).value;
		if (libelleTest.length > 100) {
			document.getElementById("HFlibelle"+iD).value = libelleTest.substr(9,100);
		} else {
			document.getElementById("HFlibelle"+iD).value = libelleTest;
		}		
		document.forms["form"+iD].submit();*/
		alert('Déclenchement refus !');
	};
	
	function testfunction() {
		alert('Test !');
	}
	
	window.addEventListener("load", function() {
		//document.getElementsByClassName("form-control").addEventListener("click", testfunction);
		alert('Evénement de chargement');
		let tabButtonsReporter = window.document.querySelectorAll('button[name="Reporter"]');
		let tabButtonsRefuser = window.document.querySelectorAll('button[name="Refuser"]');
		for (let i=0;i<tabButtonsReporter.length;i++) {
			tabButtonsReporter[i].addEventListener("click",reporterLigne(id));
			tabButtonsRefuser[i].addEventListener("click", refuserLigne(id));
		}
	});