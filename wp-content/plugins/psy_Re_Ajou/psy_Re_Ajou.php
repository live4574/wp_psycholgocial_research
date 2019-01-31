
<?php
/*
Plugin Name: Research Ajou
Plugin URI: https://github.com/live4574/wp_psycholgocial_research
Description: A research.
Version: 1.0
Author: Lee
Author URI: https://github.com/live4574
License: GPL2
*/
//Include

include_once(plugin_dir_path(__FILE__) . 'inc/shortcode.php');

//User section

add_action('admin_menu','survey_menu');
function survey_menu(){
    add_menu_page('Survey','Survey','administrator','research-settings','research_plugin_settings_page');
}
add_action('admin_init','research_plugin_settings');

function survey_plugin_settings(){
    register_setting('research-settings-group')
}
?>