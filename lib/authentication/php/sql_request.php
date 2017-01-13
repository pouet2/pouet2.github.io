<?php  

/**
 * E-mProvement SARL
 * All right reserved
 *
 * NB : Ce document est trié par ordre d'utilisation par un user.
 *
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */




/**
 * INSCRIPTION ET CONNEXION
 *
 * Vérifie la disponibilité (ou pas) d'une adresse e-mail, avant d'envoyer, s'il est disponible, les requêtes d'inscription.
 *
 *	@param		$user_login		Login demandé par le visiteur
 *
 *	@return		La requête de vérification du login
 *					- Si login disponible => Vide
 *							- Si login indisponible => le login si un utilisateur utilise déjà ce login.
 */
function authentication_login_available_get_sql_request($user_login){
	// Rédaction de la requête
	$req = "SELECT user_login FROM `user_authentication` WHERE `user_login` = '".$user_login."' LIMIT 0 , 1 ;";
	return $req;
}


/**
 * INSCRIPTION
 * 
 * Requête d'ajout d'une ligne dans la table authentication pour l'utilisateur.
 *
 *	@param		$user_login
 *	@param		$user_password
 *	@return
 */
function authentication_inscription_get_sql_request($user_login, $user_password){
	// Date pour SQL
	$time_stamp = date("Y-m-d H:i:s");
	
	// Rédaction de la requête
	$req = "INSERT INTO  `user_authentication` (
			`user_login` ,
			`user_password` ,
			`user_last_password_modification` ,
			`user_last_connexion_succeed` ,
			`user_last_connexion_attempt`
			)
			VALUES ('".$user_login."',  PASSWORD('".$user_password."'),  '".$time_stamp."',  '".$time_stamp."',  '".$time_stamp."');";
	return $req;
}



/**
 * CONNEXION
 *
 *
 *
 *	@param		$user_login
 *	@param		$user_password
 *	@return
 */
function authentication_connexion_get_sql_request($user_login, $user_password){
	// Rédaction de la requête
	$req = "SELECT user_login FROM `user_authentication` WHERE `user_login` = '".$user_login."' AND `user_password` = PASSWORD('".$user_password."') LIMIT 0 , 1 ;";
	return $req;
}



/**
 * CONNEXION
 *
 *
 *
 *	@param		$user_login
 *	@return
 */
 function authentication_update_last_connexion_attempt_get_sql_request($user_login){
	// Date pour SQL
	$time_stamp = date("Y-m-d H:i:s");
	
	// Rédaction de la requête
	$req = "UPDATE `user_authentication` SET `user_last_connexion_attempt`=`".$time_stamp."` WHERE `user_login`=`".$user_login."` ;";
	return $req;
}

/**
 * CONNEXION
 *
 *
 *
 *	@param		$user_login
 *	@return
 */
 function authentication_update_last_connexion_succeed_get_sql_request($user_login){
	// Date pour SQL
	$time_stamp = date("Y-m-d H:i:s");

	// Rédaction de la requête
	$req = "UPDATE `user_authentication` SET  `user_last_connexion_succeed`=`".$time_stamp."` WHERE `user_login`=`".$user_login."` ;";
	return $req;
}



/**
 * CHANGE PASSWORD
 *
 *
 *
 *	@param		$user_login
 *	@param		$user_password
 *	@return
 */
function authentication_set_password_get_sql_request($user_login, $new_password){
	// Date pour SQL
	$time_stamp = date("Y-m-d H:i:s");
	
	// Rédaction de la requête
	$req = "UPDATE `user_authentication` SET  `user_authentication`.`user_password`= PASSWORD(`".$new_password."`) , `user_last_password_modifcation`=`".$time_stamp."` WHERE `user_authentication`.`user_login`=`".$user_login."` ;";
	return $req;
}


/**
 * DESINSCRIPTION
 *
 *
 *	@param		$user_login
 *	@return
 */
function authentication_remove_user_get_sql_request($user_login){
	// Rédaction de la requête
	$req = "DELETE FROM `user_authentication` WHERE `user_authentication`.`user_login`=`".$user_login."` ;";
	return $req;
}



?>