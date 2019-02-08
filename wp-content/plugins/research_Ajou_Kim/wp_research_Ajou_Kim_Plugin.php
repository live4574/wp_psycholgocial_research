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
	add_action('init', array($this,'set_location_trading_hour_days')); 
    add_action('init', array($this,'register_location_content_type')); 
    add_action('add_meta_boxes', array($this,'add_location_meta_boxes')); 
    add_action('save_post_wp_locations', array($this,'save_location')); 
    add_action('admin_enqueue_scripts', array($this,'enqueue_admin_scripts_and_styles')); 
    add_action('wp_enqueue_scripts', array($this,'enqueue_public_scripts_and_styles')); 
    add_filter('the_content', array($this,'prepend_location_meta_to_content')); 
    register_activation_hook(__FILE__, array($this,'plugin_activate')); 
    register_deactivation_hook(__FILE__, array($this,'plugin_deactivate'));
}
//magic function triggered on initialization
