<?php  

/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */



/**
 * INSCRIPTION EN BASE E-LICO
 * 
 * @param
 * @param
 * @param
 * @param
 * @param
 * @return
 *
 */
function mysql_user_inscription_request($user_login, $user_lang, $user_firstname, $user_name, $user_privacy){
	// Date pour SQL
	$date = date("Y-m-d H:i:s");

	// Rédaction de la requête
	$req = "INSERT INTO  `e-lico_users`.`user_parameters` (
			`user_login` ,
			`user_lang` ,
			`user_profile_image` ,
			`user_firstname` ,
			`user_name`,
			`user_registration_date`,
			`user_privacy`
			)
			VALUES ('".$user_login."',  '".$user_lang."',  'null',  '".$user_firstname."',  '".$user_name."',  '".$date."', '".$user_privacy."' ); ";
	return $req;
}


/**
 * 
 * 
 * @param
 * @return
 *
 */
function mysql_get_user_data_request($user_login){
	// Rédaction de la requête
	$req = "SELECT  `user_lang`,  `user_profile_image`,  `user_firstname`,  `user_name`,  `user_birthday`,  `user_privacy` FROM `user_parameters` WHERE `user_login` = '".$user_login."' LIMIT 0 , 1";
	return $req;
}


/**
 *
 * @param
 * @param
 * @param
 * @return
 *
 */
function mysql_set_user_birthday_request($user_login, $month, $year){
	// Rédaction de la requête
	$req = "";
	return $req;
}


/**
 *
 * @param
 * @param
 * @return
 *
 */
function mysql_set_user_privacy_request($user_login, $user_privacy){
	// Rédaction de la requête
	$req = "";
	return $req;
}




/**
 * DESINSCRIPTION D'UN USER EN BASE E-LICO
 * 
 * @param
 * @return
 *
 */
function mysql_user_unsubscription_request($user_login){
	// Rédaction de la requête
	$req = "DELETE FROM `e-lico_users`.`user_parameters` WHERE `user_parameters`.`user_login`=`".$user_login."` ;";
	return $req;
}

?>