// global default value of elementID
var elementID = 'apiReturn';

// Déclenché quand researchInput est entré.
function research(_formInput){
    var blockHeightNb = document.getElementById('actualBlockHeight').textContent;
    var formInput = document.getElementById(_formInput).value;
    var test = identifyDataType(formInput);

    if(test == false){
        document.getElementById(elementID).innerHTML = "veuillez entrer une Tx, une addresse, un hash de block ou transaction valide";
    } else if(test == "isBlockHash"){
        getBlockHash(formInput, elementID);
    } else if (test == "isTx") {
        getTx(formInput, elementID);
    } else if (test == "isAddress"){
        getAddress(formInput, elementID);
    } else if (test == "isBlockNb"){
        if (formInput > blockHeightNb) {
            document.getElementById(elementID).innerHTML = 'veuillez entrer un N° de block valide';
        } else {
            getBlockHeight(formInput, elementID);
        }
    }
}

// Retourne le type de data entré ou retourne false
function identifyDataType(_data){
    var isHash = /^[0-9a-f]{64}$/.test(_data);
    var isAddress = /^[1-9a-km-zA-HJ-NP-Z^]{34}$/.test(_data);
    var isNb = /^[0-9]{1,6}$/g.test(_data);

    if (isAddress == true) {
        return "isAddress";
    }

    if (isNb == true && _data.length > 0 && _data.length <= 6) {
        if (_data.length < 6) { 
            for (var i = _data.length; i < 6; i++) {
                _data.slice(0, "0");
            }
        }
        return "isBlockNb";
    }

    if (isHash == true) {
        var isBlockHash = _data.substring(0,6);

        if (isBlockHash == "000000"){
            return "isBlockHash"; 
        } else {
            return "isTx";
        }
    }

    return false;
}

// Prépare l'url pour les call d'api
function getBlockHash(_blockHash) {
    var url = "https://api.blockcypher.com/v1/btc/main/blocks/" + _blockHash;
    prettyApiGet(url, elementID);
}

function getBlockHeight(_blockHeight){
    var url = "https://api.blockcypher.com/v1/btc/main/blocks/" + _blockHeight;
    prettyApiGet(url, elementID);
}

function getAddress(_address){
    var url = "https://api.blockcypher.com/v1/btc/main/addrs/" + _address;
    prettyApiGet(url, elementID);
}

function getTx(_tx){
    var url = "https://api.blockcypher.com/v1/btc/main/txs/" + _tx;
    prettyApiGet(url, elementID);
}

// Execute le call api avec modification de l'output
function prettyApiGet(_url, _elementID) {
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Affiche le JSON retourné par le serveur
            output(jsonPrettify(JSON.stringify(JSON.parse(this.responseText),null,2)), _elementID);
        }
    };

    request.open('GET', _url);
    request.send();
}

// Pour récupérer le N° de block actuel et l'afficher sur la page 
function apiGetUgly(_url, _elementID) {
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resp = JSON.stringify(JSON.parse(this.responseText),null,2);
            // valeur à rechercher dans la réponse de l'api
            var target = 'height';
            var index = resp.indexOf(target);
            resp = '<p>' + resp.slice((index + 9), (index + 15)) + '</p>' ;
            document.getElementById(_elementID).innerHTML = resp;
        }
    };

    request.open('GET', _url);
    request.send();
}

// Affiche le résultat du call api
function output(_json, _elementID) {
    // Récupérer les objet du DOM existant déjà pour les retirer
    var element = document.getElementById(_elementID);

    while (element.firstChild) {
        element.removeChild(element.firstChild);
    }

    // Ajoute le json dans le DOM
    document.getElementById(_elementID).appendChild(document.createElement('pre')).innerHTML = _json;
}

