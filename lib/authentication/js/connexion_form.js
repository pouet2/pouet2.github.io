



/***************************
 * 
 * 		   ESTHETIQUE
 * 
 ***************************/

function EMail_onBlur(form){
	
	
}


function Password_onBlur(form){
	
	
}





/****************************
 * 
 * 		CONTROLE DES CHAMPS
 * 
 *****************************/

/**
 * TODO : Raisonner par contrainte que doit respecter chaque champs + contrôle de chaque contrainte.
 * Cela évitera de faire trop de fonctions !
 */



/*
 * Modifie le fond du champ si la valeur est erronée.
 * Retire la couleur s'il est correctement renseigné.
 * 
 */
function surligne(champ, erreur)
{
	//Met un fond de couleur
	if(erreur)
      champ.style.backgroundColor = "#fba";

   //Retire le fond de couleur
   else
      champ.style.backgroundColor = "";
}


/**
 * 
 */
function verifMail(champ)
{
   var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
   if(!regex.test(champ.value))
   {
      surligne(champ, true);
      return false;
   }
   else
   {
      surligne(champ, false);
      return true;
   }
}



/**
 * 
 */
function verifPassword(champ)
{
	
	return true;
}





/****************************
 * 
 *   CONTROLE DU FORMULAIRE
 * 
 ******************************/
function verifConnexionForm(f)
{
	var Mail_OK 	= verifMail(f.email);
	var Password_OK = verifPassword(f.password);
	
	if(Mail_OK && Password_OK)
	{
		return true;
	}
	else
	{
		alert("Veuillez remplir correctement tous les champs");
		return false;
	}
}
