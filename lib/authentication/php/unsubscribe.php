<?php  
	
/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */

	// Si utilisateur loggué, prolonger la durée de la session.
	session_start();
	
	// Inclusion du fichier de lang de la lib authentication à l'aide des données de sessions
	require_once '../lang/'.$_SESSION['lang'].'.lang.php';


	
	//require_once '../../form/php/check_form.php';
	require_once '../config/config.php';
			
	require_once 'sql_request.php';
	require_once '../../sql/php/sql.php';
	require_once '../../sql/config/config.php';
	
	
	
	// DESINSCRIPTION DE L'UTILISATEUR

	//DESINSCRIPTION DANS LA BASE D'AUTHENTIFICATION
	// TODO - Mettre cette étape dans une fonction de la lib authentication !!
	// Rédaction de la requête SQL à l'aide des données des champs
	$req_authentication_unsubscribe = authentication_remove_user_get_sql_request($_SESSION['login']);

	// Execution de la requête
	mysql_myquery(SQL_AUTHENTICATION_BASE, $req_authentication_unsubscribe );
		
		
	//DESINSCRIPTION DANS LA BASE D'UTILISATEURS
	// Rédaction de la requête SQL à l'aide des données des champs
	// TODO - Changer la requête !!!
	// TODO - Mettre cette étape dans une fonction spécifique à E-liCO !
	require_once '../../../includes/sql_request.php';
	
	$req_user_unsubscription = mysql_user_unsubscription_request($_SESSION['login']);

	// Execution de la requête
	mysql_myquery(SQL_USERS_BASE, $req_user_unsubscription);
	

	// ENVOI D'UN E-MAIL DE CONFIRMATION DE DESINSCRIPTION
	// TODO - Utiliser la librairie PHPMailer
	// TODO - Retirer cette partie du code de la lib Authentication => Déplacer la partie métier dans le site.
	define('EMAIL_UNSUBSCRIPTION_CONFIRMATION_ADRESS','E-liCo <noreply@e-lico.net>');
	define('EMAIL_UNSUBSCRIPTION_CONFIRMATION_TITLE','E-liCo - Confirmation de désinscription');

	$message_automatique = file_get_contents("./".$_SESSION['lang']."_unsubscription_confirmation.html");

	$headers =  "From: ".EMAIL_UNSUBSCRIPTION_CONFIRMATION_ADRESS."\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

	mail($_SESSION['login'], EMAIL_UNSUBSCRIPTION_CONFIRMATION_TITLE, $message_automatique, $headers);

	// TODO - Traiter les cas d'e-mails non reçus => Rejeu ou Suppression de l'inscription ?

		
	// Redirige vers la page de déconnexion
	// TODO - Ajouter la page de déconnexion dans les paramètres.		
	header('Location: '.AUTHENTICATION_DECONNEXION_PAGE);
	exit;
	
?>
