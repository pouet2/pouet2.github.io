<?php  

/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */

	require_once '../../form/php/check_form.php';
	require_once '../config/config.php';
			

	// Si utilisateur loggué, prolonger la durée de la session.
	session_start();

	// Inclusion du fichier de lang de la lib authentication
	require_once '../lang/'.$_SESSION['lang'].'.lang.php';
	

	// 1. VERIFICATION DE LA PROVENANCE DU FORMULAIRE
	$sites = array(	AUTHENTICATION_FORM_PAGE );
	$_SESSION['connexion_form_error_message'] = '';
	
	if( isset($_SERVER["HTTP_REFERER"]) && !in_array($_SERVER['HTTP_REFERER'], $sites ) ){
		$_SESSION['connexion_form_error_message'] .= AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE."<br/>";
		header('Location: '.AUTHENTICATION_FORM_PAGE);
		exit;
	}


	// 2. VERIFICATION DES CHAMPS
	$form_OK = true;
	
	// Field e-mail adress
	if( !isset($_POST['adresse_e-mail']) || !checkMandatory($_POST['adresse_e-mail']) ){
		$_SESSION['connexion_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_EMAIL."<br/>";
		$form_OK = false;
	}
	if(  !checkLenght( $_POST['adresse_e-mail'], 6, intval(AUTHENTICATION_EMAIL_MAX_LENGHT)) ){
		$_SESSION['connexion_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_EMAIL."<br/>";
		$form_OK = false;
	}
	$email = $_POST['adresse_e-mail'];
	
	
	
	// Field password
	if( !isset($_POST['password']) || !checkMandatory($_POST['password']) ){
		$_SESSION['connexion_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_PASSWORD.'<br/>';
		$form_OK = false;
	}
	if(  !checkLenght( $_POST['password'], intval(AUTHENTICATION_PASSWORD_MIN_LENGHT), intval(AUTHENTICATION_PASSWORD_MAX_LENGHT)) ){
		$_SESSION['connexion_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_PASSWORD.'<br/>';
		$form_OK = false;
	}
	$password = $_POST['password'];

	
	// Si un champ KO => Retour à la page d'accueil avec affichage de la raison de l'échec
	if( !$form_OK ){
		header('Location: '.AUTHENTICATION_FORM_PAGE);
		exit;
	}
		
	
	// 3. VERIFICATION QUE L'IDENTIFIANT EST PRESENT
	require_once 'sql_request.php';
	require_once '../../sql/config/config.php';
	require_once '../../sql/php/sql.php';
	
	$req_login_available = authentication_login_available_get_sql_request($email);
	$result = mysql_myquery(SQL_AUTHENTICATION_BASE, $req_login_available);
	
	// Si taille différente de 0, alors on retourne à la page d'accueil avec le message d'erreur qui va bien.
	if( $result->num_rows == 0 ){
		$_SESSION['connexion_form_error_message'] .= AUTHENTICATION_ERROR_UNKNOWN_LOGIN."<br/>";
		header('Location: '.AUTHENTICATION_FORM_PAGE);
		exit;
	}
	
	
	// 4. ENREGISTREMENT EN BDD DE LA TENTATIVE DE CONNEXION
	// Notification dans la BdD dans last_connexion_attempt
	$req_authentication_attempt = authentication_update_last_connexion_attempt_get_sql_request($email);
	mysql_myquery(SQL_AUTHENTICATION_BASE, $req_authentication_attempt);
	
	
	// 5. VERIFICATION EN BdD DU PASSWORD
	$req_password = authentication_connexion_get_sql_request($email, $password);
	$sql_results = mysql_myquery(SQL_AUTHENTICATION_BASE, $req_password);
	$num_rows = $sql_results->num_rows;
	$sql_results->close();
	
	// 5.a) Si KO, retour au formulaire avec message d'erreur
	if( $num_rows == 0 ){
		$_SESSION['connexion_form_error_message'] .= AUTHENTICATION_ERROR_WRONG_PASSWORD."<br/>";
		header('Location: '.AUTHENTICATION_FORM_PAGE);
		exit;				
	}
	
	// 5.b) Si OK, notification en BdD et passage à la passe de login
	// Notification dans la BdD dans last_connexion_attempt et last_connexion_succeed
	$req_authentication_succeed = authentication_update_last_connexion_succeed_get_sql_request($email);
	mysql_myquery(SQL_AUTHENTICATION_BASE, $req_authentication_succeed);

	// Mise en session du login de l'utilisateur (pour utilisation en ouverture des pages suivantes).
	$_SESSION['login'] = $email;
		
	header('Location: '.AUTHENTICATION_FIRST_PAGE);
	exit;	
?>