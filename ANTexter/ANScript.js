var message = document.getElementById("message").value;
var tag = document.getElementById("tags").value;
var phone = document.getElementById("phone").value;
//var TWapiKey = <?php get_field( 'an_key', 5 ); ?>
//var TWapiKey = <?php get_field( 'twillio_key', 5 ); ?>

var ANapiKey = "095b4e51dccf9c92c464c0e564dd6f32";
var count = 0;
var ANAdress = "https://actionnetwork.org/api/v2/";
var sendServer = "https://";

    //////////////////////////////////////////////////////////////////////////////////////////////
    const addOptions = (content) => {
        // for (var i = 0; i < content.length; i++) {
        //     var element = content[i];

            var newDiv = document.createElement("option"); 
            var newContent = document.createTextNode(element);
            var newOption = newDiv.appendChild(newContent);
            document.getElementById("tags").appendChild(newOption);
                 
        //}

    };

    //////////////////////////////////////////////////////////////////////////////////////////////
    const fetchTags = async () => {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var resp = await JSON.parse(xhttp.responseText);
                var tags = resp._links["osdi:tags"];
            
                count = tags.length;
                for (var x = 0; x < tags.length; x++) {
                    var element = tags[x].href;
                    addOptions(fetchNames(element));
                }
            
            }
        };
        xhttp.open("GET", ANAdress + "tags/", true);
        xhttp.setRequestHeader("OSDI-API-Token", ANapiKey);
        xhttp.send();
    }


        const fetchNames = async (element) => {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
    
                var respon = await JSON.parse(xhr.responseText);
                return respon.name;
            }
        };
            xhr.open("GET", element, true);
            xhr.setRequestHeader("OSDI-API-Token", ANapiKey);
            xhr.send();
        }


    //////////////////////////////////////////////////////////////////////////////////////////////

    function tester() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("response").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", sendServer, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("message="+message+"&TWapiKey="+TWapiKey+"&testphone="+phone);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////
    function sender() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("response").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", sendServer, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("message="+message+"&ANTag="+tag+"&ANapiKey="+ANapiKey+"&TWapiKey="+TWapiKey);
    }

