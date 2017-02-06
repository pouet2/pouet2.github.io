//18xuCetrm5mWcndvUABRJmbzkBDsiRPuiv
//0000000000000000028599c95924fec0ec49c4d8029180016a4130fe0a812105
//44818bc867a2f25881aa5b4e53e5831e9a9df0799c4e281bed1bb186ea21e426

function research(o){
    var data = document.getElementById(o).value;
    //var isHash = /^[0-9a-f]{64}$/.test(data);
    //var isAddress = /^[0-9a-zA-Z]{34}$/.test(data);
    //var isBlockNb = /^[0-9]{6}$/.test(data);
    var test = identifyDataType(data);

    if(test == "isBlockHash"){
        getBlockHash(data);
    } else if (test == "isTx") {
        getTx(data);
    } else if(test == "isAddress"){
        getAddress(data);
    } else if(test == "isBlockNb"){
        getBlockHeight(data);
    } else {
        document.getElementById("apiReturn").innerHTML = "veuillez entrer une Tx, une addresse, un hash de block ou transaction valide";
    }
}

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
}

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


function apiGet(url) {
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // met en string puis parse la réponse retournée
            var myObj = JSON.stringify(JSON.parse(this.responseText),null,2);
            //document.getElementById("apiReturn").innerHTML = myObj;
            output(jsonPrettify(myObj));
        }
    };

    request.open('GET', url);
    request.send();
}

function output(inp) {
    document.getElementById('apiReturn').appendChild(document.createElement('pre')).innerHTML = inp;
}

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
        //console.log(match);
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

function addNavigationLink(data) {
    data = data.replace(/"/g, "");
    var test = identifyDataType(data);
    //console.log(test);
    switch (test){
        case "isBlockHash":
            data = '<a href="javascript:getBlockHash(\'' + data + '\')">"' + data + '"</a>';
            break;
        case "isTx":
            data = '<a href="javascript:getTx(\'' + data + '\')">"' + data + '"</a>';
            break;
        case "isAddress":
            data = '<a href="javascript:getAddress(\'' + data + '\'")>"' + data + '"</a>';
            break;
        case "isBlockNb":
            data = '<a href="javascript:getBlockHeight(\'' + data + '\')">"' + data + '"</a>';
            break;
        default:
            data = '"' + data + '"';
    }

    return data

}




/*
// research fonction for Bitcoin block, tx & address
function research(o){
    var data = document.getElementById(o).value;
    var isHash = /^[0-9a-f]{64}$/.test(data);
    var isAddress = /^[0-9a-zA-Z]{34}$/.test(data);
    var isBlockNb = /^[0-9]{6}$/.test(data);
    
    console.log(data);
    if(isHash == true){
        var isBlockHash = data.substring(0,10);
        if(isBlockHash == "0000000000"){
            console.log('blockhash');
            getBlockHash(data);
        } else {
            getTx(data);
        }
    } else if(isAddress == true){
        getAddress(data);
    } else if(isBlockNb == true){
        getBlockHeight(data);
    } else {
        document.getElementById("apiReturn").innerHTML = "veuillez entrer une Tx, une addresse, un hash de block ou transaction valide";
    }
}

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


function apiGet(url) {
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // met en string puis parse la réponse retournée
            var myObj = JSON.stringify(JSON.parse(this.responseText),null,2);
            document.getElementById("apiReturn").innerHTML = myObj;
        }
    };

    request.open('GET', url);
    request.send();
}

/*

// simple get api request 
var request = new XMLHttpRequest();

request.open('GET', 'http://bitcoin.mubiz.com/block_hash/');

request.onreadystatechange = function () {
  if (this.readyState == 4 && this.status == 200) {
        // met en string puis parsela réponse retournée
        var myObj = JSON.stringify(JSON.parse(this.responseText),null,2);
        document.getElementById("demo").innerHTML = myObj;
    }
};

request.send();
*/


