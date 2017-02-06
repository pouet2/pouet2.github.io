// Déclenché quand researchInput est entré.
function research(formInput){
    var data = document.getElementById(formInput).value;
    var test = identifyDataType(data);

    if(test == false){
        document.getElementById("apiReturn").innerHTML = "veuillez entrer une Tx, une addresse, un hash de block ou transaction valide";
    } else if(test == "isBlockHash"){
        getBlockHash(data);
    } else if (test == "isTx") {
        getTx(data);
    } else if(test == "isAddress"){
        getAddress(data);
    } else if(test == "isBlockNb"){
        getBlockHeight(data);
    }
}

// Retourne le type de data entré ou retourne false
function identifyDataType(data){
    var isHash = /^[0-9a-f]{64}$/.test(data);
    var isAddress = /^[0-9a-zA-Z]{34}$/.test(data);
    var isBlockNb = /^[0-9]{6}$/.test(data);

    if (isAddress == true) {
        return "isAddress";
    }

    if (isBlockNb == true) {
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
function getBlockHash(blockHash) {
    var url = "https://api.blockcypher.com/v1/btc/main/blocks/" + blockHash;
    apiGet(url);
}

function getBlockHeight(blockHeight){
    var url = "https://api.blockcypher.com/v1/btc/main/blocks/" + blockHeight;
    apiGet(url);
}

function getAddress(address){
    var url = "https://api.blockcypher.com/v1/btc/main/addrs/" + address;
    apiGet(url);
}

function getTx(tx){
    var url = "https://api.blockcypher.com/v1/btc/main/txs/" + tx;
    apiGet(url);
}

// Execute le call api
function apiGet(url) {
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // met en string puis parse la réponse retournée
            var myObj = JSON.stringify(JSON.parse(this.responseText),null,2);

            output(jsonPrettify(myObj));
        }
    };

    request.open('GET', url);
    request.send();
}

// Affiche le résultat du call api
function output(inp) {
    document.getElementById('apiReturn').appendChild(document.createElement('pre')).innerHTML = inp;

    var count = document.querySelectorAll("#apiReturn > pre").length;

    if (count > 1) {
        for (count; count > 1; count--){
            document.getElementById('apiReturn').firstChild.remove();
        }
        
    }
    
}

// Améliore le rendu du call api
function jsonPrettify(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
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

        match = addNavigationLink(match);

        return '<span class="' + cls + '">' + match + '</span>';
    });
}

// Ajoute des liens de navigation au rendu du call api
function addNavigationLink(data) {
    data = data.replace(/"/g, "");
    var test = identifyDataType(data);

    if(test == false || test == "isBlockNb"){
        data = '"' + data + '"';
    } else {
        if(test == "isBlockHash"){
            data = '<a href="javascript:getBlockHash(\'' + data + '\')">"' + data + '"</a>';
        } else if (test == "isTx") {
            data = '<a href="javascript:getTx(\'' + data + '\')">"' + data + '"</a>';
        } else if(test == "isAddress"){
         data = '<a href="javascript:getAddress(\'' + data + '\')">"' + data + '"</a>';
        }
    } 

    return data;

}