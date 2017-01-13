<?php
/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */

include_once 'lib/sql/php/sql.php';


/**
 * Fonction de localisation (latitude, longitude) d'une adresse IP (quelle soit v4 ou v6) à l'aide d'une table de géolocalisation.
 *
 * @param	$adresse_ip	L'adresse IP à géolocaliser
 *
 * @return				Localisation trouvée à l'aide de la base de données
 *
 */
function IP_geolocalisation($adresse_ip){
	// IPv4
	if( filter_var( $adresse_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) != false)
	{
		return IPv4_geolocalisation($adresse_ip);
	}

	// IPv6
	else if( filter_var( $adresse_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) != false)
	{
		return IPv6_geolocalisation($adresse_ip);
	}
}


/**
 * Fonction de localisation (latitude, longitude) d'une adresse IPv4 à l'aide d'une table de géolocalisation.
 *
 * @param	$adresse_ip	L'adresse IPv4 à géolocaliser
 *
 * @return				Localisation trouvée à l'aide de la base de données
 *
 */
function IPv4_geolocalisation($adresse_ip){

	// 1. [Spécifique IPv4] Recherche en BdD des lignes ayant la même classe A

	// 1.a) Calcul de la classe A de l'adresse_IP
	$ClasseA = long2ip( ip2long($adresse_ip) - (ip2long($adresse_ip)%(256*256*256) ) );

	// 1.b) Suppression des derniers caractères = On ne garde que le numéro de la Classe A au format IPv6
	$adresse_courte = explode(".", $ClasseA);
	$adresse_courte = "::ffff:".$adresse_courte[0];

	// 1.c) Appel en BdD des lignes de cette Classe A.
	$req = "SELECT network_start_ip, geoname_id, latitude, longitude FROM `ip_location` WHERE  `network_start_ip` LIKE  '".$adresse_courte.".%' ;";
	$sql_resultats = mysql_myquery('geolocalisation', $req);

	
	// 2. [Spécifique IPv4] Recherche de la valeur la plus proche, en dessous de la valeur décimale de l'adresse IP du visiteur
	
		// Retire les valeurs pour lesquelles l'@IP est plus grande que celle du visiteur
	/*
		foreach( élément de l'array sql_resultats ){
			if( long de l'ip du tableau > long de l'ip du visiteur ){
				unset( ligne de l'array );
			}
		}

		// Recherche de l'@IP la plus grande restant dans le tableau + retire les @IP détectées comme plus petites que d'autres
		$localisation = première ligne;
		foreach( élément de l'array ){
			// Si nouvelle @IP > ancienne @IP
				// Retirer la plus petite du tableau (inutile, car destruction du tableau prévue à la fin de la fonction)

				// Mettre la nouvelle comme référence de géolocalisation
		}
	*/

	// 3. [FACULTATIF] [Spécifique IPv4] Si possible, vérification à l'aide de la taille de la plage qu'il s'agit de la bonne valeur.
		// SI OK -> Mettre dans log que valeur garantie.
		// SI KO -> Mettre dans log d'erreur que retour d'une valeur pas optimum.

	// Calcul du masque = Mettre les 3 derniers digits à 0
	$adresse_ip_reduite = long2ip( ip2long($adresse_ip) - (ip2long($adresse_ip)%256));
	$adresse_ip_reduite = "::ffff:".$adresse_ip_reduite;

	// Rédaction de la requête
	$req = "SELECT latitude, longitude FROM `ip_location` WHERE  `network_start_ip` LIKE  '".$adresse_ip_reduite."';"; 
	$sql_resultats = mysql_myquery(SQL_BASE, $req);

	$localisation = $sql_resultats->fetch_assoc();
	
	return $localisation;
}

/**
 * Fonction de localisation (latitude, longitude) d'une adresse IPv6 à l'aide d'une table de géolocalisation.
 *
 * @param	$adresse_ip	L'adresse IPv6 à géolocaliser
 *
 * @return			Localisation trouvée à l'aide de la base de données
 *
 */
function IPv6_geolocalisation($adresse_ip){
	// Rédaction de la requête
	$req = "SELECT latitude, longitude FROM `ip_location` WHERE  `network_start_ip` LIKE  '".$adresse_ip."';"; 
	$sql_resultats = mysql_myquery("geolocalisation", $req);

	return $sql_resultats;
}



?>