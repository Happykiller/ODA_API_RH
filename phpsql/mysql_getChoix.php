<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_getChoix.php?milis=123456789&ctrl=ok

//[X][0] = code_user
//[X][1] = nom
//[X][2] = prénom

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

    //------------------------------------------------------------------
    //pourcent de saisi d activitee
    $sql = "SELECT a.code_user, UPPER(a.nom) as nom, a.prenom
        FROM `tab_utilisateurs` a
        WHERE 1=1
        AND a.`actif` = 1
        ORDER BY a.`nom` asc, a.`prenom` asc	
    ;";

    $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 

    // on fait une boucle qui va faire un tour pour chaque enregistrement 
    while($data = mysql_fetch_assoc($req)) 
    { 
        $strSorti .= $data['code_user']."&".$data['nom']."&".$data['prenom']
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