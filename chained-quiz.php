<?php
/*
Plugin Name: Chained Research
Description: Create a research
Author: Lee
Version: 1.0
License: GPLv2 or later
*/

define( 'CHAINED_PATH', dirname( __FILE__ ) );
define( 'CHAINED_RELATIVE_PATH', dirname( plugin_basename( __FILE__ )));
define( 'CHAINED_URL', plugin_dir_url( __FILE__ ));

// require controllers and models
require_once(CHAINED_PATH.'/models/basic.php');
require_once(CHAINED_PATH.'/models/quiz.php');
require_once(CHAINED_PATH.'/models/result.php');
require_once(CHAINED_PATH.'/models/question.php');
require_once(CHAINED_PATH.'/controllers/quizzes.php');
require_once(CHAINED_PATH.'/controllers/results.php');
require_once(CHAINED_PATH.'/controllers/questions.php');
require_once(CHAINED_PATH.'/controllers/completed.php');
require_once(CHAINED_PATH.'/controllers/shortcodes.php');
require_once(CHAINED_PATH.'/controllers/ajax.php');
require_once(CHAINED_PATH.'/helpers/htmlhelper.php');

add_action('init', array("ChainedQuiz", "init"));

register_activation_hook(__FILE__, array("ChainedQuiz", "install"));

add_action('admin_menu', array("ChainedQuiz", "menu"));
add_action('admin_enqueue_scripts', array("ChainedQuiz", "scripts"));

// show the things on the front-end

add_action( 'wp_enqueue_scripts', array("ChainedQuiz", "scripts"));

//text translate
function my_text_strings( $translated_text, $text, $domain ) {
switch ( $translated_text ) {
    case 'Chained Quiz' :
        $translated_text = __( 'Ajou Research', 'chained quiz' );
        break;
}
return $translated_text;
}
add_filter( 'gettext', 'my_text_strings', 20, 3 );


// other actions
add_action('wp_ajax_chainedquiz_ajax', 'chainedquiz_ajax');
add_action('wp_ajax_nopriv_chainedquiz_ajax', 'chainedquiz_ajax');