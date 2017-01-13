<?php
/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */

include_once 'lib/lang/config/config.php';

	
/**
 * Détecte la langue du navigateur (déclaratif HTTP)
 */
function detectLang(){
	if( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE']) ){
		$preferedLanguage = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	}
	else {
		return LANG_DEFAULT;
	}

	// TODO - Déplacer cette liste dans la configuration de la lib "lang" // Ajouter Arabe
	$knownLanguage = array('en', 'fr', 'de', 'it', 'es', 'hu', 'ru');

	foreach ($preferedLanguage as $lang)  {
		$lang = substr($lang, 0, 2);
		if(in_array($lang, $knownLanguage))
			return $lang;
	}
	return LANG_DEFAULT;
}


?>