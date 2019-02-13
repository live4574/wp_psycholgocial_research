<?php
/*
Plugin Name: Chained Research
Plugin URI: http://calendarscripts.info/chained-research.html
Description: Create a chained research where the upcoming questions can depend on the previous answer
Author: Kiboko Labs
Version: 0.8.7
Author URI: http://calendarscripts.info/
License: GPLv2 or later
Text domain: chained
*/

define( 'CHAINED_PATH', dirname( __FILE__ ) );
define( 'CHAINED_RELATIVE_PATH', dirname( plugin_basename( __FILE__ )));
define( 'CHAINED_URL', plugin_dir_url( __FILE__ ));

// require controllers and models
require_once(CHAINED_PATH.'/models/basic.php');
require_once(CHAINED_PATH.'/models/research.php');
require_once(CHAINED_PATH.'/models/result.php');
require_once(CHAINED_PATH.'/models/question.php');
require_once(CHAINED_PATH.'/controllers/researchzes.php');
require_once(CHAINED_PATH.'/controllers/results.php');
require_once(CHAINED_PATH.'/controllers/questions.php');
require_once(CHAINED_PATH.'/controllers/completed.php');
require_once(CHAINED_PATH.'/controllers/shortcodes.php');
require_once(CHAINED_PATH.'/controllers/ajax.php');
require_once(CHAINED_PATH.'/helpers/htmlhelper.php');

add_action('init', array("ChainedResearch", "init"));

register_activation_hook(__FILE__, array("ChainedResearch", "install"));
add_action('admin_menu', array("ChainedResearch", "menu"));
add_action('admin_enqueue_scripts', array("ChainedResearch", "scripts"));

// show the things on the front-end
add_action( 'wp_enqueue_scripts', array("ChainedResearch", "scripts"));


// other actions
add_action('wp_ajax_chainedresearch_ajax', 'chainedresearch_ajax');
add_action('wp_ajax_nopriv_chainedresearch_ajax', 'chainedresearch_ajax');