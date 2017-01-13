<?php  

/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */

	// AUTHENTICATION
	require_once '../config/config.php';
	require_once 'sql_request.php';
	
	// Si utilisateur loggué, prolonger la durée de la session.
	session_start();
	
	// Inclusion du fichier de lang de la lib authentication à l'aide des données de sessions
	require_once '../lang/'.$_SESSION['lang'].'.lang.php';

	
	// 1. VERIFICATION DE LA PROVENANCE DU FORMULAIRE

	// Liste des sites autorisées
	// TODO - Mettre cette liste en variable global, déclarée dans le fichier de configuration de la lib
	$sites = array(	AUTHENTICATION_FORM_PAGE);
	
	$_SESSION['inscription_form_error_message'] = " ";
	if( isset($_SERVER['HTTP_REFERER']) && !in_array($_SERVER['HTTP_REFERER'], $sites ) ){
		$_SESSION['inscription_form_error_message'] .= AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE."<br/>";
		header('Location: '.AUTHENTICATION_FORM_PAGE);
		exit;
	}

	
	// 2. VERIFICATION DU CONTENU DES CHAMPS DU FORMULAIRE
	// FORM
	require_once '../../form/php/check_form.php';
	
	$form_OK = true;
	
	// Field firstname
	if ( !isset($_POST['firstname']) || !checkMandatory($_POST['firstname']) ){
		// Redirigera vers le formulaire de saisie avec Message d'erreur .= Champs obligatoire non renseigné.
		$_SESSION['inscription_form_error_message'] .= "Prénom non indiqué"."<br/>";
		$form_OK = false;		
	}
	
	if( !checkLenght($_POST['firstname'], 2, intval(AUTHENTICATION_FIRSTNAME_MAX_LENGHT) ) ){ 	// || filter_var($_POST['firstname'], FILTER_SANITIZE_STRING) ){
		// Redirigera vers le formulaire de saisie avec Message d'erreur .= Champs mal renseigné.
		$_SESSION['inscription_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_FIRSTNAME."<br/>";
		$form_OK = false;
	}
	
	// Field name
	if( !checkLenght($_POST['name'], 0, intval(AUTHENTICATION_NAME_MAX_LENGHT)) ){ 			// || filter_var($_POST['name'], FILTER_SANITIZE_STRING) ){
		$_SESSION['inscription_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_NAME."<br/>";
		$form_OK = false;
	}

	// Field e-mail_adress
	if ( !isset($_POST['adresse_e-mail']) || !checkMandatory($_POST['adresse_e-mail']) ){
		$_SESSION['inscription_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_EMAIL."<br/>";
		$form_OK = false;
	}

	if(  !checkLenght( $_POST['adresse_e-mail'], 6, intval(AUTHENTICATION_EMAIL_MAX_LENGHT)) ) {			// ||  filter_var($email, FILTER_VALIDATE_EMAIL) ){
		$_SESSION['inscription_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_EMAIL."<br/>";
		$form_OK = false;
	}

	
	// Field password
	if ( !isset($_POST['password']) || !checkMandatory($_POST['password']) ){
		$_SESSION['inscription_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_PASSWORD."<br/>";
		$form_OK = false;
	}

	if(  !checkLenght( $_POST['password'], intval(AUTHENTICATION_PASSWORD_MIN_LENGHT), intval(AUTHENTICATION_PASSWORD_MAX_LENGHT)) ){
		$_SESSION['inscription_form_error_message'] .= AUTHENTICATION_ERROR_INVALID_PASSWORD."<br/>";
		$form_OK = false;
	}

	
	// Si un champ KO => Retour à la page du formulaire avec affichage de la raison de l'échec
	if( !$form_OK ){
		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit;
	}

	

	// 3. PREPARATION DES DONNEES POUR l'ENREGISTREMENT
	// Met toute la chaine en minuscule puis met la première lettre de chaque mot en Majuscule.
	// TODO - Retirer les caractères spéciaux
	$firstname = ucwords(strtolower($_POST['firstname']));
	
	// Met le NOM en MAJUSCULE
	// TODO - Retirer les caractères spéciaux
	$name = strtoupper($_POST['name']);
	
	// TODO - Retirer les caractères spéciaux
	$email = $_POST['adresse_e-mail'];
	
	$password = $_POST['password'];

	

	// 4. VERIFICATION QUE L'IDENTIFIANT N'EST PAS DEJA UTILISE
	// Inclusion de la lib SQL
	require_once '../../sql/config/config.php';
	require_once '../../sql/php/sql.php';
	
	// Rédaction de la requête SQL à l'aide de l'adresse e-mail saisie = le login de l'utilisateur
	$req_login_available = authentication_login_available_get_sql_request($email);

	// Execution de la requête SQL
	$result = mysql_myquery(SQL_AUTHENTICATION_BASE, $req_login_available );
	$num_rows = $result->num_rows;
	$result->close(); 
	
	// Si taille différente de 0, alors on retourne à la page d'accueil avec le message d'erreur qui va bien.
	if( $num_rows > 0 ){
		$_SESSION['inscription_form_error_message'] .= AUTHENTICATION_ERROR_LOGIN_ALREADY_USED."<br/>";
		header('Location: '.AUTHENTICATION_FORM_PAGE);
		exit;
	}
	


	// 5. INSCRIPTION DE L'UTILISATEUR

	// 5.a) INSCRIPTION DANS LA BASE D'AUTHENTIFICATION
	// Rédaction de la requête SQL à l'aide des données des champs
	$req_authentication_inscription = authentication_inscription_get_sql_request($email, $password);

	// Execution de la requête
	mysql_myquery(SQL_AUTHENTICATION_BASE, $req_authentication_inscription );
		
		
	// 5.b) INSCRIPTION DANS LA BASE D'UTILISATEURS E-LICO
	
	// TODO - Mettre ce 5.b) dans une fonction / page spécifique à E-liCo.
	// E-LICO
	require_once '../../../includes/sql_request.php';
	
	$privacy = '0';
	
	// Rédaction de la requête SQL à l'aide des données des champs
	$req_user_inscription = mysql_user_inscription_request($email, $_SESSION['lang'], $firstname, $name, $privacy);
		
	// Execution de la requête
	mysql_myquery(SQL_USERS_BASE, $req_user_inscription);
		
	// TODO - Récupérer les valeurs de retour des requête : Si une des deux requêtes échoue, supprimer la précédente et retourner à la page de saisie avec un message d'erreur !


	// 5.c) ENVOI D'UN E-MAIL DE CONFIRMATION D'INSCRIPTION
	// TODO - Mettre ce 5.c) dans une fonction / page spécifique à E-liCo.
	define( 'EMAIL_REGISTRATION_CONFIRMATION_ADRESS' , "E-liCo <noreply@e-lico.net>" );
	define( 'EMAIL_REGISTRATION_CONFIRMATION_TITLE' , "E-liCo - Confirmation d'inscription" );

	// TODO - Ajouter des données de personnalisation "Bonjour Prénom," ...
	// TODO - Mettre le texte dans les fichiers lang de lib/mail/lang/...
	$message_automatique = file_get_contents("./".$_SESSION['lang']."_registration_confirmation.html");

	$headers =  "From: ".EMAIL_REGISTRATION_CONFIRMATION_ADRESS."\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

	mail( $email, EMAIL_REGISTRATION_CONFIRMATION_TITLE, $message_automatique, $headers);

	// TODO - Traiter les cas d'e-mails non reçus => Rejeu ou Suppression de l'inscription ?


	// 6. MISE EN SESSION DU LOGIN DE L'UTILISATEUR ET REDIRECTION VERS LA PREMIERE PAGE POST-LOG.
	$_SESSION['login'] 		= $email;
		
	// TODO - Changer pour l'appel de la fonction de connexion avec le login et le password
	header('Location: '.AUTHENTICATION_FIRST_PAGE);
	exit;
?>