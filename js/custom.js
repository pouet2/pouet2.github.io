// global default value of elementID
var elementID = 'apiReturn';
var apiResp = {};

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
    var data = _data;
    var isHash = /^[0-9a-f]{64}$/.test(data);
    var isAddress = /^[0-9a-zA-Z]{34}$/.test(data);
    var isNb = /^[0-9]{1,6}$/g.test(data);

    if (isAddress == true) {
        return "isAddress";
    }

    if (isNb == true && data.length > 0 && data.length <= 6) {
        if (data.length < 6) { 
            for (var i = data.length; i < 6; i++) {
                data.slice(0, "0");
            }
        }
        return "isBlockNb";
    }

    if (isHash == true) {
        var isBlockHash = data.substring(0,6);
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
            //output(jsonPrettify(JSON.stringify(JSON.parse(this.responseText),null,2)), _elementID);
            var rep = JSON.stringify(JSON.parse(this.responseText),null,2);
            //console.log(rep);
            output(jsonPrettify(rep), _elementID);
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
            resp = /\d{6}/.exec(resp)[0];
            document.getElementById(_elementID).innerHTML = resp;
        }
    };

    request.open('GET', _url);
    request.send();
}


// Affiche le résultat du call api
function output(_json, _elementID) {
    // Récupérer les objet du DOM existant déjà
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
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        
        var cls = 'number';
        
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }

        if(cls != "key"){
            match = addNavigationLink(match, json);
        }
        
        return `<span class="${cls}">${match}</span>`;
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
            // récupération du segment présécent data dans _json
            var index = _json.indexOf(data);
            var test1 = _json.substr((index - 11), index);
            var test2 = _json.substr((index - 16), index);

            // Vérification que que ce sont les bons index de l'objet 
            // précent la data que l'on veut vérifier
            if (/("blocks":)|("height":)/.test(test1) || /("block_height":)/.test(test2)) {
                return `<a href="javascript:getBlockHeight('${data}')">"${data}"</a>`
            } else {
                return `"${data}"`;
            }
        
        } else if (test == "isTx") {
            return `<a href="javascript:getTx('${data}')">"${data}"</a>`
        
        } else if (test == "isAddress"){
            return `<a href="javascript:getAddress('${data}')">"${data}"</a>`
        }
    
}

// Ajout des datas incluse dans <head>
function addHeadSettings() {

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
        `<link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/>` +
        `<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">` +
        `<link rel="stylesheet" href="styles/design.css">`;

    var urlTest = /index/.test(window.location.href)
    if (urlTest == true) {
        headElements += `<title>Bitcoin Explorer</title>`;
    }
    document.getElementsByTagName('head')[0].innerHTML = headElements;
}

// Ajout de l'ensemble des datas html pour l'affichage de la page 
function homePageLoading() {

    document.getElementsByTagName('body')[0].innerHTML = '';

    var body = 
        `<nav class="navbar navbar-inverse navbar-fixed-top">` +
            `<div class="container">` +
                `<div class="navbar-header">` +
                    `<a href="index.html" class="navbar-brand" rel="home" title="">` +
                        `<img style="max-width:100px;" src="images/mubiz/mubiz-logo-white.png" alt="Mubiz logo">` +
        `</a></div></div></nav>` +
        `<div class="container" >` +
            `<div class="jumbotron">` +
                `<h1>Bitcoin explorer</h1>` +
                `<p class="text-center">Faites vos recherches sur la blockchain Bitcoin!</p>` +
            `</div>` +
            `<div class="row">` +
                `<div class="col-lg-2">` +
                    `<p>Actual block : </p>` +
                `</div>` +
                `<div id="actualBlockHeight" class="col-lg-1"> ` +
            `</div></div>` +
            `<div class="row">` +
                `<div class="col-lg-12">` +
                    `<form>` +
                        `<div class="form-group">` +
                            `<input type="text" class="form-control" id="researchInput" placeholder="Block Hash, block Height, Tx Hash or BTC address">` +
                        `</div>` +
                        `<button type=button class="btn btn-default" onclick="research('researchInput');">Submit</button>` +
                    `</form>` +
                    `<div id="apiReturn">` +
                        `<div class="row">` +
                            `<div class="col-lg-6">` +
                                `<h3>Infos</h3>` +
                                `<div id="infos"></div>` +
                            `</div>` +
                            `<div class="col-lg-6">` +
                                `<h3>Mining infos</h3>` +
                                `<div id="miningInfos"></div>` +
                        `</div></div>` +
                        `<div class="row">` +
                            `<div class="col-lg-6">` +
                                `<h3>Blockchain infos</h3>` +
                                `<div id="blockchainInfos"></div>` +
                            `</div>` +
                            `<div class="col-lg-6">` +
                                `<h3>Peers infos</h3>` +
                                `<div id="peerInfo"></div>` +
        `</div></div></div></div></div></div>` +
        `<footer class="footer">` +
            `<div class="container">` +
                `<ul>` +
                    `<li><a href="">Github</a></li>` +
                    `<li><a href="">Link</a></li>` +
                `</ul>` +
                `<p>Done by Tulsene for fun, fell free</p>` +
        `</div></footer>` +
        `<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>` +
        `<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>` +
        `<script src="js/custom.js"></script>`;

    document.getElementsByTagName('body')[0].innerHTML = body;

    apiGetUgly('http://bitcoin.mubiz.com/blocks', 'actualBlockHeight');
    prettyApiGet('https://api.blockcypher.com/v1/btc/main', 'infos');
    prettyApiGet('http://bitcoin.mubiz.com/blockchaininfo', 'blockchainInfos');
    prettyApiGet('http://bitcoin.mubiz.com/mininginfo', 'miningInfos');
    prettyApiGet('http://bitcoin.mubiz.com/peerinfo', 'peerInfo');
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
    addEvent(window , "load", homePageLoading);

    function lancer(fct) {
        addEvent(window, "load", fct);
    }

}

main();

