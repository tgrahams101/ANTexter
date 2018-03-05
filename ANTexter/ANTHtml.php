<?php
    require_once(__DIR__ . '/../advanced-custom-fields/acf.php');

    function addANtScript(){
        wp_enqueue_script( 'an-script', plugin_dir_path() . 'ANTScript.js');	# code...
    }

    add_action( wp_enqueue_scripts, addANtScript );


    echo '
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
        <div>
            <label for="ID Tags">Action Network group</label>
            <select class="js-example-basic-single bottom" name="tags" id="tags"></select>
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
			<button type="button" onclick="sender()" >send</button>
		</div>
        <div id="response"></div>
        <script>
            window.onload = function() {
                fetchTags();
            };

            var TWapiKey="' . get_field( 'twillio_key', 'user_'. get_current_user_id()).'";
            var ANapiKey="' . get_field( 'an_key', 'user_'. get_current_user_id()). '";
            console.log("' . get_field( 'twillio_key', 'user_'. get_current_user_id()) . '");
            $(".js-example-basic-single").select2();

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
                xhttp.addEventListener("load", getTags);
                xhttp.open("GET", ANAdress + "tags/", true);
                xhttp.setRequestHeader("OSDI-API-Token", ANapiKey);
                xhttp.send();
            }

            function getTags() {            
                var resp = JSON.parse(this.responseText);
                var tags = resp._embedded["osdi:tags"];
                
                for (var x = 0; x < tags.length; x++) {
                    addToSelect(tags[x].name);
                    console.log(tags[x].name);
                }
            }

            function addToSelect(content) {
                var newTag = document.getElementById("tags");
                var option = document.createElement("option");
                option.text = content;
                newTag.add(option);  
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
        </script>';
?>