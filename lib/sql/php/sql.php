<?php

/**
 * E-mProvement SARL
 * All right reserved
 * @date	27/10/2014
 * @author	Albin CAUDERLIER
 */



/**
 * 1. Se connecte à la BdD
 * 2. Lance une requête
 * 3. Libère la connexion à la BdD
 * 
 * @param	$sql_base		Base SQL vers laquelle la requête doit être émise
 * @param	$sql_request	Le String contenant la requête
 * 
 * @return	$result			Le résultat de la requête sous forme d'un array (tableau)
 * 
 */
function mysql_myquery($sql_base, $sql_request){

	include_once 'lib/sql/config/config.php';
	
	$mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, $sql_base);
	
	// Vérification de la connexion
	if (mysqli_connect_errno()) {
		printf("Échec de la connexion : %s\n", mysqli_connect_error());
		exit();
	}
	
	// Execute la requête
	$sql_result = $mysqli->query($sql_request);
	
	// Libération de la connexion à la BdD
	$mysqli->close();
	
	return $sql_result;
}

/**
 * Transforme le résultat d'une requête en un tableau
 *
 * @param	$result			Le résultat de la requête
 */
function sql2table($sql_result){
	echo '<table border="1"><tr>';

	// Ligne de titres
	for($i = 0; $i < mysql_num_fields($sql_result); $i++){
		echo '<th>';
		echo mysql_field_name($sql_result, $i);
		echo '</th>';
	}
	echo '</tr>';
	
	// Lignes de contenu
	while ($row = mysql_fetch_row($sql_result)) {
		echo '<tr>';
	
		for($j = 0; $j < count($row); $j++){
			echo '<td>';
			echo ($row[$j] == NULL) ? '<i>NULL</i>' : $row[$j];
			echo '</td>';
		}
		echo '</tr>';
	}
}

?>