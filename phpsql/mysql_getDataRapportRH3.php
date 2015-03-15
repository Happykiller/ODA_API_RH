<?php 
require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

$modeDebug = false;

// API_RH/phpsql/mysql_getDataRapportRH3.php?milis=123456789&dateBegin=02122013&dateEnd=02122013&filtreUsers=

//[x][0] = description
//[x][1] = totalgroup
//[x][2] = total
//[x][3] = perc
//[x][4] = group

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
    if ($arrayValeur["filtreUsers"] != ""){
        $strFiltreUsers = "AND a.user in (".$arrayValeur["filtreUsers"].")";
    }else{
        $strFiltreUsers = "";
    }

    //pourcent de saisi d activitee
    $sql = "SELECT e.*, (e.totalgroup / e.total * 100) perc, e.`group`
        FROM (
            SELECT d.description, c.totalgroup, f.`group`, (
                SELECT SUM(a.charge) 
                FROM tab_suivihebdo_enreg a
                WHERE 1=1
                ".$strFiltreUsers."
                AND a.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')
            ) total
            FROM (
            SELECT a.type, SUM(a.charge) totalgroup
            FROM tab_suivihebdo_enreg a
            WHERE 1=1
            ".$strFiltreUsers."
            AND a.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')
            GROUP BY a.type
            ) c, tab_suivihebdo_type_activite d, tab_suivihebdo_param_ordre_rapport3 f
            WHERE 1=1
            AND c.type = d.id
            AND c.type = f.type
            AND c.type != 2
        ) e
        ORDER BY e.group, e.totalgroup desc
    ;";

    $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 

    // on fait une boucle qui va faire un tour pour chaque enregistrement 
    while($data = mysql_fetch_assoc($req)) 
    { 
        $strSorti .= $data['description']."&".$data['totalgroup']."&".$data['total']."&".$data['perc']."&".$data['group']
        ."&"
        ."\r\n"; 
    }

    // DECONNECION SQL
    mysql_close();
    
    if($modeDebug){
        $strSorti .= ($sql);
    }
}else{
    $strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>