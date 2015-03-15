<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

$modeDebug = false;

// API/phpsql/mysql_getUsersInListes.php?milis=123456789&id_listes_users=1

//[X][0] = code_user
//[X][1] = nom
//[X][2] = prenom
//[X][3] = selected

// IN obligatoire
$arrayGet = array(
    "id_listes_users" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());
	
	$sql = "SELECT b.code_user, b.nom, b.prenom, (SELECT IFNULL(COUNT(*),0) FROM tab_listes_users_contenus a WHERE 1=1 AND a.id_listes_users = '".$arrayValeur["id_listes_users"]."'  AND a.code_user = b.code_user) as selected
		FROM `tab_utilisateurs` b
		WHERE 1=1
		ORDER BY selected desc, b.nom asc
	;";

	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 
		$strSorti .= $data['code_user'].'&'.$data['nom'].'&'.$data['prenom'].'&'.$data['selected'].'&';
		$strSorti .= "\r\n"; 
	}

	// DECONNECION SQL
	mysql_close();
	
	if($modeDebug){
		$strSorti .= ('<br><br><br><br>'.$sql);
	}
}else{
	$strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>