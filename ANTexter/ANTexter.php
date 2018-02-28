<?php
    /** *
    *Plugin Name: AN Texter with Twillio
    *Description: Send custom messages to groups of users on Action Network
    **/

    
    add_action("admin_menu", "texter");

    function texter() {
        add_menu_page("Action Texts", "Action Texter", "manage_options", "texter_settings_page", "texter_form");
    };

    function texter_form() {
        echo include('ANTHtml.php');
    };

    function add_roles_on_plugin_activation() {
        if (!is_plugin_active('advanced-custom-fields/acf.php')) {
            // Deactivate the plugin
				deactivate_plugins(__FILE__);
				
				// Throw an error in the wordpress admin console
				$error_message = __('This plugin requires the <a href="https://www.advancedcustomfields.com/">Advanced Custom Fields</a> plugin to be active!', 'advanced_custom_fields');
				die($error_message);
        } else {
            add_role( 'action_texter', 'Action Texter', array( 'read' => true, 'level_0' => true ) );
        }
    }

    register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );
    
    function remove_roles_on_plugin_deactivation() {
        //check if role exist before removing it
        if( get_role('action_texter') ) {
            remove_role( 'action_texter' );
        }

    }

    register_deactivation_hook( __FILE__, 'remove_roles_on_plugin_deactivation' );
?>

