<?php
class ChainedResearchResearchzes {
	static function manage() {
 		$action = empty($_GET['action']) ? 'list' : $_GET['action']; 
		switch($action) {
			case 'add':
				self :: add_research();
			break;
			case 'edit': 
				self :: edit_research();
			break;
			case 'list':
			default:
				self :: list_researchzes();	 
			break;
		}
	} // end manage()
	
	static function add_research() {
		$_research = new ChainedResearchResearch();
		
		if(!empty($_POST['ok'])) {
			try {
				$qid = $_research->add($_POST);			
				chained_redirect("admin.php?page=chainedresearch_results&research_id=".$qid);
			}
			catch(Exception $e) {
				$error = $e->getMessage();
			}
		}
		
		$output = __('Congratulations, you completed the <span>research!</span>
<h2>{{result-title}}</h2>
{{result-text}}

You achieved {{points}} points from {{questions}} questions.', 'chained');
		include(CHAINED_PATH.'/views/research.html.php');
	} // end add_research
	
	static function edit_research() {
		global $wpdb;
		$_research = new ChainedResearchResearch();
		
		if(!empty($_POST['ok'])) {
			try {
				$_research->save($_POST, $_GET['id']);			
				chained_redirect("admin.php?page=chained_researchzes");
			}
			catch(Exception $e) {
				$error = $e->getMessage();
			}
		}
		
		// select the research
		$research = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUIZZES." WHERE id=%d", $_GET['id']));
	   $output = stripslashes($research->output); 
		include(CHAINED_PATH.'/views/research.html.php');
	} // end edit_research
	
	// list and delete researchzes
	static function list_researchzes() {
		global $wpdb;
		$_research = new ChainedResearchResearch();
		
		if(!empty($_GET['del'])) {
			$_research->delete($_GET['id']);
			chained_redirect("admin.php?page=chained_researchzes");
		}
		
		// select researchzes
		$researchzes = $wpdb->get_results("SELECT tQ.*, COUNT(tC.id) as submissions 
			FROM ".CHAINED_QUIZZES." tQ LEFT JOIN ".CHAINED_COMPLETED." tC ON tC.research_id = tQ.id AND tC.not_empty=1
			GROUP BY tQ.id ORDER BY tQ.id DESC");
		
		// now select all posts that have watu shortcode in them
		$posts=$wpdb->get_results("SELECT * FROM {$wpdb->posts} 
		WHERE post_content LIKE '%[chained-research %]%' AND post_title!=''
		AND post_status='publish' ORDER BY post_date DESC");	
		
		// match posts to exams
		foreach($researchzes as $cnt=>$research) {
			foreach($posts as $post) {
				if(strstr($post->post_content,"[chained-research ".$research->id."]")) {
					$researchzes[$cnt]->post=$post;			
					break;
				}
			}
		}
		include(CHAINED_PATH."/views/chained-researchzes.html.php");
	} // end list_researchzes	
	
	// displays a research
	static function display($research_id) {
	   global $wpdb, $user_ID;
	   $_question = new ChainedResearchQuestion();
	   
	   // select the research
	   $research = $wpdb -> get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUIZZES." WHERE id=%d", $research_id));
	   if(empty($research->id)) die(__('Research not found', 'chained'));
	   
	   // completion ID already created?
		if(empty($_SESSION['chained_completion_id'])) {			
			$wpdb->query( $wpdb->prepare("INSERT INTO ".CHAINED_COMPLETED." SET
		 		research_id = %d, datetime = NOW(), ip = %s, user_id = %d",
		 		$research->id, $_SERVER['REMOTE_ADDR'], $user_ID));
		 	$_SESSION['chained_completion_id'] = $wpdb->insert_id;	
		}
	   
		 // select the first question
		 $question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE research_id=%d
		 	ORDER BY sort_order, id LIMIT 1", $research->id));
		 
		 // select possible answers
		 $choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_CHOICES." 
		 	WHERE research_id=%d AND question_id=%d ORDER BY id", $research->id, $question->id));
		 			 	
		 $first_load = true;			 	
		 include(CHAINED_PATH."/views/display-research.html.php");
	}

	// answer a question or complete the research
	static function answer_question() {
		global $wpdb, $user_ID;
		$_research = new ChainedResearchResearch();
		$_question = new ChainedResearchQuestion();
		
		// select research
		$research = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUIZZES." WHERE id=%d", $_POST['research_id']));
		
		// select question
		$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", $_POST['question_id']));
		
		// prepare $answer var		
		$answer = ($question->qtype == 'checkbox') ? @$_POST['answers'] : @$_POST['answer'];
		if(empty($answer)) $answer = 0;
				
		// calculate points
		$points = $_question->calculate_points($question, $answer);
		echo $points."|CHAINEDQUIZ|";
		
		// figure out next question
		$next_question = $_question->next($question, $answer);
		
		// store the answer
		if(!empty($_SESSION['chained_completion_id'])) {
			if(is_array($answer)) $answer = implode(",", $answer);

			// make sure to avoid duplicates and only update the answer if it already exists
			$exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".CHAINED_USER_ANSWERS."
				WHERE research_id=%d AND completion_id=%d AND question_id=%d", 
				$research->id, $_SESSION['chained_completion_id'], $question->id));			
			
			if($exists) {
				$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_USER_ANSWERS." SET
					answer=%s, points=%f WHERE research_id=%d AND completion_id=%d AND question_id=%d", 
					$answer, $points, $research->id, $_SESSION['chained_completion_id'], $question->id));
			}
			else {				
				$wpdb->query($wpdb->prepare("INSERT INTO ".CHAINED_USER_ANSWERS." SET
					research_id=%d, completion_id=%d, question_id=%d, answer=%s, points=%f",
					$research->id, $_SESSION['chained_completion_id'], $question->id, $answer, $points));
			}		
			
			// update the "completed" record as non empty
			$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_COMPLETED." SET not_empty=1 WHERE id=%d", $_SESSION['chained_completion_id']));
		}
		
		if(!empty($next_question->id)) {
			$question = $next_question;
			$choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_CHOICES." 
		 	WHERE research_id=%d AND question_id=%d ORDER BY id", $research->id, $question->id));
			include(CHAINED_PATH."/views/display-research.html.php");
		}
		else {
			 // add to points
			 $points += $_POST['points'];
			 echo $_research->finalize($research, $points); // if none, submit the research
		}	 		
	}
}