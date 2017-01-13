<?php
/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */


/* **********************************
 *      PHP CONFIGURATION
 ************************************/

// Rapporte toutes les erreurs à part les E_NOTICE et E_WARNING
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Reporter toutes les erreurs PHP = debug uniquement
error_reporting(E_ALL);



/************************************
 *      WEBSITE CONFIGURATION
 ************************************/
define('SITE_NAME', 'E-liCo');
define('BASE_URL', 'https://e-lico.net');
define('COMMON_URL', 'https://e-lico.net');




/* **********************************
 *     SECURITY CONFIG
 ************************************/
define('SERVER_MAC_KEY', '1234567890azertyuiopqsdfghjklm');


?>