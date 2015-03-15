<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_getCheckFiger.php?milis=123456789&code_user=FRO&date_begin=2013-02-11&date_end=2013-02-17

//[0][0] = number

// IN obligatoire
$arrayGet = array(
    "code_user" => null,
    "date_begin" => null,
    "date_end" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
    // CONNECION SQL
    $db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

    // CONNECION BASE
    mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());
	
    //------------------------------------------------------------------
    //pourcent de saisi d activitee
    $sql = "SELECT count(*) as number
            FROM `tab_suivihebdo_jour_figer_user` 
            WHERE 1=1
            AND `date` between '".$arrayValeur["date_begin"]."' and '".$arrayValeur["date_end"]."'
            AND `code_user` = '".$arrayValeur["code_user"]."'	
    ;";

    $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 

    // on fait une boucle qui va faire un tour pour chaque enregistrement 
    while($data = mysql_fetch_assoc($req)) 
    { 
            $strSorti .= $data['number']
            ."&"
            ."\r\n"; 
    }

    // DECONNECION SQL
    mysql_close();
}else{
    $strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>