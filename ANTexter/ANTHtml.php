<?php
require_once(__DIR__ . '/../advanced-custom-fields/acf.php');

function addANtScript()
{
	wp_enqueue_script( 'an-script', plugin_dir_path() . 'ANTScript.js');	# code...
}

add_action( wp_enqueue_scripts, addANtScript );

echo '<style>
    body {
        font-family: sans-serif;
    }
    button, #phone, label[for="phone"] {
        float: left;
        width: 100px;
        margin: 10px;
    }
    .bottom {
        margin-bottom: 20px;
    }
    </style>

    <script>
        var TWapiKey="' . get_field( 'twillio_key', 'user_'. get_current_user_id()).'";
        var ANapiKey="' . get_field( 'an_key', 'user_'. get_current_user_id()). '";
        console.log("' . get_field( 'twillio_key', 'user_'. get_current_user_id()) . '");
    </script>

    <h2 onLoad="fetchTags()">AN Texter with Twillio</h2>
    <div class="bottom">
        <label for="message">Message</label>
        <textarea id="message" name="message"></textarea>
    <div>
    <div>
        <label for="ID Tags">Action Network group</label>
        <select name="tags" id="tags">
        </select>
    </div>
    <div>
    <label for="phone">Test Phone#:</label>
    <input id="phone" name="phone">
        <button type="button" onclick="tester()" disabled="disabled">test</button>
        <button type="button" onclick="sender()" disabled="disabled">send</button>
    </div>
	<div id="response"></div>
	<script>
	window.onload = function() {
		fetchTags();
	  };

	var message = document.getElementById("message").value;
var tag = document.getElementById("tags").value;
var phone = document.getElementById("phone").value;
var count = 0;
var total = 0;
var ANAdress = "https://actionnetwork.org/api/v2/";
var sendServer = "https://";
var ANapiKey = "095b4e51dccf9c92c464c0e564dd6f32";

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
    xhr.open("GET", href, false);
    xhr.setRequestHeader("OSDI-API-Token", ANapiKey);
    xhr.send();
}

function getName() {
    var resp = JSON.parse(this.responseText);
	addToSelect(resp.name);
	console.log(resp.name);
}

function addToSelect(content) {
	var x = document.getElementById("tags");
	var option = document.createElement("option");
	option.text = content;
	x.add(option);   
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




	</script>
	';
?>