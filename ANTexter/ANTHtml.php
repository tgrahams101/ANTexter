<?php
    require_once(__DIR__ . '/../advanced-custom-fields/acf.php');

    function addANtScript(){
        wp_enqueue_script( 'an-script', plugin_dir_path() . 'ANTScript.js');	# code...
    }

    add_action( wp_enqueue_scripts, addANtScript );

?>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"   integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> 
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<style>
            body {
                font-family: sans-serif;
            }
            .tester button, #phone {
                width: 138px;
                margin: 10px;
            }
            .bottom {
                margin-bottom: 20px;
			}
			#tags {
				width: 260px;
			}
			.sender button {
				width:390px;
			}
			button {
				height: 30px;
			}
        </style>
        <h2>AN Texter with Twillio</h2>
        <img id="loadingTags" src="<?php echo plugins_url( 'images/loading_spinner.gif', __FILE__ ); ?>" />
        <div>
            <label for="ID Tags">Action Network Tag</label>
            <select class="js-example-basic-single bottom" name="tags" id="tags" onchange="getTaggingsCount(this)">
                <option disabled selected>Select a Tag</option>
            </select>
		</div>
		<div>
		    <span id="recipientTotal">Total recipients: 0</span>
        </div>
        <div class="bottom">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="4" cols="50"></textarea>
        </div>
        <div class="tester">
            <label for="phone">Test Phone#:</label>
            <input id="phone" name="phone">
            <button type="button" onclick="tester()" >test</button>
		</div>
		<div class="sender">
			<button id="sendButton" type="button" onclick="sender()" disabled >send</button>
		</div>
        <div id="response"></div>
        <img id="sendingTexts" src="<?php echo plugins_url( 'images/loading_spinner.gif', __FILE__ ); ?>" />
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
var sendServer = ajaxurl;
var ANapiKey="<?php echo get_field( 'action_network_api_key', 'user_'. get_current_user_id()) ?>";
var theTagId = "";
            //////////////////////////////////////////////////////////////////////////////////////////////
            // Get tag categories from Action Network and add to tag_id dropdown
            function fetchTags() {
                var xhttp = new XMLHttpRequest();
                xhttp.addEventListener("load", getTags);
                xhttp.open("GET", ANAdress + "tags/", true);
                xhttp.setRequestHeader("OSDI-API-Token", ANapiKey);
                xhttp.send();
            }

            function getTags() {            
                var resp = JSON.parse(this.responseText);
                var tags = resp._embedded["osdi:tags"];
                
                for (var x = 0; x < tags.length; x++) {
                    var ref = tags[x]['_links']['self']['href'].split('/');
                    addToSelect(tags[x].name, ref[ref.length - 1] );
                }
                $("#loadingTags").hide();
            }
            
            function getTaggingsCount(elem) {
                theTag = elem.value;
                var xhttp = new XMLHttpRequest();
                xhttp.addEventListener("load", handleTaggingsResponse);
                xhttp.open("GET", ANAdress + "tags/" +elem.value+ "/taggings/", true);
                xhttp.setRequestHeader("OSDI-API-Token", ANapiKey);
                xhttp.send();
            }
            
            function handleTaggingsResponse() {
                var total = document.getElementById("recipientTotal");
                var data = JSON.parse(this.responseText);
                 total.innerHTML = "Total recipients: " + data["total_records"];
                document.getElementById("sendButton").disabled = false;
            }

            function addToSelect(content, value) {
                var newTag = document.getElementById("tags");
                var option = document.createElement("option");
                option.text = content;
                option.value = value;
                newTag.add(option);
            }

            //////////////////////////////////////////////////////////////////////////////////////////////
            // Send text message
            function tester() {
                var message = document.getElementById("message").value;
                var phone = document.getElementById("phone").value;
                var xhttp = new XMLHttpRequest();
                xhttp.addEventListener("load", serverResponse);
                xhttp.open("POST", sendServer, true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("action=send_test_text&body="+message+"&to="+phone);
            }

            // Send real messages
            function sender() {
                if (confirm(document.getElementById("recipientTotal").innerHTML)) {
                    var message = document.getElementById("message").value;
                    var xhttp = new XMLHttpRequest();
                    xhttp.addEventListener("load", checkProgress);
                    xhttp.open("POST", sendServer, true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("action=send_bulk_text&body=" + message + "&tags=" + theTag);
                    $("#sendingTexts").show();
                }
            }
            
            function checkProgress() {
                var response = JSON.parse(this.responseText);
                if (response.finish) {
                    document.getElementById("response").innerHTML = "FINISHED!<br>Total sent: " 
                        +response.messagesSent+ "<br>Total missing numbers: " 
                        +response.missingNumbers+ "<br>Total errors: " +response.errors+ "<br>";
                    $("#sendingTexts").hide();
                } else if (response.errMsg) {
                    document.getElementById("response").innerHTML = response.errMsg;
                } else {
                    document.getElementById("response").innerHTML = "Sent " + response.messagesSent + " messages";
                }
                
                setTimeout(function() {
                    var xhttp = new XMLHttpRequest();
                    xhttp.addEventListener("load", checkProgress);
                    xhttp.open("GET", sendServer + "?action=check_progress&pid=" + response.id, true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send();
                }, 30000);
            }

            // Post response from server
            function serverResponse() {
                document.getElementById("response").innerHTML = this.responseText;
            }
            $("#sendingTexts").hide();
        </script>