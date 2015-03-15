<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

$modeDebug = FALSE;

// API_RH/phpsql/mysql_getDataRapportRH4.php?milis=123456789&dateBegin=02122013&dateEnd=02122013&filtreUsers=

//[X][0] = type
//[X][1] = description_type
//[X][2] = phase
//[X][3] = description_phase
//[X][4] = group
//[X][5] = nb
//[X][6] = user
//[X][7] = nb

// IN obligatoire
$arrayGet = array(
    "dateBegin" => null,
    "dateEnd" => null,
    "filtreUsers" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());
	
	//------------------------------------------------------------------
	$sql = "
		SELECT DISTINCT 
                    a.type, 
                    a.phase, 
                    (select c.description from tab_suivihebdo_type_activite c where c.id=a.type) as description_type, 
                    (select IFNULL(d.description,'') from tab_suivihebdo_type_phase d where d.id=a.phase) as description_phase, 
                    b.ordre, 
                    b.group
		FROM (
                    SELECT type, phase, date_saisi FROM tab_suivihebdo_enreg 
                    UNION 
                    SELECT type, '0' as phase, '0000-00-00' as date_saisi FROM tab_suivihebdo_enreg
                ) a, tab_suivihebdo_param_ordre_rapport4 b
		WHERE 1=1
		AND a.type = b.type
		AND a.type != 2
		AND ((a.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')) or a.`date_saisi` = '0000-00-00')
		ORDER BY b.ordre, a.phase
	;";
		
	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 		
		$ligne = $data['type']."&".$data['description_type']."&".$data['phase']."&".$data['description_phase']."&".$data['group']."&";
		
		$cond_phase = "";
		if($data['phase'] == "0"){
			$cond_phase = "";
		}else{
			$cond_phase = "AND c.phase = ".$data['phase'];
		}
		
		$sql2 = "
			SELECT IFNULL(SUM(charge),0) as nb
			FROM tab_suivihebdo_enreg c
			WHERE 1=1
			AND c.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')
			AND c.type = ".$data['type']."
			".$cond_phase."
		;";
			
		$req2 = mysql_query($sql2) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error()); 
		
		// on fait une boucle qui va faire un tour pour chaque enregistrement 
		while($data2 = mysql_fetch_assoc($req2)) 
		{
			$ligne .= $data2['nb']."&";
		}
		
		if ($arrayValeur["filtreUsers"] != ""){
			$strFiltreUsers = "AND a.user in (".$arrayValeur["filtreUsers"].")";
		}else{
			$strFiltreUsers = "";
		}
		
		$sql3 = "
			SELECT DISTINCT UPPER(a.user) user
			FROM tab_suivihebdo_enreg a
			WHERE 1=1
			".$strFiltreUsers."
			AND a.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')
			ORDER BY a.user
		;";
			
		$req2 = mysql_query($sql3) or die('Erreur SQL !<br>'.$sql2.'<br>'.mysql_error()); 
		
		// on fait une boucle qui va faire un tour pour chaque enregistrement 
		while($data2 = mysql_fetch_assoc($req2)) 
		{ 
			if ($arrayValeur["filtreUsers"] != ""){
				$strFiltreUsers = "AND c.user in (".$arrayValeur["filtreUsers"].")";
			}else{
				$strFiltreUsers = "";
			}
			
			$sql4 = "
				SELECT IFNULL(SUM(charge),0) as nb
				FROM tab_suivihebdo_enreg c
				WHERE 1=1
				".$strFiltreUsers."
				AND c.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')
				AND UPPER(c.user) = '".$data2['user']."'
				AND c.type = ".$data['type']."
				".$cond_phase."
			;";
				
			$req3 = mysql_query($sql4) or die('Erreur SQL !<br>'.$sql3.'<br>'.mysql_error()); 
			
			$ligneTemp = $data2['user']."&0&";
			
			// on fait une boucle qui va faire un tour pour chaque enregistrement 
			while($data3 = mysql_fetch_assoc($req3)) 
			{ 
				$ligneTemp = $data2['user']."&".$data3['nb']."&";
			} 
			
			$ligne .= $ligneTemp;
		}
		
		$strSorti .= $ligne
		."\r\n";
	}

	// DECONNECION SQL
	mysql_close();
	
	if($modeDebug){
		$strSorti .= ('<br><br><br><br>'.$sql);
		$strSorti .= ('<br><br><br><br>'.$sql2);
		$strSorti .= ('<br><br><br><br>'.$sql3);
		$strSorti .= ('<br><br><br><br>'.$sql4);
	}
}else{
	$strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>