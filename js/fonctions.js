///////////////////
//BLOCK FONCTIONS TECHNIQUES
///////////////////

///////////////////
//BLOCK FONCTIONS METIER
///////////////////
//function getUsersForListe(p_id_liste) 

///////////////////
//BLOCK FONCTIONS AJAX JS/PHP/MYSQL
///////////////////
//function getUsersInListe(p_id_listes_users)
//function getListesUsersByPropri(p_code_user)

///////////////////
//BLOCK FONCTIONS PRESENTATION
///////////////////
//function getHtmlSuiviKO(p_user)
//function inputMoisEnCoursRH(p_champs1, p_champs2)

////////////////////////////////////////////////////////////////////

///////////////////
//BLOCK VARIABLE GLOBAL
///////////////////

///////////////////
//BLOCK FONCTIONS TECHNIQUES
///////////////////

///////////////////
//BLOCK FONCTIONS METIER
///////////////////
function getUsersForListe(p_id_liste) {
    try {
        var strRetour = "";
        
        var tabFiltreUsers = new Array();
        //[X][0] = code_user
        //[X][1] = nom
        //[X][2] = prenom
        //[X][3] = selected
        tabFiltreUsers = getUsersInListe(p_id_liste);
        var strFiltreUsers = "";
        for (var indice in tabFiltreUsers) {
            if(tabFiltreUsers[indice][3] == "1"){
                strFiltreUsers += "'"+tabFiltreUsers[indice][0]+"',";
            }
        }

        strFiltreUsers = strFiltreUsers.substring(0,strFiltreUsers.length-1);

        strRetour = strFiltreUsers;
		
        return strRetour;
    }
    catch (er) {
        log(0, "ERROR(getUsersForListe):" + er.message);
        return null;
    }
}

///////////////////
//BLOCK FONCTIONS AJAX JS/PHP/MYSQL
///////////////////
//[X][0] = code_user
//[X][1] = nom
//[X][2] = prenom
//[X][3] = selected
function getUsersInListe(p_id_listes_users) {
    try {
        var strResponse = "";
        var returns = new Array();
        var tempTab = new Array();
        var tempSubTab = new Array();

        xhr_object = new AJ();

        var url = "API_RH/phpsql/mysql_getUsersInListes.php?milis="+getMilise()+"&id_listes_users=" + p_id_listes_users;

        xhr_object.open("GET", url, false);
        xhr_object.send(null);
        if (xhr_object.readyState == 4) {
            strResponse = xhr_object.responseText;
        } else {
            strResponse = "ERROR";
        }

        tempTab = strResponse.split("\n");

        for (var indice in tempTab) {
            tempSubTab = tempTab[indice].split("&");
            if (tempSubTab.length > 1) {
                returns[returns.length] = tempSubTab.slice(0, tempSubTab.length - 1);
            }
        }

        delete xhr_object;

        return returns;
    }
    catch (er) {
        var msg = "ERROR(getListesUsersByPropri):" + er.message;
        log(0, msg);
        return msg;
    } finally {
        delete xhr_object;
    }
}

//[X][0] = id
//[X][1] = titre
//[X][2] = nb
function getListesUsersByPropri(p_code_user) {
	try {
		var strResponse = "";
		var returns = new Array();
		var tempTab = new Array();
		var tempSubTab = new Array();

		xhr_object = new AJ();
		
		var url = "API_RH/phpsql/mysql_getListesUsersByPropri.php?milis="+getMilise()+"&code_user=" + p_code_user;

		xhr_object.open("GET", url, false);
		xhr_object.send(null);
		if (xhr_object.readyState == 4) {
			strResponse = xhr_object.responseText;
		} else {
			strResponse = "ERROR";
		}

		tempTab = strResponse.split("\n");

		for (var indice in tempTab) {
			tempSubTab = tempTab[indice].split("&");
			if (tempSubTab.length > 1) {
				returns[returns.length] = tempSubTab.slice(0, tempSubTab.length - 1);
			}
		}

		delete xhr_object;

		return returns;
	}
	catch (er) {
		var msg = "ERROR(getListesUsersByPropri):" + er.message;
		log(0, msg);
		return msg;
	} finally {
		delete xhr_object;
	}
}

