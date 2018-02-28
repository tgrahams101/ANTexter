<?php
require_once(__DIR__ . '/../advanced-custom-fields/acf.php');

echo '<style>
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
    <div id="response"></div>
    <script src="ANTScript.js"></script>'
?>