// Améliore le rendu du call api
function jsonPrettify(_json) {
    var json = _json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (_match) {
        
        var cls = 'number';
        
        if (/^"/.test(_match)) {
            if (/:$/.test(_match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(_match)) {
            cls = 'boolean';
        } else if (/null/.test(_match)) {
            cls = 'null';
        }

        if(cls != "key"){
            _match = addNavigationLink(_match, json);
        }
        
        return `<span class="${cls}">${_match}</span>`;
    });
}

// Ajoute des liens de navigation au rendu du call api
function addNavigationLink(_data, _json) {
    var data = _data.replace(/"/g, "");
    var test = identifyDataType(data);


        if (test == false) {
            return `"${data}"`
        
        } else if (test == "isBlockHash"){
                return `<a href="javascript:getBlockHash('${data}')">"${data}"</a>`
        
        } else if (test == "isBlockNb"){
            // récupération de segments présécent data dans _json
            var index = _json.indexOf(data);
            var test1 = _json.substr((index - 15), index);
            var test2 = _json.substr((index - 11), index);
            var test3 = _json.substr((index - 16), index);

            // Vérification que que ce sont les bons mots clés
            // précent la data que l'on veut vérifier
            if (/("nb":)/.test(test1) |/("blocks":)|("height":)/.test(test2) || /("block_height":)/.test(test3)) {
                return `<a href="javascript:getBlockHeight('${data}')">"${data}"</a>`
            } else {
                return data;
            }
        
        } else if (test == "isTx") {
            return `<a href="javascript:getTx('${data}')">"${data}"</a>`
        
        } else if (test == "isAddress"){
            return `<a href="javascript:getAddress('${data}')">"${data}"</a>`
        }
}

// Permet de détecter l'url du site
function routing() {
    var isHomePage =  /github.io\/index.html/.test(window.location.href);
    var isExplorer = /github.io\/explorer/.test(window.location.href);
    var isBlog = /github.io\/blog/.test(window.location.href);
    var resp = [];

    if (isHomePage == true) {
        resp = ['isHomePage', ''];
        return resp;
    } else if (isExplorer == true) {
        resp = ['isExplorer', '../'];
        return resp;
    } else if (isBlog == true) {
        resp = ['isBlog', '../'];
        return resp;
    }

    return false;
}

// Ajout des datas du <head>
function addHeadSettings() {
    var route = routing();

    var headElements = `<meta charset="UTF-8" />` + 
        `<meta name="description"    content="A bitcoin block explorer full js client version"/>` +
        `<meta name="keywords"       content="bitcoin block-explorer block explorer transaction blockchain hash putabot"/>` +
        `<meta name="robots"         content="index,follow"/>` +
        `<meta name="rating"         content="safe for kids"/>` +
        `<meta name="revisit-after"  content="14 days"/>` +
        `<meta name="author"         content="Tulsene"/>` +
        `<meta name="geo.placename"  content="France"/>` +
        `<meta property="og:type"       content="website"/>` +
        `<meta property="og:site_name"  content="GitHub - Tulsene"/>` +
        `<meta property="og:locale"     content="fr_FR"/>` +
        `<link rel="shortcut icon" type="image/ico" href="${route[1]}images/favicon.ico"/>` +
        `<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">` +
        `<link rel="stylesheet" href="${route[1]}styles/design.css">`;
 
    if (route[0] == 'isHomePage') {
        headElements += `<title>Bitcoin Explorer</title>`;
    } else if (route[0] == 'isExplorer') {
        headElements += `<title>Bitcoin Explorer</title>`;
    } else if (route[0] == 'isBlog') {
        headElements += `<title>Bitcoin Explorer</title>`;
    } 

    document.getElementsByTagName('head')[0].innerHTML = headElements;
}

// Générateur de la navbar
function navBlockGenerator(_route) {
    if (_route[0] == 'isHomePage') {
        var isActive =['class="active"','',''];
    } else if (_route[0] == 'isExplorer') {
        var isActive = ['','class="active"',''];
    } else if (_route[0] == 'isBlog') {
        var isActive = ['','','class="active"'];
    } else {
        return false;
    }

    var navBlock = 
        `<nav class="navbar navbar-inverse navbar-fixed-top">` +
          `<div class="container">` +
            `<div class="navbar-header">` +
              `<a href="${_route[1]}index.html" class="navbar-brand" rel="home" title="">` +
                `<img style="max-width:100px;" src="${_route[1]}images/mubiz/mubiz-logo-white.png" alt="Mubiz logo">` +
            `</a></div>` +
            `<div id="navbar" class="collapse navbar-collapse">` +
              `<ul class="nav navbar-nav">` +
                `<li ${isActive[0]}>` +
                  `<a href="${_route[1]}index.html">Home</a>` +
                `</li>` +
                `<li ${isActive[1]}>` +
                  `<a href="${_route[1]}explorer/index.html">Block Explorer</a>` +
                `</li>` +
                `<li ${isActive[2]}>` +
                  `<a href="${_route[1]}blog/index.html">Blog</a>` +
                `</li>` +
        `</ul></div></div></nav>`;

    document.getElementById('navBlock').innerHTML = navBlock;

    return true;
}

// Générateur des premiers éléments html du body
function bodyElementsGenerator(_route) {
    if (_route[0] == 'isHomePage') {
        var bodyElements =
            `<p class="test-center">Bienvenue à la maison</p>`
    } else if (_route[0] == 'isExplorer') {
        var bodyElements =
            `<div class="container" >` +
              `<h1>Bitcoin explorer</h1>` +
              `<h3 class="text-center">Faites vos recherches sur la blockchain Bitcoin!</h3>` +
              `<div class="row">` +
                `<div class="col-lg-2">` +
                  `<p>Actual block : </p>` +
                `</div>` +
                `<div id="actualBlockHeight" class="col-lg-1"> ` +
              `</div></div>` +
              `<div class="row">` +
                `<div class="col-lg-12">` +
                  `<form action="javascript:research('researchInput');">` +
                    `<input type="text" class="form-control" id="researchInput" placeholder="Block Hash, block Height, Tx Hash or BTC address">` +
                      `<button type="submit" class="btn btn-default" id="formBtn">Submit</button>` +
                  `</form>` +
                  `<div id="apiReturn">` +
                    `<div class="row">` +
                      `<div class="col-lg-6">` +
                        `<h3>Infos</h3>` +
                        `<div id="infos"></div>` +
                      `</div>` +
                      `<div class="col-lg-6">` +
                        `<h3>Blockchain infos</h3>` +
                        `<div id="blockchainInfos"></div>` +
                `</div></div></div></div></div></div>` ;
    } else if (_route[0] == 'isBlog') {
        var bodyElements =
            `<p class="test-center">Rien ici pour le moment</p>`
    } else {
        return false;
    }

    document.getElementById('bodyContent').innerHTML = bodyElements;
    
    return true;
}

// Generateur de contenu du body des pages
function bodyContentGenerator(_route) {
    if (_route[0] == 'isHomePage') {
        var isActive =['class="active"','',''];
    } else if (_route[0] == 'isExplorer') {
        apiGetUgly('https://api.blockcypher.com/v1/btc/main', 'actualBlockHeight');
        prettyApiGet('https://api.blockcypher.com/v1/btc/main', 'infos');
        prettyApiGet('https://btc.blockr.io/api/v1/coin/info', 'blockchainInfos');
    } else if (_route[0] == 'isBlog') {
        var isActive = ['','','class="active"'];
    } else {
        return false;
    }
}

// Generateur du footer
function footerBlockGenerator(_route) {
    var footerBlock = 
    `<footer class="footer">` +
            `<div class="container">` +
                `<ul>` +
                    `<li><a href="">Github</a></li>` +
                    `<li><a href="">Link</a></li>` +
                `</ul>` +
                `<p>Done by Tulsene for fun, fell free</p>` +
        `</div></footer>`;

    document.getElementById('footerBlock').innerHTML = footerBlock;

    return true;
}

// Generateur de page html
function pageGenerator() {
    var route = routing();

    var body = 
        `<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>` +
        `<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>`;

    document.getElementsByTagName('body')[0].innerHTML += body;

    // Récupérer les objet du DOM existant déjà pour les retirer
    var elmt = document.getElementById('navBlock');
    
    while (elmt.firstChild) {
        elmt.removeChild(elmt.firstChild);
    }
    
    navBlockGenerator(route);


    var elmt = document.getElementById('bodyContent');
    while (elmt.firstChild) {
        elmt.removeChild(elmt.firstChild);
    }

    bodyElementsGenerator(route);

    bodyContentGenerator(route);
    

    var elmt = document.getElementById('footerBlock');
    while (elmt.firstChild) {
        elmt.removeChild(elmt.firstChild);
    }

    footerBlockGenerator(route);
}

function main() {
// https://openclassrooms.com/courses/executer-plusieurs-fonctions-au-chargement-d-une-page
// element.addEventListener("événement_sans_on_devant", fonction_a_executer, propagation_evenement_(booleen));
// element.attachEvent("évènement", fonction); // implémentation de microsoft
    function addEvent(obj, event, fct) {
        if (obj.attachEvent) //Est-ce IE ?
            obj.attachEvent("on" + event, fct); //Ne pas oublier le "on"
        else
            obj.addEventListener(event, fct, true);
    }

    // Les fonction à exécuter au chargement de la page
    addEvent(window , "load", addHeadSettings);
    addEvent(window , "load", pageGenerator);

    function start(fct) {
        addEvent(window, "load", fct);
    }

}

main();

