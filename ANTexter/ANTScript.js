var message = document.getElementById("message").value;
var tag = document.getElementById("tags").value;
var phone = document.getElementById("phone").value;
//var TWapiKey = <?php get_field( 'an_key', 5 ); ?>
//var ANapiKey = <?php get_field( 'twillio_key', 5 ); ?>
var ANapiKey = "095b4e51dccf9c92c464c0e564dd6f32";
var count = 0;
var ANAdress = "https://actionnetwork.org/api/v2/";
var sendServer = "https://";

//////////////////////////////////////////////////////////////////////////////////////////////
function addOptions(content) {
    var newDiv = document.createElement("option"); 
    for (var i = 0; i < content.length; i++) {
        var element = content[i];
        var newContent = document.createTextNode(element);
        var newOption = newDiv.appendChild(newContent);
        document.getElementById("tags").appendChild(newOption);        
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////
function getHrefs() {            
    var resp = JSON.parse(this.responseText);
    var tags = resp._links["osdi:tags"];

    count = tags.length;
    for (var x = 0; x < tags.length; x++) {
        var element = tags[x].href;
        addOptions(fetchNames(element));
    }
}
    
function fetchTags() {
    var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", getHrefs);
    xhttp.open("GET", ANAdress + "tags/", true);
    xhttp.setRequestHeader("OSDI-API-Token", ANapiKey);
    xhttp.send();
}

function getTags() {
    var respon = JSON.parse(this.responseText);
    addOptions(respon.name); 
}

function fetchNames (element) {
    var xhr = new XMLHttpRequest();
    xhr.addEventListener("load", getTags);
    xhr.open("GET", element, true);
    xhr.setRequestHeader("OSDI-API-Token", ANapiKey);
    xhr.send();
}

//////////////////////////////////////////////////////////////////////////////////////////////

function serverResponse() {
    document.getElementById("response").innerHTML = this.responseText;
}

function tester() {
    var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", serverResponse);
    xhttp.open("GET", sendServer, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("message="+message+"&TWapiKey="+TWapiKey+"&testphone="+phone);
}

function sender() {
    var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", serverResponse);
    xhttp.open("GET", sendServer, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("message="+message+"&ANTag="+tag+"&ANapiKey="+ANapiKey+"&TWapiKey="+TWapiKey);
}

