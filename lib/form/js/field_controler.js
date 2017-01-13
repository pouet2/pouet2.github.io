
/************************************
 * 
 *      CONTROLE DE FORMAT DES CHAMPS
 * 
 ************************************/

/**
 * Contrôle de la taille
 */
function checkLength(champ, minLength, maxLength)
{
	if(champ.value.length < minLength || champ.value.length > maxLength)
	{
		return false;
	}

	return true;
}


/**
 * Contrôle ANS - Alpha - Numérique - Spéciaux
 */
function checkFormatANS(champ, ...)
{

	return true;
}


/**
 * Contrôle la Casse (Majuscule / minuscule)
 */
function checkCasse(champ, ...)
{

	return true;
}


/**
 *    Force les caractères spéciaux
 */
function forceSpecialCaracters(champ, ...)
{

	return true;
}


/**
 *    Force la casse
 */
function forceCasse(champ, ...)
{

	return true;
}


