<?php  

/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */
	
	// Démarrage ou restauration de la session
	session_start();

	require_once 'authentication.php';	
	unlog_user();

	require_once '../config/config.php';	
	header('Location: '.AUTHENTICATION_FORM_PAGE);
	exit;
?>