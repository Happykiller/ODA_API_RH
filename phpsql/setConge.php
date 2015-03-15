<?php
//Config : Les informations personnels de l'instance (log, pass, etc)
require("../../include/config.php");

//API Fonctions : les fonctions fournis de base par l'API
require("../../API/php/fonctions.php");

//Header établie la connection à la base $connection
require("../../API/php/header.php");

//Mode debug
$modeDebug = false;

//Public ou privé (clé obligatoire)
$modePublic = true;

//Liens de test
// phpsql/setConge.php?milis=123450&ctrl=ok&code_user=FRO&annee=2014&mois=01&jour=01&am_ap=am&type=CP

//Définition des entrants
$arrayGet = array(
    "ctrl" => null,
    "code_user" => null,
    "annee" => null,
    "mois" => null,
    "jour" => null,
    "am_ap" => null,
    "type" => null
);

//Récupération des entrants
$arrayValeur = recupInput($arrayGet, $bolDecode);

//Vérification sécurité
$retourCheckKey = checkKey($connectionAuth, $prefixTable, $arrayValeur, $modePublic);

if($arrayValeur["error"] == null){
    //Si pas d'erreur
    $object_retour = new stdClass();
    $object_retour->strErreur = "";
    $object_retour->data = "";
    
    //--------------------------------------------------------------------------
    $strSql = "Select *
        FROM `".$prefixTable."api_rh_tab_enreg_conges` a
        WHERE 1=1
        AND a.`code_user` = '".$arrayValeur["code_user"]."'
        AND a.`date` = '".$arrayValeur["annee"]."-".$arrayValeur["mois"]."-".$arrayValeur["jour"]."'
        AND a.`am_ap` = '".$arrayValeur["am_ap"]."'
        AND a.`actif` = 1
    ";

    // On envois la requète
    $select = $connection->query($strSql) or die('Erreur SQL !'.$strSql.'<br>'.print_r($connection->errorInfo(), true));

    // On indique que nous utiliserons les résultats en tant qu'objet
    $select->setFetchMode(PDO::FETCH_OBJ);

    // On transforme les résultats en tableaux d'objet
    $resultats = new stdClass();
    $resultats->data = $select->fetchAll(PDO::FETCH_OBJ);
    $resultats->nombre = count($resultats->data);
    
    $erase = false;
    if($resultats->nombre != 0){
        $strSql = "UPDATE `".$prefixTable."api_rh_tab_enreg_conges`
            SET `actif` = 0,
                `dateSupp` = NOW()
            WHERE 1=1
            AND `code_user` = '".$arrayValeur["code_user"]."'
            AND `date` = '".$arrayValeur["annee"]."-".$arrayValeur["mois"]."-".$arrayValeur["jour"]."'
            AND `am_ap` = '".$arrayValeur["am_ap"]."'
            AND `actif` = 1
        ;";
        $req = $connection->prepare($strSql);
        $req->execute() or die('Erreur SQL:'.print_r($req->errorInfo(), true)." (".$strSql.")");
        $req->closeCursor();
        
        if($arrayValeur["type"] == $resultats->data[0]->type){
            $erase = true;
        }
    }
    
    if(!$erase){
        $strSql = "INSERT INTO  `".$prefixTable."api_rh_tab_enreg_conges` (
                `code_user` ,
                `date` ,
                `type` ,
                `am_ap` ,
                `dateCreation` ,
                `statut` ,
                `actif` 
            )
            VALUES (
                :code_user , :date , :type , :am_ap , NOW() ,  'P' ,  :actif
            )
        ;";

        $req = $connection->prepare($strSql);
        $req->bindValue(":code_user", $arrayValeur["code_user"], PDO::PARAM_STR);
        $date = $arrayValeur["annee"]."-".$arrayValeur["mois"]."-".$arrayValeur["jour"];
        $req->bindValue(":date", $date, PDO::PARAM_STR);
        $req->bindValue(":type", $arrayValeur["type"], PDO::PARAM_STR);
        $req->bindValue(":am_ap", $arrayValeur["am_ap"], PDO::PARAM_STR);
        $req->bindValue(":actif", 1, PDO::PARAM_INT);

        $resultat = new stdClass();
        if($req->execute()){
            $resultat->id = $connection->lastInsertId(); 
            $resultat->annee = $arrayValeur["annee"];
            $resultat->mois= $arrayValeur["mois"];
            $resultat->jour= $arrayValeur["jour"];
            $resultat->am_ap= $arrayValeur["am_ap"];
            $resultat->type= $arrayValeur["type"];
        }else{
            die('Erreur SQL:'.print_r($req->errorInfo(), true)." (".$strSql.")");
        }
        $req->closeCursor();
        $object_retour->data["resultat"] = $resultat;
    }else{
        $resultat = new stdClass();
        $resultat->id = 0; 
        $resultat->annee = $arrayValeur["annee"];
        $resultat->mois = $arrayValeur["mois"];
        $resultat->jour = $arrayValeur["jour"];
        $resultat->am_ap = $arrayValeur["am_ap"];
        $resultat->type = "vide";
        $object_retour->data["resultat"] = $resultat;
    }
    
    //--------------------------------------------------------------------------
    $resultats_json = json_encode($object_retour);
    
    $strSorti = $resultats_json;

    if($modeDebug){
        $strSorti .= ($strSql);
    }
}else{
    //Problème sur les entrants
    $strSorti = "ERROR:".$arrayValeur["error"];
}

//Cloture de l'interface
require("../../API/php/footer.php");
?>