<?php  

/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */


/**
 *	Vérifie qu'un champ obligatoire a bien été renseigné.
 *
 *	@param	$field	La valeur à contrôler
 *
 *	@return		True si la valeur est renseignée
 *			False si la valeur n'est pas renseignée ou vide.
 */
function checkMandatory($field){
	if( empty( $field )){
		return false;
	}
	return true;
}


/**
 *	V�rifie qu'un champ respecte les contraintes de taille max et taille min.
 *
 *	@param	$field	La valeur � contr�ler
 *
 *	@return		True si la valeur est respecte les longueurs impos�es
 *			False si la valeur est trop longue ou trop courte.
 */
function checkLenght($field, $min, $max){
	if( strlen($field) < $min || strlen($field) > $max ){
		return false;
	}
	return true;
}

/**
 *	V�rifie qu'un e-mail respecte les r�gles de construction.
 *
 *	@param	$field	La valeur � contr�ler
 *
 *	@return		True si l'adresse e-mail est correctement renseign�e
 *			False si l'adresse e-mail est invalide.
 */
function checkEmail($field){
	if( filter_var($email, FILTER_VALIDATE_EMAIL) ){
		return true;
	}
	return false;
}

	
?>