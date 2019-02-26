<?php
class ChainedQuizQuestion {
	function add($vars) {
		global $wpdb;
		
		// sort order
		$sort_order = $wpdb->get_var($wpdb->prepare("SELECT MAX(sort_order) FROM ".CHAINED_QUESTIONS."
			WHERE quiz_id=%d", $vars['quiz_id']));
		$sort_order++;	 
		
		$result = $wpdb->query($wpdb->prepare("INSERT INTO ".CHAINED_QUESTIONS." SET
			quiz_id=%d, question=%s, qtype=%s, rank=%d, title=%s, autocontinue=%d, sort_order=%d", 
			$vars['quiz_id'], $vars['question'], $vars['qtype'], @$vars['rank'], $vars['title'], 
			@$vars['autocontinue'], $sort_order));
			
		if($result === false) throw new Exception(__('DB Error', 'chained'));
		return $wpdb->insert_id;	
	} // end add
	
	function save($vars, $id) {
		global $wpdb;
		
		$result = $wpdb->query($wpdb->prepare("UPDATE ".CHAINED_QUESTIONS." SET
			question=%s, qtype=%s, title=%s, autocontinue=%d WHERE id=%d", 
			$vars['question'], $vars['qtype'], $vars['title'], @$vars['autocontinue'], $id));
			
			
		if($result === false) throw new Exception(__('DB Error', 'chained'));
		return true;	
	}
	
	function delete($id) {
		global $wpdb;
	
		// delete choices		
		$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_CHOICES." WHERE question_id=%d", $id));
		
		// delete question
		$result = $wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_QUESTIONS." WHERE id=%d", $id));
		
		if($result === false) throw new Exception(__('DB Error', 'chained'));
		return true;	
	}
	
	// saves the choices on a question
	function save_choices($vars, $id) {
		global $wpdb;
		
		// edit/delete existing choices
		$choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_CHOICES." WHERE question_id=%d ORDER BY id ", $id));
		
		foreach($choices as $choice) {
			if(!empty($_POST['dels']) and in_array($choice->id, $_POST['dels'])) {
				$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_CHOICES." WHERE id=%d", $choice->id));
				continue;
			}
			
			// else update
			$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_CHOICES." SET
				choice=%s, points=%s, is_correct=%d, goto=%s WHERE id=%d", 
				$_POST['answer'.$choice->id], $_POST['points'.$choice->id], @$_POST['is_correct'.$choice->id], $_POST['goto'.$choice->id], $choice->id));
		}	
		
		// add new choices
		$counter = 1;
		$correct_array = @$_POST['is_correct'];
		foreach($_POST['answers'] as $answer) {
			$correct = @in_array($counter, $correct_array) ? 1 : 0;
			$counter++;
			if($answer === '') continue;
		
			// now insert the choice
			$wpdb->query( $wpdb->prepare("INSERT INTO ".CHAINED_CHOICES." SET
				question_id=%d, choice=%s, points=%s, is_correct=%d, goto=%s, quiz_id=%d", 
				$id, $answer, $_POST['points'][($counter-2)], $correct, $_POST['goto'][($counter-2)], $_POST['quiz_id']) );
		}
	} // end save_choices

	// displays the question contents
	function display_question($question) {
		//   only add stripslashes and autop
		$content = stripslashes($question->question);
		$content = wpautop($content);
		return $content;
	}

  // displays the possible choices on a question
  function display_choices($question, $choices) {
  	   $autocontinue = '';
  	   if(($question->qtype == 'radio' ||$question->qtype=='button') and $question->autocontinue) {
  	   	$autocontinue = "onclick=\"chainedQuiz.goon(".$question->quiz_id.", '".admin_url('admin-ajax.php')."');\"";
  	   }  	   
  	   
		switch($question->qtype) {
			case 'none':
				return "<div class='chained-quiz-choice' style='display:none'><textarea class='chained-quiz-frontend' name='answer'>none</textarea></div>";
			break;
			case 'text':
				return "<div class='chained-quiz-choice'><textarea class='chained-quiz-frontend' name='answer'></textarea></div>";
			break;
			case 'radio':
			case 'button':
			case 'checkbox':
				$type = $question->qtype;
				$name = (($question->qtype == 'radio') ||($question->qtype =='button'))? "answer": "answers[]"; 


				$output = "";
				foreach($choices as $choice) {
					$choice_text = stripslashes($choice->choice);
						
					if($question->qtype=='button'){
						$output .= "<style>
						.button4 {
							border-radius: 12px;
						}
						.chained-quiz-choice{ 
							display: inline;
							margin-left:20px;
							margin-right:20px;
						}
     					</style>
     					<div class='chained-quiz-choice'><label class='chained-quiz-label'><input class='chained-quiz-frontend chained-quiz-$type' type='$type' style= 'width:80pt; height:80pt; border-radius: 13em/8em' name='$name' value='".$choice_text."' $autocontinue></label></div>";
					}
					else{
						$output .= "<div class='chained-quiz-choice'><label class='chained-quiz-label'><input class='chained-quiz-frontend chained-quiz-$type' type='$type' name='$name' value='".$choice->id."' $autocontinue> $choice_text</label></div>";	
					}
				}
				
				return $output;
			break;
		}
  } // end display_choices
  
  // calculate the points of a given answer
  function calculate_points($question, $answer) {
  	global $wpdb;
  	
  	$ids = array(0);
  	if(is_array($answer)) $ids = array_merge($ids, $answer);
  	else $ids[] = $answer;
  	
  	// select points
  	if($question->qtype != 'text') {
	  	$points = $wpdb->get_var($wpdb->prepare("SELECT SUM(points) FROM ".CHAINED_CHOICES."
	  		WHERE question_id=%d AND id IN (".implode(",", $ids).")", $question->id));
	  }
	  else {
	  	$points = $wpdb->get_var($wpdb->prepare("SELECT points FROM ".CHAINED_CHOICES."
	  		WHERE question_id=%d AND choice LIKE %s", $question->id, $answer));
		}
  	return $points;	
  }
  
  // gets the next question in a quiz, depending on the given answer
  function next($question, $answer) {
 		global $wpdb; 	
 		
		// select answer(s)
		$goto = array();
		$answer_ids = array(0);
		if(is_array($answer)) {
			foreach($answer as $ans) {
				 if(!empty($ans)) $answer_ids[] = $ans;
			}
		} 
		else {
			if($question->qtype == 'text' ||$question->qtype=='none') {
					$answer = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".CHAINED_CHOICES."
	  		  WHERE question_id=%d AND choice LIKE %s", $question->id, $answer));				
			} 
			
			if(!empty($answer)) $answer_ids[] = $answer; // radio buttons and text areas
		} 
		
		
		// select the choices selected by the user		
		$choices = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_CHOICES." 
			WHERE question_id=%d AND id IN (".implode(",", $answer_ids).") ", $question->id));
			
		foreach($choices as $choice) {
			if(isset($goto[$choice->goto])) $goto[$choice->goto]++;
			else $goto[$choice->goto] = 1;			
		}
	  
		// now sort goto to figure out what's the top goto selection	
		arsort($goto);		
		$goto = array_flip($goto);
		$key = array_shift($goto);
		
		//let's treat textareas in different way. If answer is not found, let's not finalize the quiz but go to next
		if(($question->qtype == 'text'||$question->qtype=='none'||$question->qtype=='button') and empty($key)) $key = 'next';
		
		// echo $key.'x'; 
		if(empty($key) or $key == 'finalize') return false;
		
		if($key == 'next') {
			// select next question by sort_order
			$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." 
				WHERE quiz_id=%d AND sort_order > %d ORDER BY sort_order LIMIT 1", $question->quiz_id, $question->sort_order));
			return $question;	
		}
		if($key =='time_next'){
			//show grey x function
			sleep(2); //when select wrong choice wait 2seconds. to show x_grey
			// select next question by sort_order
			$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." 
				WHERE quiz_id=%d AND sort_order > %d ORDER BY sort_order LIMIT 1", $question->quiz_id, $question->sort_order));
			return $question;		
		}
	
	  if(is_numeric($key)) {
	  	$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." 
				WHERE quiz_id=%d AND id=%d LIMIT 1", $question->quiz_id, $key));
			return $question;	
	  }
	
	  // just in case
	  return false;		
	} // end next()
	function showKeywords($question){
	  global $wpdb, $user_ID;
	    
	    $user_id = empty($user_ID) ? 0 : $user_ID;
	    
		 $_result = new ChainedQuizQuestion();
		
		 // get final screen and replace vars
		 $output = stripslashes($question->question);
		 $output = str_replace('{{target1}}', @$question->target1, $output);
		 $output = str_replace('{{target2}}', stripslashes(@$result->description), $output);
		 $output = str_replace('{{target3}}', @$question->target3, $output);
		 $output = str_replace('{{target4}}', @$question->target4, $output);
		 $output = str_replace('{{target5}}', @$question->target5, $output);
		 $output =str_replace('{{target6}}', @$question->target6, $output);
		 $output= str_replace('{{target7}}', @$question->target7, $output);
		 $output= str_replace('{{target8}}', @$question->target8, $output);
		 $output= str_replace('{{target9}}', @$question->target9, $output);
		 $output= str_replace('{{target10}}', @$question->target10, $output);
		 		 
		 
		 $output = do_shortcode($output);
		 $output = wpautop($output);
		
		 return $output;
	}
}?>