function getTabRapportRHuser(p_user) {
	try {
		var strResponse = "";
		var returns = new Array();
		var tempTab = new Array();
		var tempSubTab = new Array();

		xhr_object = new AJ();

		var url = "API_RH/phpsql/mysql_getDataRapportRH2User.php?milis=" + getMilise() + "&user=" + p_user;

		xhr_object.open("GET", url, false);
		xhr_object.send(null);
		if (xhr_object.readyState == 4) {
			strResponse = xhr_object.responseText;
		} else {
			strResponse = "ERROR";
		}

		tempTab = strResponse.split("\n");

		for (var indice in tempTab) {
			tempSubTab = tempTab[indice].split("&");
			if (tempSubTab.length > 1) {
				returns[returns.length] = tempSubTab.slice(0, tempSubTab.length - 1);
			}
		}

		delete xhr_object;

		return returns;
	}
	catch (er) {
		log(0, "ERROR(getTabRapportRHuser):" + er.message);
	}
}

///////////////////
//BLOCK FONCTIONS PRESENTATION
///////////////////
function getHtmlSuiviKO(p_user) {
	try {
		var tab = new Array();
		tab = getTabRapportRHuser(p_user);
		//[X][0] = date
		//[X][1] = responsable
		//[X][2] = total
		
		var strRetour = "";
		
		if(tab.length > 0){
					
			strRetour += "<center>";
			strRetour += "<br>";
			strRetour += "<img border=0 src=\"img/mini_attention.png\" /> Vous n'avez pas rempli enti&egrave;rement votre suivi hebdomadaire.";
			strRetour += "<br>";
			
			strRetour += "			<table cellpadding=\"0\" cellspacing=\"0\" border=1 style=\"font-size:14px;background-color:white;\">";
			
			strRespon = "";
			
			strRetour += "				<tr>";
			strRetour += "					<td style=\"width: 100px;text-align:center;\">";
			strRetour += "						Responsable";
			strRetour += "					</td>";
			strRetour += "					<td style=\"width: 150px;text-align:center;\">";
			strRetour += "						Non compl&eacute;t&eacute;";
			strRetour += "					</td>";
			
			for (var indice in tab) {
				if(strRespon != tab[indice][1]){
					strRetour += "					</td>";
					strRetour += "				</tr>";
					strRespon = tab[indice][1];
					strRetour += "				<tr>";
					strRetour += "					<td style=\"width: 100px;text-align:center;\">";
					strRetour += "						"+tab[indice][1]+"";
					strRetour += "					</td>";
					strRetour += "					<td style=\"width: 150px;text-align:center;font-size:10px;\">";
				}
				strRetour += "				"+tab[indice][0]+"("+tab[indice][2]+")<br>";
			}
			strRetour += "					</td>";
			strRetour += "				</tr>";
			strRetour += "			</table>";
			strRetour += "</center>";
			
		}
		

        return strRetour;
	}
	catch (er) {
		log(0, "ERROR(getHtmlSuiviKO):" + er.message);
	}
}

function inputMoisEnCoursRH(p_champs1, p_champs2) {
	try {
	
		var element1 = document.getElementById(p_champs1);
		var element2 = document.getElementById(p_champs2);
		
		var d = new Date();
		var dm = d.getMonth() + 1;
		var dan = d.getYear();
		var dd = d.getDate();
		if(dan < 999) dan+=1900;
		var nbjour = daysInMonth(dm,dan);
		if(dd == 1) {dd = 1;} else {dd -= 1;}
		
		element1.value = "01/"+pad2(dm)+"/"+dan;
		element2.value = pad2(dd)+"/"+pad2(dm)+"/"+dan;
		
	}
	catch (er) {
		log(0, "ERROR(inputMoisEnCours):" + er.message);
	}
}