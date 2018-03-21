var message = document.getElementById("message").value;
var tag = document.getElementById("tags").value;
var phone = document.getElementById("phone").value;
var count = 0;
var total = 0;
var ANAdress = "https://actionnetwork.org/api/v2/";
var sendServer = "https://";

//////////////////////////////////////////////////////////////////////////////////////////////
// Get tag categories from Action Network and add to tag_id dropdown
function fetchTags() {
    var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", getHrefs);
    xhttp.open("GET", ANAdress + "tags/", true);
    xhttp.setRequestHeader("OSDI-API-Token", ANapiKey);
    xhttp.send();
}

function getHrefs() {            
    var resp = JSON.parse(this.responseText);
    var hrefs = resp._links["osdi:tags"];
    count = hrefs.length;
    
    for (var x = 0; x < count; x++) {
        fetchObject(hrefs[x].href);
    }
}
    
function fetchObject (href) {
    var xhr = new XMLHttpRequest();
    xhr.addEventListener("load", getName);
    xhr.open("GET", href, true);
    xhr.setRequestHeader("OSDI-API-Token", ANapiKey);
    xhr.send();
}

function getName() {
    var resp = JSON.parse(this.responseText);
    addToSelect(resp.name); 
}

function addToSelect(content) {
    var newDiv = document.createElement("option");
    var newOption = newDiv.appendChild(document.createTextNode(content));
    document.querySelector("select").appendChild(newOption);   
}

//////////////////////////////////////////////////////////////////////////////////////////////
// Send text message
function tester() {
    var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", serverResponse);
    xhttp.open("GET", sendServer, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("message="+message+"&TWapiKey="+TWapiKey+"&testphone="+phone);
}

// Send real messages
function sender() {
    var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", serverResponse);
    xhttp.open("GET", sendServer, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("message="+message+"&ANTag="+tag+"&ANapiKey="+ANapiKey+"&TWapiKey="+TWapiKey);
}

// Post response from server
function serverResponse() {
    document.getElementById("response").innerHTML = this.responseText;
}
