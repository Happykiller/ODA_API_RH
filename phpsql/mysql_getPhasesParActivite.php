<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_getPhasesParActivite.php?milis=123450&ctrl=ok

//[0][0] = 

// IN obligatoire
$arrayGet = array(
    "ctrl" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());

	$sql = "select a.id as id_domaine, a.description desc_domaine, b.id_sous_domaine, b.id as id_phase, b.description desc_phase
	FROM tab_suivihebdo_type_activite a
		LEFT JOIN tab_suivihebdo_type_phase b
		ON a.id_sous_domaine = b.id_sous_domaine 
	order by id_domaine asc, b.description asc
	;";

	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 
		$strSorti .= $data['id_domaine'].'&'.$data['desc_domaine'].'&'.$data['id_sous_domaine'].'&'.$data['id_phase'].'&'.$data['desc_phase']
		.'&'
		."\r\n";
	}

	// DECONNECION SQL
	mysql_close();
}else{
	$strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>