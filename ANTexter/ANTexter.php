<?php
/** *
*Plugin Name: AN Texter with Twillio
*Description: Send custom messages to groups of users on Action Network
**/
    add_action("admin_menu", "texter");

    function texter()
    {
        add_menu_page("AN Text Sender", "AN Texter", "manage_options", "texter_settings_page", "texter_form");
    };

    function texter_form()
    {
        echo `
        <style>
            button, #phone, label[for="phone"] {
                float: left;
                width: 100px;
                margin: 10px;
            }
            .bottom {
                margin-bottom: 20px;
            }
        </style>
        <h2>AN Texter with Twillio</h2>
        <div class="bottom">
            <label for="message">Message</label>
            <textarea id="message" name="message"></textarea>
        <div>
        <div onLoad="fetchTags()">
            <label for="ID Tags">Action Network group</label>
            <select name="tags" id="tags">
                <option>ID tags</option>
            </select>
        </div>
        <div>
        <label for="phone">Test Phone#:</label>
        <input id="phone" name="phone">
            <button type="button" onclick="tester()" disabled="disabled">test</button>
            <button type="button" onclick="sender()" disabled="disabled">send</button>
        </div>
        <div id="response">
        </div>
  

<script>
    let message = document.getElementById("message").value
    let tag = document.getElementById("tags").value
    let phone = document.getElementById("phone").value
    let ANapiKey = <?php get_userdata( $an_key ); ?>
    let TWapiKey = <?php get_userdata( $twillio_key ); ?>
    let count = 0
    let ANAdress = "https://actionnetwork.org/api/v2/"
    let sendServer = "https://"

    //////////////////////////////////////////////////////////////////////////////////////////////
    const addOptions = (content) => {
        for (let i = 0; i < content.length; i++) {
            const element = content[i]

            let newDiv = document.createElement("option"); 
            let newContent = document.createTextNode(element);
            let newOption = newDiv.appendChild(newContent)
            document.getElementById("tags").appendChild(newOption)
                 
        }

    }

    //////////////////////////////////////////////////////////////////////////////////////////////
    function fetchTags() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

            let resp = this.responseText;
            let tags = resp._links["osdi:tags"]
            
            count = tags.length
            for (let x = 0; x < tags.length; x++) {
                const element = tags[x]
                ajax.get(element.href, "OSDI-API-Token: 095b4e51dccf9c92c464c0e564dd6f32", addOptions)
            }
        }
    }
        xhttp.open("GET", ANAdress + "tags/", true)
        xhttp.setRequestHeader("OSDI-API-Token", ANapiKey)
        xhttp.send()
    }

    //////////////////////////////////////////////////////////////////////////////////////////////

    function tester() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("response").innerHTML = this.responseText
            }
        }
        xhttp.open("GET", sendServer, true)
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
        xhttp.send("message="+message+"&TWapiKey="+TWapiKey+"&testphone="+phone)
    }

    //////////////////////////////////////////////////////////////////////////////////////////////
    function sender() {
        var xhttp = new XMLHttpRequest()
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("response").innerHTML = this.responseText;
            }
        }
        xhttp.open("GET", sendServer, true)
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
        xhttp.send("message="+message+"&ANTag="+tag+"&ANapiKey="+ANapiKey+"&TWapiKey="+TWapiKey)
    }
</script>`;

};
?>