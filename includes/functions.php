<?php
/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */

require_once 'config.php';

	
/**
 *	Fonction de nettoyage d'un texte
 *
 *	@param	$text	Texte à nettoyer
 *
 *	@return		Texte nettoyé
 */
function cleanText($text){
	$text = trim($text); // delete white spaces after & before text

	if (1 === get_magic_quotes_gpc()){
		$stripslashes = create_function('$txt', 'return stripslashes($txt);');
	} else {
		$stripslashes = create_function('$txt', 'return $txt;');
	}

	// magic quotes ?
	$text = $stripslashes($text);
	$text = htmlentities($text, ENT_QUOTES); // converts to string with " and ' as well
	//$text = nl2br($text);
	return $text;
}



// TODO - Faire de même avec les adresse e-mail, à l'inscription et à la connexion => Transparent pour l'utilisateur.
/**
 *	Fonction de nettoyage d'un lien
 *
 *	@param	$text	Lien à nettoyer
 *
 *	@return		Lien nettoyé
 */
function cleanLink($text) {
	//Suppression des espaces en début et fin de chaine
	$text = trim($text);

	//Suppression des accents
	$text = strtr($texe,'�����������������������������������������������������','aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn');

	//mise en minuscule
	$text = strtolower($text);

	//Suppression des espaces et caracteres speciaux
	$text = str_replace(" ",'-',$text);
	$text = preg_replace('#([^a-z0-9-])#','-',$text);

	//Suppression des tirets multiples
	$text = preg_replace('#([-]+)#','-',$text);

	//Suppression du premier caractere si c'est un tiret
	if($text{0} == '-')
		$texte = substr($text,1);

	//Suppression du dernier caractere si c'est un tiret
	if(substr($text, -1, 1) == '-')
		$text = substr($text, 0, -1);

	return $text;
}


/**
 * 
 * @param
 * @return		
 *
 */
function unhtmlentities($chaineHtml) {
	$tmp = get_html_translation_table(HTML_ENTITIES);
	$tmp = array_flip ($tmp);
	$chaineTmp = strtr ($chaineHtml, $tmp);
	$chaineTmp = str_replace('&#039;', '\'', $chaineTmp);
	return $chaineTmp;
}




/**
 * Détecte le navigateur utilisé par l'utilisateur (déclaratif HTTP)
 *
 * @return		L'OS détecté à l'aide des données déclaratives HTTP
 *
 */
function detect_os(){

	$a = $_SERVER['HTTP_USER_AGENT'];

	if (preg_match('#windows\snt\s5\.1#i',$a))return('Microsoft Windows XP');
	if (preg_match('#linux\sx86_64#i',$a))return('Linux (64 bits)');
	if (preg_match('#khtml#i',$a))return('Linux');
	if (preg_match('#linux#i',$a))return('Linux');
	if (preg_match('#libwww-fm#i',$a))return('Linux');
	if (preg_match('#freebsd#i',$a))return('FreeBSD');
	if (preg_match('#mac\sos\sx#i',$a))return('Mac OS X');
	if (preg_match('#windows\snt\s6\.1#i',$a))return('Microsoft Windows 7');
	if (preg_match('#haiku#i',$a))return('Haiku');
	if (preg_match('#windows\snt\s6\.0;\swow64#i',$a))return('Microsoft Windows Vista (64bits)');
	if (preg_match('#windows\snt\s6\.0;\swin64#i',$a))return('Microsoft Windows Vista (64bits)');
	if (preg_match('#windows\snt\s6\.0#i',$a))return('Microsoft Windows Vista');
	if (preg_match('#sunos#i',$a))return('Open Solaris');
	if (preg_match('#android#i',$a))return('Android');
	if (preg_match('#windows\s95#i',$a))return('Microsoft Windows 95');
	if (preg_match('#windows\snt\s5\.0#i',$a))return('Microsoft Windows 2000');
	if (preg_match('#windows\snt\s5\.3#i',$a))return('Microsoft Windows Server 2003');
	if (preg_match('#windows\snt#i',$a))return('Microsoft Windows NT');
	if (preg_match('#windows\s98#i',$a))return('Microsoft Windows 98');
	if (preg_match('#windows\sce#i',$a))return('Microsoft Windows Mobile');
	if (preg_match('#windows\sphone\sos[\s\/]([0-9v]{1,7}(?:\.[0-9a-z]{1,7}){0,7})#i',$a,$c))return('Microsoft Windows Phone version '.$c[1]);
	if (preg_match('#mac_powerpc#i',$a))return('Mac OS X');
	if (preg_match('#macintosh#i',$a))return('Macintosh');
	if (preg_match('#cygwin_nt#i',$a))return('Microsoft Windows 2000');
	if (preg_match('#os\/2#i',$a))return('Microsoft OS/2');
	if (preg_match('#symbianos[\s\/]([0-9v]{1,7}(?:\.[0-9a-z]{1,7}){0,7})#i',$a,$c))return('Symbian OS version '.$c[1]);
	if (preg_match('#symbian-crystal[\s\/]([0-9v]{1,7}(?:\.[0-9a-z]{1,7}){0,7})#i',$a,$c))return('Symbian OS version '.$c[1]);
	if (preg_match('#offbyone;\swindows\s2000#i',$a))return('Microsoft Windows XP');
	if (preg_match('#windows\s2000#i',$a))return('Microsoft Windows 2000');
	if (preg_match('#nintendo\swii#i',$a))return('Nintendo Wii');
	if (preg_match('#playstation\sportable#i',$a))return('PlayStation Portable');
	if (preg_match('#iphone\sos\s[\s\/]([0-9v]{1,7}(?:[\._][0-9a-z]{1,7}){0,7})#i',$a,$c))return('iPhone OS version '.$c[1]);

	return 'OS non identifie';
}


/**
 * Détecte le navigateur utilisé par l'utilisateur (déclaratif HTTP)
 *
 * @return	Le navigateur détecté à l'aide des données déclaratives HTTP
 */
function detect_browser(){
	$a = $_SERVER['HTTP_USER_AGENT'];
	return get_browser(null, true);
}


/**
 * Inclut le header spécifique à la page indiquée et ouvre le body.
 *
 * @param	$result_page		La page de résultat indiquée
 *
 */
function includeHeader($result_page) {
	include 'includes/commons/header.php';

	include 'pages/'.$result_page.'/header_page.php';
	
	echo '<body>';
}


// TODO - Permettre d'avoir plusieurs niveaux d'arborescence dans le dossier page.
/**
 * RequestDispatcher
 * Processing of the request uri
 */
function getRequestedUri() {

	$dossier_pages = 'pages';
	$page_home	= 'home';

	// Page
	if(isset($_GET['p'])){
		$page = cleanText($_GET['p']);
	}
	else {
		return($page_home);
	}
	
	// Directory protection
	$temp = explode('/',$page);

	// Existing page?
	if(file_exists($dossier_pages.'/'.$temp[0].'/index.php'))
		return($temp[0]);
	else {
		// TODO - Il peut être préférable de rediriger vers la page error.php
		return($page_home);
	}
}

?>