<?php
class ChainedResearchQuestions {
	static function manage() {
 		$action = empty($_GET['action']) ? 'list' : $_GET['action']; 
		switch($action) {
			case 'add':
				self :: add_question();
			break;
			case 'edit': 
				self :: edit_question();
			break;
			case 'list':
			default:
				self :: list_questions();	 
			break;
		}
	} // end manage()
	
	static function add_question() {
		global $wpdb;
		$_question = new ChainedResearchQuestion();
		
		if(!empty($_POST['ok'])) {
			try {
				$_POST['research_id'] = $_GET['research_id'];
				$qid = $_question->add($_POST);		
				$_question->save_choices($_POST, $qid);	
				chained_redirect("admin.php?page=chainedresearch_questions&research_id=".$_GET['research_id']);
			}
			catch(Exception $e) {
				$error = $e->getMessage();
			}
		}
		
		$research = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUIZZES." WHERE id=%d", $_GET['research_id']));
		
		// select other questions for the go-to dropdown
		$other_questions = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE research_id=%d ORDER BY title", $research->id));
		
		include(CHAINED_PATH.'/views/question.html.php');
	} // end add_question
	
	static function edit_question() {
		global $wpdb;
		$_question = new ChainedResearchQuestion();
		
		if(!empty($_POST['ok'])) {
			try {
				$_question->save($_POST, $_GET['id']);
				$_question->save_choices($_POST, $_GET['id']);
			}
			catch(Exception $e) {
				$error = $e->getMessage();
			}
		}
		
		// select the research and question		
		$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", $_GET['id']));
		$research = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUIZZES." WHERE id=%d", $question->research_id));

		// select question choices
		$choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_CHOICES." WHERE question_id=%d ORDER BY id ", $question->id));	
		
		// select other questions for the go-to dropdown
		$other_questions = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." 
			WHERE research_id=%d AND id!=%d ORDER BY title", $research->id, $question->id));	
		
		include(CHAINED_PATH.'/views/question.html.php');
	} // end edit_research
	
	// list and delete questions
	static function list_questions() {
		global $wpdb;
		$_question = new ChainedResearchQuestion();
		
		if(!empty($_GET['del'])) {
			$_question->delete($_GET['id']);			
		}
		
		if(!empty($_GET['move'])) {
			// select question
			$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", $_GET['move']));
			
			if($_GET['dir'] == 'up') {
				$new_order = $question->sort_order - 1;
				if($new_order < 0) $new_order = 0;
				
				// shift others
				$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_QUESTIONS." SET sort_order=sort_order+1 
				  WHERE id!=%d AND sort_order=%d AND research_id=%d", $_GET['move'], $new_order, $_GET['research_id']));
			}
			else {
				$new_order = $question->sort_order+1;			
	
				// shift others
				$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_QUESTIONS." SET sort_order=sort_order-1 
	  				WHERE id!=%d AND sort_order=%d AND research_id=%d", $_GET['move'], $new_order, $_GET['research_id']));
			}
			
			// change this one
			$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_QUESTIONS." SET sort_order=%d WHERE id=%d", 
				$new_order, $_GET['move']));
				
			// redirect 	
			chained_redirect('admin.php?page=chainedresearch_questions&research_id=' . $_GET['research_id']);
		}
		
		$research = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUIZZES." WHERE id=%d", $_GET['research_id']));
		$questions = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE research_id=%d ORDER BY sort_order, id", $_GET['research_id']));
		$count = sizeof($questions);
		include(CHAINED_PATH."/views/questions.html.php");
	} // end list_researchzes
	
	// initially fix sort order of the questions in all researchzes
	// it sets order based on question ID
	static function fix_sort_order_global() {
		global $wpdb;
		
		$researchzes = $wpdb->get_results("SELECT id FROM ".CHAINED_QUIZZES);
		
		foreach($researchzes as $research) {
			$min_id = $wpdb->get_var($wpdb->prepare("SELECT MIN(id) FROM ".CHAINED_QUESTIONS." WHERE research_id=%d", $research->id));
			$min_id--;
			
			$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_QUESTIONS." SET
				sort_order = id - %d WHERE research_id=%d", $min_id, $research->id));
		}
		
	}	// end fix_sort_order_global
}