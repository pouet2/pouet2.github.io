/**
 * 
 * 
 * E-mProvement - (C) 2014
 * 
 * Albin CAUDERLIER <albin.cauderlier@e-mprovement.com>
 * 
 */



/**
 * 
 * 
 * 
 */
function getElementbyClass(classname)
{
	var inc=0;
	var elt=new Array();
	var alltags=document.all? document.all : document.getElementsByTagName("*");
	
	for (i=0; i<alltags.length; i++)
	{
		if (alltags[i].className==classname)
		elt.unshift(alltags[i]);
	}
	return elt;
}	



/**
 * 
 * 
 */
function getobj(id)
{
	if(document.layers)
		return document.id; // Netscape 4.x

	if(document.getElementById)
		return document.getElementById(id); // Netscape 6.x IE 5.x

	if(document.all)	
		return id; // IE 4.x
}


/**
 * 
 * 
 * 
 */
function hideAll(bid)
{
	var sections=getElementbyClass(bid);
      	
	for(var i=0;i<sections.length;i++){
      		sections[i].style.display="none";
	}
}

/**
 * Appelé par chaque lien #, avec la fonction HTML onclick()
 *
 * Ex : <li><a href="#" onclick="show('Parameters')">Param�tres</a>
 */
function show(section)
{
	// Retire de l'affichage tous les autres class="theme" existants
      	hideAll("theme");

	// R�cup�re l'objet correspondant � la nouvelle page.
      	var elt=getobj(section);

	// Affiche la div ayant id="section" demand�e.
      	elt.style.display="block";
}

/**
 * 
 * 
 */
function aff()
{
	hideAll("cit");
	var index_cit=Math.round(12*Math.random())+1;
	var elt=getobj(index_cit);
	elt.style.display="block";
}

