<?php
// main model containing general config and UI functions
class ChainedQuiz {
   static function install($update = false) {
   	global $wpdb;	
   	$wpdb -> show_errors();
   	
   	if(!$update) self::init();
	  
	   // quizzes
   	if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_QUIZZES."'") != CHAINED_QUIZZES) {        
			$sql = "CREATE TABLE `" . CHAINED_QUIZZES . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `title` VARCHAR(255) NOT NULL DEFAULT '',
				  `output` TEXT				  
				) DEFAULT CHARSET=utf8;";
			
			$wpdb->query($sql);
	  }
	  
	  // questions
   	if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_QUESTIONS."'") != CHAINED_QUESTIONS) {        
			$sql = "CREATE TABLE `" . CHAINED_QUESTIONS . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `title` VARCHAR(255) NOT NULL DEFAULT '',
				  `question` TEXT,
				  `qtype` VARCHAR(20) NOT NULL DEFAULT '',
				  `rank` INT UNSIGNED NOT NULL DEFAULT 0			  
				) DEFAULT CHARSET=utf8;";
			
			$wpdb->query($sql);
	  } 
	 //targets
	  if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_TARGETS."'") != CHAINED_TARGETS) {        
			$sql = "CREATE TABLE `" . CHAINED_TARGETS . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGED NOT NULL DEFAULT 0,
				  `question_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `target` TEXT,			  
				) DEFAULT CHARSET=utf8;";
			
			$wpdb->query($sql);
	  }
	 
	  // choices
     if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_CHOICES."'") != CHAINED_CHOICES) {        
			$sql = "CREATE TABLE `" . CHAINED_CHOICES . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `question_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `choice` TEXT,
				  `points` DECIMAL(4,2) NOT NULL DEFAULT '0.00',
				  `is_correct` TINYINT UNSIGNED NOT NULL DEFAULT 0,
				  `goto` VARCHAR(100) NOT NULL DEFAULT 'next'
				) DEFAULT CHARSET=utf8;";
			
			$wpdb->query($sql);
	  } 
	  
	  // results
	  if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_RESULTS."'") != CHAINED_RESULTS) {        
			$sql = "CREATE TABLE `" . CHAINED_RESULTS . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `points_bottom` DECIMAL(8,2) NOT NULL DEFAULT '0.00',
				  `points_top` DECIMAL(8,2) NOT NULL DEFAULT '0.00',
				  `title` VARCHAR(255) NOT NULL DEFAULT '',
				  `description` TEXT 
				) DEFAULT CHARSET=utf8;";
			
			$wpdb->query($sql);
	  } 
	  
	  // completed quizzes	
	  if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_COMPLETED."'") != CHAINED_COMPLETED) {        
			$sql = "CREATE TABLE `" . CHAINED_COMPLETED . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `points` DECIMAL(8,2) NOT NULL DEFAULT '0.00',
				  `result_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `datetime` DATETIME,
				  `ip` VARCHAR(20) NOT NULL DEFAULT '',
				  `user_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `snapshot` TEXT
				) DEFAULT CHARSET=utf8;";
			
			$wpdb->query($sql);
	  } 	 
	  
	  // details of user answers
	  if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_USER_ANSWERS."'") != CHAINED_USER_ANSWERS) {        
			$sql = "CREATE TABLE `" . CHAINED_USER_ANSWERS . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `completion_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `question_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `answer` TEXT,
				  `points` DECIMAL(4,2) NOT NULL DEFAULT '0.00'				  
				) DEFAULT CHARSET=utf8;";
			
			$wpdb->query($sql);
	  } 	 
	  
	  
	  chainedquiz_add_db_fields(array(
	  	  array("name" => 'autocontinue', 'type' => 'TINYINT UNSIGNED NOT NULL DEFAULT 0'),
	  	  array("name" => 'sort_order', 'type' => 'INT UNSIGNED NOT NULL DEFAULT 0'),
	  ), CHAINED_QUESTIONS);
	  
	  chainedquiz_add_db_fields(array(
	  	  array("name" => 'redirect_url', 'type' => "VARCHAR(255) NOT NULL DEFAULT ''"),
	  ), CHAINED_RESULTS);
	  
	  chainedquiz_add_db_fields(array(
	  	  array("name" => 'email_admin', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
	  	  array("name" => 'email_user', 'type' => "TINYINT UNSIGNED NOT NULL DEFAULT 0"),
	  ), CHAINED_QUIZZES);
	  
	  chainedquiz_add_db_fields(array(
	  	  array("name" => 'not_empty', 'type' => "TINYINT NOT NULL DEFAULT 0"), /*When initially creating a record, it is empty. If it remains so we have to delete it.*/
	  ), CHAINED_COMPLETED);
	  
	  // fix sort order once for old quizzes (in version 0.7.5)
		if(get_option('chained_fixed_sort_order') != 1) {
			ChainedQuizQuestions :: fix_sort_order_global();
			update_option('chained_fixed_sort_order', 1);
		}	
		
		// update not_empty = 1 for all completed records prior to version and DB version 
		$version = get_option('chainedquiz_version');
		if($version < 0.67) {
			$wpdb->query("UPDATE ".CHAINED_COMPLETED." SET not_empty=1");
		}
	  
	  update_option('chainedquiz_version', "0.67");
	  // exit;
   }
   
   // main menu
   static function menu() {
   	add_menu_page(__('Chained Quiz', 'chained'), __('Chained Quiz', 'chained'), "manage_options", "chained_quizzes", 
   		array('ChainedQuizQuizzes', "manage"));
   		
   	add_submenu_page(NULL, __('Chained Quiz Results', 'chained'), __('Chained Quiz Results', 'chained'), 'manage_options', 
   		'chainedquiz_results', array('ChainedQuizResults','manage'));	
   	add_submenu_page(NULL, __('Chained Quiz Questions', 'chained'), __('Chained Quiz Questions', 'chained'), 'manage_options', 
   		'chainedquiz_questions', array('ChainedQuizQuestions','manage'));	
   	add_submenu_page(NULL, __('Users Completed Quiz', 'chained'), __('Users Completed Quiz', 'chained'), 'manage_options', 
   		'chainedquiz_list', array('ChainedQuizCompleted','manage'));		
	}
	
	// CSS and JS
	static function scripts() {
		// CSS
		wp_register_style( 'chained-css', CHAINED_URL.'css/main.css?v=1');
	  wp_enqueue_style( 'chained-css' );
   
   	wp_enqueue_script('jquery');
	   
	   // Chained quiz's own Javascript
		wp_register_script(
				'chained-common',
				CHAINED_URL.'js/common.js',
				false,
				'1.0',
				false
		);
		wp_enqueue_script("chained-common");
		
		$translation_array = array('please_answer' => __('Please answer the question', 'chained'));
		wp_localize_script( 'chained-common', 'chained_i18n', $translation_array );	
	}
	
	// initialization
	static function init() {
		global $wpdb;
		load_plugin_textdomain( 'chained', false, CHAINED_RELATIVE_PATH."/languages/" );
		if (!session_id()) @session_start();
		
		// define table names 
		define( 'CHAINED_QUIZZES', $wpdb->prefix. "chained_quizzes");
		define( 'CHAINED_QUESTIONS', $wpdb->prefix. "chained_questions");
		define('CHAINED_TARGETS', $wpdb->prefix. "chained_targets");
		define( 'CHAINED_CHOICES', $wpdb->prefix. "chained_choices");
		define( 'CHAINED_RESULTS', $wpdb->prefix. "chained_results");
		define( 'CHAINED_COMPLETED', $wpdb->prefix. "chained_completed");
		define( 'CHAINED_USER_ANSWERS', $wpdb->prefix. "chained_user_answers");
		
		define( 'CHAINED_VERSION', get_option('chained_version'));
				
		// shortcodes
		add_shortcode('chained-research', array("ChainedQuizShortcodes", "quiz"));	
		
		// once daily delete empty records older than 1 day
		if(get_option('chainedquiz_cleanup') != date("Y-m-d")) {
			$wpdb->query("DELETE FROM ".CHAINED_COMPLETED." WHERE not_empty=0 AND datetime < '".current_time('mysql')."' - INTERVAL 24 HOUR");
			update_option('chainedquiz_cleanup', date("Y-m-d"));
		}
				
		$version = get_option('chainedquiz_version');
		if($version < '0.67') self::install(true);
	}
			
		
	static function help() {
		require(CHAINED_PATH."/views/help.php");
	}	
}