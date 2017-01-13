<?php  

/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */



/***************************
 *         SESSIONS
*****************************/

/**
 *
 *
 *
 */
function session_set_value($key, $value){
	// Enregistrement de la valeur "value" dans la variable de session "key"
	$_SESSION[$key] = $value;
}


/**
 *
 *
 *
 */
function session_get_value($key){
	// Retourne la valeur enregistrée dans la variable de session "key"
	return $_SESSION[$key];
}


/**
 *
 *	@return		Indique si l'utilisateur est déjà connecté au service
 *
 *	Permet d'éviter une nouvelle authentification si l'utilisateur est toujours connecté.
 */
function session_check_connected(){
	// On prolonge la session
	session_start();

	// On teste si la variable de session existe et contient une valeur
	if(!empty($_SESSION['login']))
	{
		return true;
	}

	return false;
}



/**
 *
 *
 *
 */
function session_disconnect(){
	// Démarrage ou restauration de la session
	session_start();

	// Destruction des variables de la session
	session_unset();

	// Réinitialisation du tableau de session
	// On le vide intégralement
	$_SESSION = array();

	// Destruction de la session
	session_destroy();

	// Destruction du tableau de session
	unset($_SESSION);
}









?>