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
	    // targets
   	if($wpdb->get_var("SHOW TABLES LIKE '".CHAINED_TARGETS."'") != CHAINED_TARGETS) {        
			$sql = "CREATE TABLE `" . CHAINED_TARGETS . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `quiz_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `question_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `target` TEXT,
				  `is_correct` TINYINT UNSIGNED NOT NULL DEFAULT 0,
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
				  `description` TEXT,
				  `check` TEXT, 
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
	  	  array("name" => 'target','type'=>'INT UNSIGNED NOT NULL DEFAULT 0'),
	  	  array("name" => 'target1', 'type'=>'TEXT'),
	  	  array("name" => 'target2', 'type'=>'TEXT'),
	  	  array("name" => 'target3', 'type'=>'TEXT'),
	  	  array("name" => 'target4', 'type'=>'TEXT'),
	  	  array("name" => 'target5', 'type'=>'TEXT'),
	  	  array("name" => 'target6', 'type'=>'TEXT'),
	  	  array("name" => 'target7', 'type'=>'TEXT'),	  	  
	  	  array("name" => 'target8', 'type'=>'TEXT'),
	  	  array("name" => 'target9', 'type'=>'TEXT'),
	  	  array("name" => 'target10', 'type'=>'TEXT'),
	  	  array("name" => 'target11', 'type'=>'TEXT'),
	  	  array("name" => 'target12', 'type'=>'TEXT'),
	  	  array("name" => 'target13', 'type'=>'TEXT'),
	  	  array("name" => 'target14', 'type'=>'TEXT'),
	  	  array("name" => 'target15', 'type'=>'TEXT'),
	  	  array("name" => 'target16', 'type'=>'TEXT'),
	  	  array("name" => 'target17', 'type'=>'TEXT'),
	  	  array("name" => 'target18', 'type'=>'TEXT'),
	  	  array("name" => 'target19', 'type'=>'TEXT'),
	  	  array("name" => 'target20', 'type'=>'TEXT'),
	  	  array("name" => 'target21', 'type'=>'TEXT'),
	  	  array("name" => 'target22', 'type'=>'TEXT'),
	  	  array("name" => 'target23', 'type'=>'TEXT'),
	  	  array("name" => 'target24', 'type'=>'TEXT'),
	  	  array("name" => 'target25', 'type'=>'TEXT'),
	  	  array("name" => 'target26', 'type'=>'TEXT'),
	  	  array("name" => 'target27', 'type'=>'TEXT'),
	  	  array("name" => 'target28', 'type'=>'TEXT'),
	  	  array("name" => 'target29', 'type'=>'TEXT'),
	  	  array("name" => 'target30', 'type'=>'TEXT'),
	  	  array("name" => 'target31', 'type'=>'TEXT'),
	  	  array("name" => 'target32', 'type'=>'TEXT'),
	  	  array("name" => 'target33', 'type'=>'TEXT'),
	  	  array("name" => 'target34', 'type'=>'TEXT'),
	  	  array("name" => 'target35', 'type'=>'TEXT'),
	  	  array("name" => 'target36', 'type'=>'TEXT'),
	  	  array("name" => 'target37', 'type'=>'TEXT'),	  	  
	  	  array("name" => 'target38', 'type'=>'TEXT'),
	  	  array("name" => 'target39', 'type'=>'TEXT'),
	  	  array("name" => 'target40', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget1', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget2', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget3', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget4', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget5', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget6', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget7', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget8', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget9', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget10', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget11', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget12', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget13', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget14', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget15', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget16', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget17', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget18', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget19', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget20', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget21', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget22', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget23', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget24', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget25', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget26', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget27', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget28', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget29', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget30', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget31', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget32', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget33', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget34', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget35', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget36', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget37', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget38', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget39', 'type'=>'TEXT'),
	  	  array("name" => 'ranTarget40', 'type'=>'TEXT'),
	  	  array("name" => 'targetAns1', 'type'=>'TEXT'),
		  array("name" => 'targetAns2', 'type'=>'TEXT'),
		  array("name" => 'targetAns3', 'type'=>'TEXT'),
		  array("name" => 'targetAns4', 'type'=>'TEXT'),
		  array("name" => 'targetAns5', 'type'=>'TEXT'),
		  array("name" => 'targetAns6', 'type'=>'TEXT'),
		  array("name" => 'targetAns7', 'type'=>'TEXT'),
		  array("name" => 'targetAns8', 'type'=>'TEXT'),
		  array("name" => 'targetAns9', 'type'=>'TEXT'),
		  array("name" => 'targetAns10', 'type'=>'TEXT'),
		  array("name" => 'targetAns11', 'type'=>'TEXT'),
		  array("name" => 'targetAns12', 'type'=>'TEXT'),
		  array("name" => 'targetAns13', 'type'=>'TEXT'),
		  array("name" => 'targetAns14', 'type'=>'TEXT'),
		  array("name" => 'targetAns15', 'type'=>'TEXT'),
		  array("name" => 'targetAns16', 'type'=>'TEXT'),
		  array("name" => 'targetAns17', 'type'=>'TEXT'),
		  array("name" => 'targetAns18', 'type'=>'TEXT'),
		  array("name" => 'targetAns19', 'type'=>'TEXT'),
		  array("name" => 'targetAns20', 'type'=>'TEXT'),
		  array("name" => 'targetAns21', 'type'=>'TEXT'),
		  array("name" => 'targetAns22', 'type'=>'TEXT'),
		  array("name" => 'targetAns23', 'type'=>'TEXT'),
		  array("name" => 'targetAns24', 'type'=>'TEXT'),
		  array("name" => 'targetAns25', 'type'=>'TEXT'),
		  array("name" => 'targetAns26', 'type'=>'TEXT'),
		  array("name" => 'targetAns27', 'type'=>'TEXT'),
		  array("name" => 'targetAns28', 'type'=>'TEXT'),
		  array("name" => 'targetAns29', 'type'=>'TEXT'),
		  array("name" => 'targetAns30', 'type'=>'TEXT'),
		  array("name" => 'targetAns31', 'type'=>'TEXT'),
		  array("name" => 'targetAns32', 'type'=>'TEXT'),
		  array("name" => 'targetAns33', 'type'=>'TEXT'),
		  array("name" => 'targetAns34', 'type'=>'TEXT'),
		  array("name" => 'targetAns35', 'type'=>'TEXT'),
		  array("name" => 'targetAns36', 'type'=>'TEXT'),
		  array("name" => 'targetAns37', 'type'=>'TEXT'),
		  array("name" => 'targetAns38', 'type'=>'TEXT'),
		  array("name" => 'targetAns39', 'type'=>'TEXT'),
		  array("name" => 'targetAns40', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns1', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns2', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns3', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns4', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns5', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns6', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns7', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns8', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns9', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns10', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns11', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns12', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns13', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns14', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns15', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns16', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns17', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns18', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns19', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns20', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns21', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns22', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns23', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns24', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns25', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns26', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns27', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns28', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns29', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns30', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns31', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns32', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns33', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns34', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns35', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns36', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns37', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns38', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns39', 'type'=>'TEXT'),
		  array("name" => 'ranTargetAns40', 'type'=>'TEXT'),
		  array("name" => 'answerTime', 'type'=>'INT UNSIGNED NOT NULL DEFAULT 0'),
		  array("name" => 'choice', 'type'=>'TEXT'),		
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
		
		$translation_array = array('please_answer' => __('질문에 응답해주세요.', 'chained'));
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
		define( 'CHAINED_CHOICES', $wpdb->prefix. "chained_choices");
		define( 'CHAINED_TARGETS', $wpdb->prefix. "chained_targets");
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