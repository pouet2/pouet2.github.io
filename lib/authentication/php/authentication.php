<?php

/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */


/**
 *
 *
 */
function is_login_recorded( $user_login ){




}




/**
 *
 *
 */
function update_authentication_attempt( $user_login ){




}



/**
 *
 *
 */
function update_authentication_succeed( $user_login ){




}


/**
 *
 *
 */
function is_password_correct( $user_login, $password ){




}




/**
 *
 *
 */
function change_password( $user_login, $old_password, $new_password ){




}




/**
 *
 *
 */
function unlog_user(){
	// Destruction des variables de la session
	session_unset();

	// R�initialisation du tableau de session - On le vide int�gralement
	$_SESSION = array();

	// Destruction de la session
	session_destroy();

	// Destruction du tableau de session
	unset($_SESSION);
}





/**
 *
 *
 */
function remove_user( $user_login ){




}









?>