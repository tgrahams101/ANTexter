<script>
    var TWapiKey="<?php echo get_field( 'twillio_key', 5 ); ?>";
    var ANapiKey="<?php echo get_field( 'an_key', 5 ); ?>";
</script>

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
        echo include('ANTHtml.html');
    };
    
?>

