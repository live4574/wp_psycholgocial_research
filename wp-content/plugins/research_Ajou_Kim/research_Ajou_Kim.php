<?php
/* 
Plugin Name: Research Ajou 
Plugin URI:https://github.comlive4574/wp_psycholgocial_research 
Description: A research. 
Version: 1.0 
Author: Lee 
Author URI: https://github.com/live4574 
License: GPL2 
 */ 

add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu() {
   add_options_page('My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options');
}
function my_plugin_options() {
   if (!current_user_can('manage_options'))
     wp_die( __('You do not have sufficient permissions to access this page.') );
?>
   <div class='wrap'>
   <h2>My Plugin Options</h2>
   <p>Here is where the form would go if I actually had options.</p>
   </div>
<? } ?>