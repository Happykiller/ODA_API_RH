<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

$modeDebug = false;

// API/phpsql/mysql_getListesUsersByPropri.php?milis=123456789&code_user=FRO

//[X][0] = id
//[X][1] = titre
//[X][2] = nb

// IN obligatoire
$arrayGet = array(
    "code_user" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());
	
	$sql = "SELECT a.id, a.titre, (SELECT COUNT(*) FROM tab_listes_users_contenus b WHERE 1=1 AND a.id = b.id_listes_users) as nb
		FROM tab_listes_users a
		WHERE 1=1
		AND a.code_user = '".$arrayValeur["code_user"]."'
	;";

	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 
		$strSorti .= $data['id'].'&'.$data['titre'].'&'.$data['nb'].'&';
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