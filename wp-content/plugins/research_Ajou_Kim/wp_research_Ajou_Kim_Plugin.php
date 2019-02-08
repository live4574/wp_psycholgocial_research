<?php
defined('ABSPATH') or die('Nope, not accesing this');
//block direct access or terminate

/*
Plugin Name: Research Ajou
Plugin URI: https://github.com/live4574/wp_psycholgocial_research
Description: A research.
Version: 1.0
Author: Lee
Author URI: https://github.com/live4574
License: GPL2
*/
//plugin declartion

private $wp_ajou_survey_seconds=array();
//properties

class wp_simple_location{
	include(plugin_dir_path(__FILE__) . 'inc/wp_research_ajou_shortcode.php');
	//include shortcodes
	include(plugin_dir_path(__FILE__) . 'inc/wp_research_ajou_widget.php');
	//include widgets
}
publci function __construct(){
	add_action('init', array($this,'set_location_survey_hour_days')); //set the default survey hour days(used by the content type)
    add_action('init', array($this,'register_location_content_type')); //register location content type
    add_action('add_meta_boxes', array($this,'add_location_meta_boxes')); //add meta boexs
    add_action('save_post_wp_locations', array($this,'save_location'));  //save location
    add_action('admin_enqueue_scripts', array($this,'enqueue_admin_scripts_and_styles')); //admin scripts and styles
    add_action('wp_enqueue_scripts', array($this,'enqueue_public_scripts_and_styles'));
    //public scripts and styles 
    add_filter('the_content', array($this,'prepend_location_meta_to_content')); 
    //gets our meta data and dispayed it before the content
    register_activation_hook(__FILE__, array($this,'plugin_activate')); //activate hook
    register_deactivation_hook(__FILE__, array($this,'plugin_deactivate')); //deactivate hook
}
//magic function triggered on initialization
