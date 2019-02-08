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

class wp_simple_location{
	include(plugin_dir_path(__FILE__) . 'inc/wp_research_ajou_shortcode.php');
	//include shortcodes
	include(plugin_dir_path(__FILE__) . 'inc/wp_research_ajou_widget.php');
	//include widgets
}
