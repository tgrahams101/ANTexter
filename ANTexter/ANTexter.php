<?php
    /** *
    *Plugin Name: AN Texter with Twillio
    *Description: Send custom messages to groups of users on Action Network
    *Author: Jeff Dangerfield
    *Version: 1.0
    **/
    add_action("admin_menu", "texter");

    function texter() {
        add_menu_page("AN Text Sender", "AN Texter", "manage_options", "texter_settings_page", "texter_form");
    };

    function texter_form() {

        if (!current_user_can( 'manage_options' )) {
            wp_die( "Sorry. You don't have access to use this plugin." );
        }
        
        echo include('ANTHtml.php');
    };
    
    // $addTesterUser = "$result = add_role( 'actiontexter', __('Action Texter' ),
    //     array(
    //         'read' => true, // true allows this capability
    //         'texter_settings_page' => true, // Allows the user to publish, otherwise posts stays in draft mode
    //         'edit_themes' => false, // false denies this capability. User can’t edit your theme
    //         'install_plugins' => false, // User cant add new plugins
    //         'update_plugin' => false, // User can’t update any plugins
    //         'update_core' => false // user cant perform core updates
    //     )    
    // );";

    // echo file_append("/../advanced-custom-fields/core/fields/_functions.php", $addTesterUser);

?>

