<?php  

/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */

	require_once '../../form/php/check_form.php';
	require_once '../config/config.php';
			
	require_once 'sql_request.php';
	require_once '../../sql/config/config.php';
	require_once '../../sql/php/sql.php';
	
	// TODO - Retirer cette dépendance par une vers la librairie SQL pour rendre lib authentication indépendante du site.
	// require_once '../../../includes/functions.php';

	// Si utilisateur loggué, prolonger la durée de la session.
	session_start();

	// Inclusion du fichier de lang de la lib authentication
	include_once '../lang/'.$_SESSION['lang'].'.lang.php';
	
	// TODO - Adapter les messages d'erreurs
	// TODO - Adapter la redirection vers param.
	// TODO - Mettre un petit message de confirmation de bon traitement.
	
	// Initialisation des messages d'erreur
	$form_OK = true;
	if (isset($_SESSION['change_password_form_error_message'])){
		$_SESSION['change_password_form_error_message'] = '';
	}
	$_SESSION['change_password_form_error_message'] = '';
	
	
	// VERIFICATION DE LA PROVENANCE DU FORMULAIRE
	$sites = array(	'http://www.e-lico.com/login/', 'http://www.e-lico.net/login/');

	if( isset($_SERVER["HTTP_REFERER"]) ){
		if( !in_array($_SERVER['HTTP_REFERER'], $sites )){
			$_SESSION['change_password_form_error_message'] .= AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE."<br/>";
			header('Location: '.AUTHENTICATION_FIRST_PAGE);
			exit;
		}
	}	


	// VERIFICATION DES CHAMPS
	// Field old_password
	$old_password 	= '';
	if (isset($_POST['old_password'])){
		$old_password = $_POST['old_password'];
	}
	else {
		// TODO - Rediriger vers le formulaire de saisie avec Message d'erreur .= Champs obligatoire non renseigné.

		$form_OK = false;		
	}
	if( !checkMandatory($old_password) || !checkLenght( $old_password, intval(AUTHENTICATION_PASSWORD_MIN_LENGHT), intval(AUTHENTICATION_PASSWORD_MAX_LENGHT)) ){
		$_SESSION['change_password_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_PASSWORD.'<br/>';
		$form_OK = false;
	}
	

	// Field new_password_1
	$new_password_1	= '';
	if (isset($_POST['new_password_1'])){
		$new_password_1 = $_POST['new_password_1'];
	}
	else {
		// TODO - Rediriger vers le formulaire de saisie avec Message d'erreur .= Champs obligatoire non renseigné.
		$form_OK = false;
	}
	if( !checkMandatory($new_password_1) || !checkLenght( $new_password_1, intval(AUTHENTICATION_PASSWORD_MIN_LENGHT), intval(AUTHENTICATION_PASSWORD_MAX_LENGHT)) ){
		$_SESSION['change_password_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_PASSWORD.'<br/>';
		$form_OK = false;
	}

	
	// Field new_password_2
	$new_password_2	= '';
	if (isset($_POST['new_password_2'])){
		$new_password_2 = $_POST['new_password_2'];
	}
	else {
		// TODO - Rediriger vers le formulaire de saisie avec Message d'erreur .= Champs obligatoire non renseigné.
		$form_OK = false;
	}
	if( !checkMandatory($new_password_2) || !checkLenght( $new_password_2, intval(AUTHENTICATION_PASSWORD_MIN_LENGHT), intval(AUTHENTICATION_PASSWORD_MAX_LENGHT)) ){
		$_SESSION['change_password_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_PASSWORD.'<br/>';
		$form_OK = false;
	}

	
	// Si un champ KO => Retour à la page d'accueil avec affichage de la raison de l'échec
	if( !$form_OK ){
		header('Location: '.AUTHENTICATION_FIRST_PAGE);
		exit;
	}


	// VERIFICATION QUE LES DEUX MOTS DE PASSE SONT IDENTIQUES
	if( $new_password_1 !== $new_password_2 ){
		$_SESSION['change_password_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_PASSWORD.'<br/>';
		header('Location: '.AUTHENTICATION_FIRST_PAGE);
		exit;
	}
	

	// RECUPERATION ET VERIFICATION DU PASSWORD
	$req_password = mysql_password_request($_SESSION['login']);
	$sql_results = mysql_myquery(SQL_AUTHENTICATION_BASE, $req_password);
	$password_recorded = $sql_results->fetch_assoc();
	$sql_results->close();
	
	// SI KO, RETOUR AU FORMULAIRE AVEC MESSAGE D'ERREUR
	if( strcmp($password,$old_password) !== 0  ){
		// Notification dans la BdD dans last_connexion_attempt
		$req_authentication_attempt = mysql_update_last_connexion_attempt_request($_SESSION['login']);
		mysql_myquery(SQL_AUTHENTICATION_BASE, $req_authentication_attempt);

		$_SESSION['change_password_form_error_message'] .= AUTHENTICATION_ERROR_WRONG_PASSWORD."<br/>";
		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit;				
	}

	// CHANGEMENT DU PASSWORD ET RETOUR A LA PAGE DE LOGIN
	$req_set_password = mysql_set_password_request($_SESSION['login'], $new_password_1);
	mysql_myquery(SQL_AUTHENTICATION_BASE, $req_set_password);

	// RETOUR A LA PAGE DE LOGIN
	header('Location: '.AUTHENTICATION_FIRST_PAGE);
	exit;
?>