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

public function set_location_survey_hour_days(){
	$this->wp_location_survey_hour_days=apply_filtres('wp_location_survey_hours_days',array('monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday'  'thursday' => 'Thursday','friday' => 'Friday','saturday' => 'Saturday','sunday' => 'Sunday',
        ));
}
//set the default survey hour days(used in admin backend)

public function register_location_content_type(){
	$labels = array( 'name'               => 'Location',
           'singular_name'      => 'Location',
           'menu_name'          => 'Locations',
           'name_admin_bar'     => 'Location',
           'add_new'            => 'Add New', 
           'add_new_item'       => 'Add New Location',
           'new_item'           => 'New Location', 
           'edit_item'          => 'Edit Location',
           'view_item'          => 'View Location',
           'all_items'          => 'All Locations',
           'search_items'       => 'Search Locations',
           'parent_item_colon'  => 'Parent Location:', 
           'not_found'          => 'No Locations found.', 
           'not_found_in_trash' => 'No Locations found in Trash.',
       );
	//labels for post type
	$args=array(
		   'labels'            => $labels,
           'public'            => true,
           'publicly_queryable'=> true,
           'show_ui'           => true,
           'show_in_nav'       => true,
           'query_var'         => true,
           'hierarchical'      => false,
           'supports'          => array('title','thumbnail','editor'),
           'has_archive'       => true,
           'menu_position'     => 20,
           'show_in_admin_bar' => true,
           'menu_icon'         => 'dashicons-location-alt',
           'rewrite'            => array('slug' => 'locations', 'with_front' => 'true')
       );
	//argument for post type
	register_post_type('wp_locations',$args);
	//register post type
}