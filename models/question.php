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
	function display_question($question,$visit) {
		//   only add stripslashes and autop
		global $wpdb, $user_ID;
	    $user_id = empty($user_ID) ? 0 : $user_ID;
	    
		$content = stripslashes($question->question);

		$randomCount=mt_rand(1,10);
		//$sql=${"target".randomCount};
		//데이터베이스에 타겟값에 따라 랜덤값 각 저장해놓고 각 타겟숫자명에 
		//그값으로 저장
		$randomSort=array();
		if($question->target==1){
			array_push($randomSort,$question->target1);
			array_push($randomSort,$question->target2);
			array_push($randomSort,$question->target3);
			array_push($randomSort,$question->target4);
			array_push($randomSort,$question->target5);
			array_push($randomSort,$question->target6);
			array_push($randomSort,$question->target7);
			array_push($randomSort,$question->target8);
			array_push($randomSort,$question->target9);
			array_push($randomSort,$question->target10);
			if($visit==0){ 
				shuffle($randomSort);
			}
		}
		$content = str_replace('{{target1}}', $randomSort[0], $content);
		$content = str_replace('{{target2}}', $randomSort[1], $content);
		$content = str_replace('{{target3}}', $randomSort[2], $content);
		$content = str_replace('{{target4}}', $randomSort[3], $content);
		$content = str_replace('{{target5}}', $randomSort[4], $content);
		$content = str_replace('{{target6}}', $randomSort[5], $content);
		$content = str_replace('{{target7}}', $randomSort[6], $content);
		$content = str_replace('{{target8}}', $randomSort[7], $content);
		$content = str_replace('{{target9}}', $randomSort[8], $content);
		$content = str_replace('{{target10}}', $randomSort[9], $content);
		
		$content = str_replace('{{target11}}', @$question->target11, $content);
		$content = str_replace('{{target12}}', @$question->target12, $content);
		$content = str_replace('{{target13}}', @$question->target13, $content);
		$content = str_replace('{{target14}}', @$question->target14, $content);
		$content = str_replace('{{target15}}', @$question->target15, $content);
		$content = str_replace('{{target16}}', @$question->target16, $content);
		$content = str_replace('{{target17}}', @$question->target17, $content);
		$content = str_replace('{{target18}}', @$question->target18, $content);
		$content = str_replace('{{target19}}', @$question->target19, $content);
		$content = str_replace('{{target20}}', @$question->target20, $content);
		
		$content = str_replace('{{target21}}', @$question->target21, $content);
		$content = str_replace('{{target22}}', @$question->target22, $content);
		$content = str_replace('{{target23}}', @$question->target23, $content);
		$content = str_replace('{{target24}}', @$question->target24, $content);
		$content = str_replace('{{target25}}', @$question->target25, $content);
		$content = str_replace('{{target26}}', @$question->target26, $content);
		$content = str_replace('{{target27}}', @$question->target27, $content);
		$content = str_replace('{{target28}}', @$question->target28, $content);
		$content = str_replace('{{target29}}', @$question->target29, $content);
		$content = str_replace('{{target30}}', @$question->target30, $content);

		$content = str_replace('{{target31}}', @$question->target31, $content);
		$content = str_replace('{{target32}}', @$question->target32, $content);
		$content = str_replace('{{target33}}', @$question->target33, $content);
		$content = str_replace('{{target34}}', @$question->target34, $content);
		$content = str_replace('{{target35}}', @$question->target35, $content);
		$content = str_replace('{{target36}}', @$question->target36, $content);
		$content = str_replace('{{target37}}', @$question->target37, $content);
		$content = str_replace('{{target38}}', @$question->target38, $content);
		$content = str_replace('{{target39}}', @$question->target39, $content);
		$content = str_replace('{{target40}}', @$question->target40, $content);
		
		$content = do_shortcode($content);
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
	function getRandomKeyword($question){
		if($question->target=='1'){
			$randomCount=mt_rand(1,10);
		}else if($question->target=='2'){
			$randomCount=mt_rand(1,10);
		}else if($question->target=='3'){
			$randomCount=mt_rand(1,10);
		}else if($question->target==4){
			$randomCount=mt_rand(1,10);
		}else if($question->target=='5'){
			$randomCount=mt_rand(1,10);
		}else if($question->target=='6'){
			$randomCount=mt_rand(1,10);
		}else if($question->target=='7'){
			$randomCount=mt_rand(1,10);
		}else{
			$randomCount=1;
		}
		
		$sql=@$question->${"target".$randomCount};
		return $sql;
	}
}
