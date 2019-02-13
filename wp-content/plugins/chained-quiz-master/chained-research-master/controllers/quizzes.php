<?php
class ChainedResearchResearches {
	static function manage() {
 		$action = empty($_GET['action']) ? 'list' : $_GET['action']; 
		switch($action) {
			case 'add':
				self :: add_Research();
			break;
			case 'edit': 
				self :: edit_Research();
			break;
			case 'list':
			default:
				self :: list_Researches();	 
			break;
		}
	} // end manage()
	
	static function add_Research() {
		$_Research = new ChainedResearchResearch();
		
		if(!empty($_POST['ok'])) {
			try {
				$qid = $_Research->add($_POST);			
				chained_redirect("admin.php?page=chainedResearch_results&Research_id=".$qid);
			}
			catch(Exception $e) {
				$error = $e->getMessage();
			}
		}
		
		$output = __('Congratulations, you completed the <span>Research!</span>
<h2>{{result-title}}</h2>
{{result-text}}

You achieved {{points}} points from {{questions}} questions.', 'chained');
		include(CHAINED_PATH.'/views/Research.html.php');
	} // end add_Research
	
	static function edit_Research() {
		global $wpdb;
		$_Research = new ChainedResearchResearch();
		
		if(!empty($_POST['ok'])) {
			try {
				$_Research->save($_POST, $_GET['id']);			
				chained_redirect("admin.php?page=chained_Researches");
			}
			catch(Exception $e) {
				$error = $e->getMessage();
			}
		}
		
		// select the Research
		$Research = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_RESEARCHES." WHERE id=%d", $_GET['id']));
	   $output = stripslashes($Research->output); 
		include(CHAINED_PATH.'/views/Research.html.php');
	} // end edit_Research
	
	// list and delete Researches
	static function list_Researches() {
		global $wpdb;
		$_Research = new ChainedResearchResearch();
		
		if(!empty($_GET['del'])) {
			$_Research->delete($_GET['id']);
			chained_redirect("admin.php?page=chained_Researches");
		}
		
		// select Researches
		$Researches = $wpdb->get_results("SELECT tQ.*, COUNT(tC.id) as submissions 
			FROM ".CHAINED_RESEARCHES." tQ LEFT JOIN ".CHAINED_COMPLETED." tC ON tC.Research_id = tQ.id AND tC.not_empty=1
			GROUP BY tQ.id ORDER BY tQ.id DESC");
		
		// now select all posts that have watu shortcode in them
		$posts=$wpdb->get_results("SELECT * FROM {$wpdb->posts} 
		WHERE post_content LIKE '%[chained-Research %]%' AND post_title!=''
		AND post_status='publish' ORDER BY post_date DESC");	
		
		// match posts to exams
		foreach($Researches as $cnt=>$Research) {
			foreach($posts as $post) {
				if(strstr($post->post_content,"[chained-Research ".$Research->id."]")) {
					$Researches[$cnt]->post=$post;			
					break;
				}
			}
		}
		include(CHAINED_PATH."/views/chained-Researches.html.php");
	} // end list_Researches	
	
	// displays a Research
	static function display($Research_id) {
	   global $wpdb, $user_ID;
	   $_question = new ChainedResearchQuestion();
	   
	   // select the Research
	   $Research = $wpdb -> get_row($wpdb->prepare("SELECT * FROM ".CHAINED_RESEARCHES." WHERE id=%d", $Research_id));
	   if(empty($Research->id)) die(__('Research not found', 'chained'));
	   
	   // completion ID already created?
		if(empty($_SESSION['chained_completion_id'])) {			
			$wpdb->query( $wpdb->prepare("INSERT INTO ".CHAINED_COMPLETED." SET
		 		Research_id = %d, datetime = NOW(), ip = %s, user_id = %d",
		 		$Research->id, $_SERVER['REMOTE_ADDR'], $user_ID));
		 	$_SESSION['chained_completion_id'] = $wpdb->insert_id;	
		}
	   
		 // select the first question
		 $question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE Research_id=%d
		 	ORDER BY sort_order, id LIMIT 1", $Research->id));
		 
		 // select possible answers
		 $choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_CHOICES." 
		 	WHERE Research_id=%d AND question_id=%d ORDER BY id", $Research->id, $question->id));
		 			 	
		 $first_load = true;			 	
		 include(CHAINED_PATH."/views/display-Research.html.php");
	}

	// answer a question or complete the Research
	static function answer_question() {
		global $wpdb, $user_ID;
		$_Research = new ChainedResearchResearch();
		$_question = new ChainedResearchQuestion();
		
		// select Research
		$Research = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_RESEARCHES." WHERE id=%d", $_POST['Research_id']));
		
		// select question
		$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", $_POST['question_id']));
		
		// prepare $answer var		
		$answer = ($question->qtype == 'checkbox') ? @$_POST['answers'] : @$_POST['answer'];
		if(empty($answer)) $answer = 0;
				
		// calculate points
		$points = $_question->calculate_points($question, $answer);
		echo $points."|CHAINEDResearch|";
		
		// figure out next question
		$next_question = $_question->next($question, $answer);
		
		// store the answer
		if(!empty($_SESSION['chained_completion_id'])) {
			if(is_array($answer)) $answer = implode(",", $answer);

			// make sure to avoid duplicates and only update the answer if it already exists
			$exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".CHAINED_USER_ANSWERS."
				WHERE Research_id=%d AND completion_id=%d AND question_id=%d", 
				$Research->id, $_SESSION['chained_completion_id'], $question->id));			
			
			if($exists) {
				$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_USER_ANSWERS." SET
					answer=%s, points=%f WHERE Research_id=%d AND completion_id=%d AND question_id=%d", 
					$answer, $points, $Research->id, $_SESSION['chained_completion_id'], $question->id));
			}
			else {				
				$wpdb->query($wpdb->prepare("INSERT INTO ".CHAINED_USER_ANSWERS." SET
					Research_id=%d, completion_id=%d, question_id=%d, answer=%s, points=%f",
					$Research->id, $_SESSION['chained_completion_id'], $question->id, $answer, $points));
			}		
			
			// update the "completed" record as non empty
			$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_COMPLETED." SET not_empty=1 WHERE id=%d", $_SESSION['chained_completion_id']));
		}
		
		if(!empty($next_question->id)) {
			$question = $next_question;
			$choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_CHOICES." 
		 	WHERE Research_id=%d AND question_id=%d ORDER BY id", $Research->id, $question->id));
			include(CHAINED_PATH."/views/display-Research.html.php");
		}
		else {
			 // add to points
			 $points += $_POST['points'];
			 echo $_Research->finalize($Research, $points); // if none, submit the Research
		}	 		
	}
}