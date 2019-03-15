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
		global $wpdb, $user_ID;
		global $sortArray;

	    $user_id = empty($user_ID) ? 0 : $user_ID;
	    
		$content = stripslashes($question->question);

		$randomCount=mt_rand(1,10);
		//$sql=${"target".randomCount};
		//데이터베이스에 타겟값에 따라 랜덤값 각 저장해놓고 각 타겟숫자명에 
		//그값으로 저장
		if($question->target==1){
			if($question->id==5){ 
				array_push($sortArray,$question->target1);
				array_push($sortArray,$question->target2);
				array_push($sortArray,$question->target3);
				array_push($sortArray,$question->target4);
				array_push($sortArray,$question->target5);
				array_push($sortArray,$question->target6);
				array_push($sortArray,$question->target7);
				array_push($sortArray,$question->target8);
				array_push($sortArray,$question->target9);
				array_push($sortArray,$question->target10);
				$originArr=Array();
				array_push($originArr,$question->target1);
				array_push($originArr,$question->target2);
				array_push($originArr,$question->target3);
				array_push($originArr,$question->target4);
				array_push($originArr,$question->target5);
				array_push($originArr,$question->target6);
				array_push($originArr,$question->target7);
				array_push($originArr,$question->target8);
				array_push($originArr,$question->target9);
				array_push($originArr,$question->target10);
				
				shuffle($sortArray);
				$returnArr=Array();
				for($i=0;$i<10;$i++){
					for($j=0;$j<10;$j++){
						if(strcmp($sortArray[$i],$originArr[$j])==0){
							array_push($returnArr, $j);
							break;
						}
					}
				}
				$ansArr=Array();
				array_push($ansArr,$question->targetAns1);
				array_push($ansArr,$question->targetAns2);
				array_push($ansArr,$question->targetAns3);
				array_push($ansArr,$question->targetAns4);
				array_push($ansArr,$question->targetAns5);
				array_push($ansArr,$question->targetAns6);
				array_push($ansArr,$question->targetAns7);
				array_push($ansArr,$question->targetAns8);
				array_push($ansArr,$question->targetAns9);
				array_push($ansArr,$question->targetAns10);

				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTarget1" => $sortArray[0],
						"ranTarget2" => $sortArray[1],
						"ranTarget3" => $sortArray[2],
						"ranTarget4" => $sortArray[3],
						"ranTarget5" => $sortArray[4],
						"ranTarget6" => $sortArray[5],
						"ranTarget7" => $sortArray[6],
						"ranTarget8" => $sortArray[7],
						"ranTarget9" => $sortArray[8],
						"ranTarget10" => $sortArray[9]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTargetAns1" => $ansArr[$returnArr[0]],
						"ranTargetAns2" => $ansArr[$returnArr[1]],
						"ranTargetAns3" => $ansArr[$returnArr[2]],
						"ranTargetAns4" => $ansArr[$returnArr[3]],
						"ranTargetAns5" => $ansArr[$returnArr[4]],
						"ranTargetAns6" => $ansArr[$returnArr[5]],
						"ranTargetAns7" => $ansArr[$returnArr[6]],
						"ranTargetAns8" => $ansArr[$returnArr[7]],
						"ranTargetAns9" => $ansArr[$returnArr[8]],
						"ranTargetAns10" => $ansArr[$returnArr[9]]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
			}
		}
		if($question->target==2){
			if($question->id==66){ 
				array_push($sortArray,$question->target1);
				array_push($sortArray,$question->target2);
				array_push($sortArray,$question->target3);
				array_push($sortArray,$question->target4);
				array_push($sortArray,$question->target5);
				array_push($sortArray,$question->target6);
				array_push($sortArray,$question->target7);
				array_push($sortArray,$question->target8);
				array_push($sortArray,$question->target9);
				array_push($sortArray,$question->target10);
				
				$originArr=Array();
				array_push($originArr,$question->target1);
				array_push($originArr,$question->target2);
				array_push($originArr,$question->target3);
				array_push($originArr,$question->target4);
				array_push($originArr,$question->target5);
				array_push($originArr,$question->target6);
				array_push($originArr,$question->target7);
				array_push($originArr,$question->target8);
				array_push($originArr,$question->target9);
				array_push($originArr,$question->target10);
				
				shuffle($sortArray);
				
				$returnArr=Array();
				for($i=0;$i<10;$i++){
					for($j=0;$j<10;$j++){
						if(strcmp($sortArray[$i],$originArr[$j])==0){
							array_push($returnArr, $j);
							break;
						}
					}
				}
				$ansArr=Array();
				array_push($ansArr,$question->targetAns1);
				array_push($ansArr,$question->targetAns2);
				array_push($ansArr,$question->targetAns3);
				array_push($ansArr,$question->targetAns4);
				array_push($ansArr,$question->targetAns5);
				array_push($ansArr,$question->targetAns6);
				array_push($ansArr,$question->targetAns7);
				array_push($ansArr,$question->targetAns8);
				array_push($ansArr,$question->targetAns9);
				array_push($ansArr,$question->targetAns10);

				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTarget1" => $sortArray[0],
						"ranTarget2" => $sortArray[1],
						"ranTarget3" => $sortArray[2],
						"ranTarget4" => $sortArray[3],
						"ranTarget5" => $sortArray[4],
						"ranTarget6" => $sortArray[5],
						"ranTarget7" => $sortArray[6],
						"ranTarget8" => $sortArray[7],
						"ranTarget9" => $sortArray[8],
						"ranTarget10" => $sortArray[9]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTargetAns1" => $ansArr[$returnArr[0]],
						"ranTargetAns2" => $ansArr[$returnArr[1]],
						"ranTargetAns3" => $ansArr[$returnArr[2]],
						"ranTargetAns4" => $ansArr[$returnArr[3]],
						"ranTargetAns5" => $ansArr[$returnArr[4]],
						"ranTargetAns6" => $ansArr[$returnArr[5]],
						"ranTargetAns7" => $ansArr[$returnArr[6]],
						"ranTargetAns8" => $ansArr[$returnArr[7]],
						"ranTargetAns9" => $ansArr[$returnArr[8]],
						"ranTargetAns10" => $ansArr[$returnArr[9]]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
			}
		}
		if($question->target==3){
			if($question->id==77){ 
				array_push($sortArray,$question->target1);
				array_push($sortArray,$question->target2);
				array_push($sortArray,$question->target3);
				array_push($sortArray,$question->target4);
				array_push($sortArray,$question->target5);
				array_push($sortArray,$question->target6);
				array_push($sortArray,$question->target7);
				array_push($sortArray,$question->target8);
				array_push($sortArray,$question->target9);
				array_push($sortArray,$question->target10);
				array_push($sortArray,$question->target11);
				array_push($sortArray,$question->target12);
				array_push($sortArray,$question->target13);
				array_push($sortArray,$question->target14);
				array_push($sortArray,$question->target15);
				array_push($sortArray,$question->target16);
				array_push($sortArray,$question->target17);
				array_push($sortArray,$question->target18);
				array_push($sortArray,$question->target19);
				array_push($sortArray,$question->target20);
				
				$originArr=Array();
				array_push($originArr,$question->target1);
				array_push($originArr,$question->target2);
				array_push($originArr,$question->target3);
				array_push($originArr,$question->target4);
				array_push($originArr,$question->target5);
				array_push($originArr,$question->target6);
				array_push($originArr,$question->target7);
				array_push($originArr,$question->target8);
				array_push($originArr,$question->target9);
				array_push($originArr,$question->target10);
				array_push($originArr,$question->target11);
				array_push($originArr,$question->target12);
				array_push($originArr,$question->target13);
				array_push($originArr,$question->target14);
				array_push($originArr,$question->target15);
				array_push($originArr,$question->target16);
				array_push($originArr,$question->target17);
				array_push($originArr,$question->target18);
				array_push($originArr,$question->target19);
				array_push($originArr,$question->target20);

				shuffle($sortArray);
				
				$returnArr=Array();
				for($i=0;$i<20;$i++){
					for($j=0;$j<20;$j++){
						if(strcmp($sortArray[$i],$originArr[$j])==0){
							array_push($returnArr, $j);
							break;
						}
					}
				}
				$ansArr=Array();
				array_push($ansArr,$question->targetAns1);
				array_push($ansArr,$question->targetAns2);
				array_push($ansArr,$question->targetAns3);
				array_push($ansArr,$question->targetAns4);
				array_push($ansArr,$question->targetAns5);
				array_push($ansArr,$question->targetAns6);
				array_push($ansArr,$question->targetAns7);
				array_push($ansArr,$question->targetAns8);
				array_push($ansArr,$question->targetAns9);
				array_push($ansArr,$question->targetAns10);
				array_push($ansArr,$question->targetAns11);
				array_push($ansArr,$question->targetAns12);
				array_push($ansArr,$question->targetAns13);
				array_push($ansArr,$question->targetAns14);
				array_push($ansArr,$question->targetAns15);
				array_push($ansArr,$question->targetAns16);
				array_push($ansArr,$question->targetAns17);
				array_push($ansArr,$question->targetAns18);
				array_push($ansArr,$question->targetAns19);
				array_push($ansArr,$question->targetAns20);

				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTarget1" => $sortArray[0],
						"ranTarget2" => $sortArray[1],
						"ranTarget3" => $sortArray[2],
						"ranTarget4" => $sortArray[3],
						"ranTarget5" => $sortArray[4],
						"ranTarget6" => $sortArray[5],
						"ranTarget7" => $sortArray[6],
						"ranTarget8" => $sortArray[7],
						"ranTarget9" => $sortArray[8],
						"ranTarget10" => $sortArray[9],
						"ranTarget11" => $sortArray[10],
						"ranTarget12" => $sortArray[11],
						"ranTarget13" => $sortArray[12],
						"ranTarget14" => $sortArray[13],
						"ranTarget15" => $sortArray[14],
						"ranTarget16" => $sortArray[15],
						"ranTarget17" => $sortArray[16],
						"ranTarget18" => $sortArray[17],
						"ranTarget19" => $sortArray[18],
						"ranTarget20" => $sortArray[19]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTargetAns1" => $ansArr[$returnArr[0]],
						"ranTargetAns2" => $ansArr[$returnArr[1]],
						"ranTargetAns3" => $ansArr[$returnArr[2]],
						"ranTargetAns4" => $ansArr[$returnArr[3]],
						"ranTargetAns5" => $ansArr[$returnArr[4]],
						"ranTargetAns6" => $ansArr[$returnArr[5]],
						"ranTargetAns7" => $ansArr[$returnArr[6]],
						"ranTargetAns8" => $ansArr[$returnArr[7]],
						"ranTargetAns9" => $ansArr[$returnArr[8]],
						"ranTargetAns10" => $ansArr[$returnArr[9]],
						"ranTargetAns11" => $ansArr[$returnArr[10]],
						"ranTargetAns12" => $ansArr[$returnArr[11]],
						"ranTargetAns13" => $ansArr[$returnArr[12]],
						"ranTargetAns14" => $ansArr[$returnArr[13]],
						"ranTargetAns15" => $ansArr[$returnArr[14]],
						"ranTargetAns16" => $ansArr[$returnArr[15]],
						"ranTargetAns17" => $ansArr[$returnArr[16]],
						"ranTargetAns18" => $ansArr[$returnArr[17]],
						"ranTargetAns19" => $ansArr[$returnArr[18]],
						"ranTargetAns20" => $ansArr[$returnArr[19]]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
			}
		}
		if($question->target==4){
			if($question->id==99){ 
				array_push($sortArray,$question->target1);
				array_push($sortArray,$question->target2);
				array_push($sortArray,$question->target3);
				array_push($sortArray,$question->target4);
				array_push($sortArray,$question->target5);
				array_push($sortArray,$question->target6);
				array_push($sortArray,$question->target7);
				array_push($sortArray,$question->target8);
				array_push($sortArray,$question->target9);
				array_push($sortArray,$question->target10);
				array_push($sortArray,$question->target11);
				array_push($sortArray,$question->target12);
				array_push($sortArray,$question->target13);
				array_push($sortArray,$question->target14);
				array_push($sortArray,$question->target15);
				array_push($sortArray,$question->target16);
				array_push($sortArray,$question->target17);
				array_push($sortArray,$question->target18);
				array_push($sortArray,$question->target19);
				array_push($sortArray,$question->target20);
				array_push($sortArray,$question->target21);
				array_push($sortArray,$question->target22);
				array_push($sortArray,$question->target23);
				array_push($sortArray,$question->target24);
				array_push($sortArray,$question->target25);
				array_push($sortArray,$question->target26);
				array_push($sortArray,$question->target27);
				array_push($sortArray,$question->target28);
				array_push($sortArray,$question->target29);
				array_push($sortArray,$question->target30);
				array_push($sortArray,$question->target31);
				array_push($sortArray,$question->target32);
				array_push($sortArray,$question->target33);
				array_push($sortArray,$question->target34);
				array_push($sortArray,$question->target35);
				array_push($sortArray,$question->target36);
				array_push($sortArray,$question->target37);
				array_push($sortArray,$question->target38);
				array_push($sortArray,$question->target39);
				array_push($sortArray,$question->target40);
				
				$originArr=Array();
				array_push($originArr,$question->target1);
				array_push($originArr,$question->target2);
				array_push($originArr,$question->target3);
				array_push($originArr,$question->target4);
				array_push($originArr,$question->target5);
				array_push($originArr,$question->target6);
				array_push($originArr,$question->target7);
				array_push($originArr,$question->target8);
				array_push($originArr,$question->target9);
				array_push($originArr,$question->target10);
				array_push($originArr,$question->target11);
				array_push($originArr,$question->target12);
				array_push($originArr,$question->target13);
				array_push($originArr,$question->target14);
				array_push($originArr,$question->target15);
				array_push($originArr,$question->target16);
				array_push($originArr,$question->target17);
				array_push($originArr,$question->target18);
				array_push($originArr,$question->target19);
				array_push($originArr,$question->target20);
				array_push($originArr,$question->target21);
				array_push($originArr,$question->target22);
				array_push($originArr,$question->target23);
				array_push($originArr,$question->target24);
				array_push($originArr,$question->target25);
				array_push($originArr,$question->target26);
				array_push($originArr,$question->target27);
				array_push($originArr,$question->target28);
				array_push($originArr,$question->target29);
				array_push($originArr,$question->target30);
				array_push($originArr,$question->target31);
				array_push($originArr,$question->target32);
				array_push($originArr,$question->target33);
				array_push($originArr,$question->target34);
				array_push($originArr,$question->target35);
				array_push($originArr,$question->target36);
				array_push($originArr,$question->target37);
				array_push($originArr,$question->target38);
				array_push($originArr,$question->target39);
				array_push($originArr,$question->target40);
				
				shuffle($sortArray);
				
				$returnArr=Array();
				for($i=0;$i<40;$i++){
					for($j=0;$j<40;$j++){
						if(strcmp($sortArray[$i],$originArr[$j])==0){
							array_push($returnArr, $j);
							break;
						}
					}
				}
				$ansArr=Array();
				array_push($ansArr,$question->targetAns1);
				array_push($ansArr,$question->targetAns2);
				array_push($ansArr,$question->targetAns3);
				array_push($ansArr,$question->targetAns4);
				array_push($ansArr,$question->targetAns5);
				array_push($ansArr,$question->targetAns6);
				array_push($ansArr,$question->targetAns7);
				array_push($ansArr,$question->targetAns8);
				array_push($ansArr,$question->targetAns9);
				array_push($ansArr,$question->targetAns10);
				array_push($ansArr,$question->targetAns11);
				array_push($ansArr,$question->targetAns12);
				array_push($ansArr,$question->targetAns13);
				array_push($ansArr,$question->targetAns14);
				array_push($ansArr,$question->targetAns15);
				array_push($ansArr,$question->targetAns16);
				array_push($ansArr,$question->targetAns17);
				array_push($ansArr,$question->targetAns18);
				array_push($ansArr,$question->targetAns19);
				array_push($ansArr,$question->targetAns20);
				array_push($ansArr,$question->targetAns21);
				array_push($ansArr,$question->targetAns22);
				array_push($ansArr,$question->targetAns23);
				array_push($ansArr,$question->targetAns24);
				array_push($ansArr,$question->targetAns25);
				array_push($ansArr,$question->targetAns26);
				array_push($ansArr,$question->targetAns27);
				array_push($ansArr,$question->targetAns28);
				array_push($ansArr,$question->targetAns29);
				array_push($ansArr,$question->targetAns30);
				array_push($ansArr,$question->targetAns31);
				array_push($ansArr,$question->targetAns32);
				array_push($ansArr,$question->targetAns33);
				array_push($ansArr,$question->targetAns34);
				array_push($ansArr,$question->targetAns35);
				array_push($ansArr,$question->targetAns36);
				array_push($ansArr,$question->targetAns37);
				array_push($ansArr,$question->targetAns38);
				array_push($ansArr,$question->targetAns39);
				array_push($ansArr,$question->targetAns40);
				
				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTarget1" => $sortArray[0],
						"ranTarget2" => $sortArray[1],
						"ranTarget3" => $sortArray[2],
						"ranTarget4" => $sortArray[3],
						"ranTarget5" => $sortArray[4],
						"ranTarget6" => $sortArray[5],
						"ranTarget7" => $sortArray[6],
						"ranTarget8" => $sortArray[7],
						"ranTarget9" => $sortArray[8],
						"ranTarget10" => $sortArray[9],
						"ranTarget11" => $sortArray[10],
						"ranTarget12" => $sortArray[11],
						"ranTarget13" => $sortArray[12],
						"ranTarget14" => $sortArray[13],
						"ranTarget15" => $sortArray[14],
						"ranTarget16" => $sortArray[15],
						"ranTarget17" => $sortArray[16],
						"ranTarget18" => $sortArray[17],
						"ranTarget19" => $sortArray[18],
						"ranTarget20" => $sortArray[19],
						"ranTarget21" => $sortArray[20],
						"ranTarget22" => $sortArray[21],
						"ranTarget23" => $sortArray[22],
						"ranTarget24" => $sortArray[23],
						"ranTarget25" => $sortArray[24],
						"ranTarget26" => $sortArray[25],
						"ranTarget27" => $sortArray[26],
						"ranTarget28" => $sortArray[27],
						"ranTarget29" => $sortArray[28],
						"ranTarget30" => $sortArray[29],
						"ranTarget31" => $sortArray[30],
						"ranTarget32" => $sortArray[31],
						"ranTarget33" => $sortArray[32],
						"ranTarget34" => $sortArray[33],
						"ranTarget35" => $sortArray[34],
						"ranTarget36" => $sortArray[35],
						"ranTarget37" => $sortArray[36],
						"ranTarget38" => $sortArray[37],
						"ranTarget39" => $sortArray[38],
						"ranTarget40" => $sortArray[39]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTargetAns1" => $ansArr[$returnArr[0]],
						"ranTargetAns2" => $ansArr[$returnArr[1]],
						"ranTargetAns3" => $ansArr[$returnArr[2]],
						"ranTargetAns4" => $ansArr[$returnArr[3]],
						"ranTargetAns5" => $ansArr[$returnArr[4]],
						"ranTargetAns6" => $ansArr[$returnArr[5]],
						"ranTargetAns7" => $ansArr[$returnArr[6]],
						"ranTargetAns8" => $ansArr[$returnArr[7]],
						"ranTargetAns9" => $ansArr[$returnArr[8]],
						"ranTargetAns10" => $ansArr[$returnArr[9]],
						"ranTargetAns11" => $ansArr[$returnArr[10]],
						"ranTargetAns12" => $ansArr[$returnArr[11]],
						"ranTargetAns13" => $ansArr[$returnArr[12]],
						"ranTargetAns14" => $ansArr[$returnArr[13]],
						"ranTargetAns15" => $ansArr[$returnArr[14]],
						"ranTargetAns16" => $ansArr[$returnArr[15]],
						"ranTargetAns17" => $ansArr[$returnArr[16]],
						"ranTargetAns18" => $ansArr[$returnArr[17]],
						"ranTargetAns19" => $ansArr[$returnArr[18]],
						"ranTargetAns20" => $ansArr[$returnArr[19]],
						"ranTargetAns21" => $ansArr[$returnArr[20]],
						"ranTargetAns22" => $ansArr[$returnArr[21]],
						"ranTargetAns23" => $ansArr[$returnArr[22]],
						"ranTargetAns24" => $ansArr[$returnArr[23]],
						"ranTargetAns25" => $ansArr[$returnArr[24]],
						"ranTargetAns26" => $ansArr[$returnArr[25]],
						"ranTargetAns27" => $ansArr[$returnArr[26]],
						"ranTargetAns28" => $ansArr[$returnArr[27]],
						"ranTargetAns29" => $ansArr[$returnArr[28]],
						"ranTargetAns30" => $ansArr[$returnArr[29]],
						"ranTargetAns31" => $ansArr[$returnArr[30]],
						"ranTargetAns32" => $ansArr[$returnArr[31]],
						"ranTargetAns33" => $ansArr[$returnArr[32]],
						"ranTargetAns34" => $ansArr[$returnArr[33]],
						"ranTargetAns35" => $ansArr[$returnArr[34]],
						"ranTargetAns36" => $ansArr[$returnArr[35]],
						"ranTargetAns37" => $ansArr[$returnArr[36]],
						"ranTargetAns38" => $ansArr[$returnArr[37]],
						"ranTargetAns39" => $ansArr[$returnArr[38]],
						"ranTargetAns40" => $ansArr[$returnArr[39]]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
			}
		}
		if($question->target==5){
			if($question->id==140){ 
				array_push($sortArray,$question->target1);
				array_push($sortArray,$question->target2);
				array_push($sortArray,$question->target3);
				array_push($sortArray,$question->target4);
				array_push($sortArray,$question->target5);
				array_push($sortArray,$question->target6);
				array_push($sortArray,$question->target7);
				array_push($sortArray,$question->target8);
				array_push($sortArray,$question->target9);
				array_push($sortArray,$question->target10);
				
				$originArr=Array();
				array_push($originArr,$question->target1);
				array_push($originArr,$question->target2);
				array_push($originArr,$question->target3);
				array_push($originArr,$question->target4);
				array_push($originArr,$question->target5);
				array_push($originArr,$question->target6);
				array_push($originArr,$question->target7);
				array_push($originArr,$question->target8);
				array_push($originArr,$question->target9);
				array_push($originArr,$question->target10);
				
				shuffle($sortArray);
				
				$returnArr=Array();
				for($i=0;$i<10;$i++){
					for($j=0;$j<10;$j++){
						if(strcmp($sortArray[$i],$originArr[$j])==0){
							array_push($returnArr, $j);
							break;
						}
					}
				}
				$ansArr=Array();
				array_push($ansArr,$question->targetAns1);
				array_push($ansArr,$question->targetAns2);
				array_push($ansArr,$question->targetAns3);
				array_push($ansArr,$question->targetAns4);
				array_push($ansArr,$question->targetAns5);
				array_push($ansArr,$question->targetAns6);
				array_push($ansArr,$question->targetAns7);
				array_push($ansArr,$question->targetAns8);
				array_push($ansArr,$question->targetAns9);
				array_push($ansArr,$question->targetAns10);

				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTarget1" => $sortArray[0],
						"ranTarget2" => $sortArray[1],
						"ranTarget3" => $sortArray[2],
						"ranTarget4" => $sortArray[3],
						"ranTarget5" => $sortArray[4],
						"ranTarget6" => $sortArray[5],
						"ranTarget7" => $sortArray[6],
						"ranTarget8" => $sortArray[7],
						"ranTarget9" => $sortArray[8],
						"ranTarget10" => $sortArray[9]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTargetAns1" => $ansArr[$returnArr[0]],
						"ranTargetAns2" => $ansArr[$returnArr[1]],
						"ranTargetAns3" => $ansArr[$returnArr[2]],
						"ranTargetAns4" => $ansArr[$returnArr[3]],
						"ranTargetAns5" => $ansArr[$returnArr[4]],
						"ranTargetAns6" => $ansArr[$returnArr[5]],
						"ranTargetAns7" => $ansArr[$returnArr[6]],
						"ranTargetAns8" => $ansArr[$returnArr[7]],
						"ranTargetAns9" => $ansArr[$returnArr[8]],
						"ranTargetAns10" => $ansArr[$returnArr[9]]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
			}
		}
		if($question->target==6){
			if($question->id==151){ 
				array_push($sortArray,$question->target1);
				array_push($sortArray,$question->target2);
				array_push($sortArray,$question->target3);
				array_push($sortArray,$question->target4);
				array_push($sortArray,$question->target5);
				array_push($sortArray,$question->target6);
				array_push($sortArray,$question->target7);
				array_push($sortArray,$question->target8);
				array_push($sortArray,$question->target9);
				array_push($sortArray,$question->target10);
				array_push($sortArray,$question->target11);
				array_push($sortArray,$question->target12);
				array_push($sortArray,$question->target13);
				array_push($sortArray,$question->target14);
				array_push($sortArray,$question->target15);
				array_push($sortArray,$question->target16);
				array_push($sortArray,$question->target17);
				array_push($sortArray,$question->target18);
				array_push($sortArray,$question->target19);
				array_push($sortArray,$question->target20);
				
				$originArr=Array();
				array_push($originArr,$question->target1);
				array_push($originArr,$question->target2);
				array_push($originArr,$question->target3);
				array_push($originArr,$question->target4);
				array_push($originArr,$question->target5);
				array_push($originArr,$question->target6);
				array_push($originArr,$question->target7);
				array_push($originArr,$question->target8);
				array_push($originArr,$question->target9);
				array_push($originArr,$question->target10);
				array_push($originArr,$question->target11);
				array_push($originArr,$question->target12);
				array_push($originArr,$question->target13);
				array_push($originArr,$question->target14);
				array_push($originArr,$question->target15);
				array_push($originArr,$question->target16);
				array_push($originArr,$question->target17);
				array_push($originArr,$question->target18);
				array_push($originArr,$question->target19);
				array_push($originArr,$question->target20);
				
				shuffle($sortArray);
				
				$returnArr=Array();
				for($i=0;$i<20;$i++){
					for($j=0;$j<20;$j++){
						if(strcmp($sortArray[$i],$originArr[$j])==0){
							array_push($returnArr, $j);
							break;
						}
					}
				}
				$ansArr=Array();
				array_push($ansArr,$question->targetAns1);
				array_push($ansArr,$question->targetAns2);
				array_push($ansArr,$question->targetAns3);
				array_push($ansArr,$question->targetAns4);
				array_push($ansArr,$question->targetAns5);
				array_push($ansArr,$question->targetAns6);
				array_push($ansArr,$question->targetAns7);
				array_push($ansArr,$question->targetAns8);
				array_push($ansArr,$question->targetAns9);
				array_push($ansArr,$question->targetAns10);
				array_push($ansArr,$question->targetAns11);
				array_push($ansArr,$question->targetAns12);
				array_push($ansArr,$question->targetAns13);
				array_push($ansArr,$question->targetAns14);
				array_push($ansArr,$question->targetAns15);
				array_push($ansArr,$question->targetAns16);
				array_push($ansArr,$question->targetAns17);
				array_push($ansArr,$question->targetAns18);
				array_push($ansArr,$question->targetAns19);
				array_push($ansArr,$question->targetAns20);

				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTarget1" => $sortArray[0],
						"ranTarget2" => $sortArray[1],
						"ranTarget3" => $sortArray[2],
						"ranTarget4" => $sortArray[3],
						"ranTarget5" => $sortArray[4],
						"ranTarget6" => $sortArray[5],
						"ranTarget7" => $sortArray[6],
						"ranTarget8" => $sortArray[7],
						"ranTarget9" => $sortArray[8],
						"ranTarget10" => $sortArray[9],
						"ranTarget11" => $sortArray[10],
						"ranTarget12" => $sortArray[11],
						"ranTarget13" => $sortArray[12],
						"ranTarget14" => $sortArray[13],
						"ranTarget15" => $sortArray[14],
						"ranTarget16" => $sortArray[15],
						"ranTarget17" => $sortArray[16],
						"ranTarget18" => $sortArray[17],
						"ranTarget19" => $sortArray[18],
						"ranTarget20" => $sortArray[19]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTargetAns1" => $ansArr[$returnArr[0]],
						"ranTargetAns2" => $ansArr[$returnArr[1]],
						"ranTargetAns3" => $ansArr[$returnArr[2]],
						"ranTargetAns4" => $ansArr[$returnArr[3]],
						"ranTargetAns5" => $ansArr[$returnArr[4]],
						"ranTargetAns6" => $ansArr[$returnArr[5]],
						"ranTargetAns7" => $ansArr[$returnArr[6]],
						"ranTargetAns8" => $ansArr[$returnArr[7]],
						"ranTargetAns9" => $ansArr[$returnArr[8]],
						"ranTargetAns10" => $ansArr[$returnArr[9]],
						"ranTargetAns11" => $ansArr[$returnArr[10]],
						"ranTargetAns12" => $ansArr[$returnArr[11]],
						"ranTargetAns13" => $ansArr[$returnArr[12]],
						"ranTargetAns14" => $ansArr[$returnArr[13]],
						"ranTargetAns15" => $ansArr[$returnArr[14]],
						"ranTargetAns16" => $ansArr[$returnArr[15]],
						"ranTargetAns17" => $ansArr[$returnArr[16]],
						"ranTargetAns18" => $ansArr[$returnArr[17]],
						"ranTargetAns19" => $ansArr[$returnArr[18]],
						"ranTargetAns20" => $ansArr[$returnArr[19]]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
			}
		}
		if($question->target==7){
			if($question->id==173){ 
				array_push($sortArray,$question->target1);
				array_push($sortArray,$question->target2);
				array_push($sortArray,$question->target3);
				array_push($sortArray,$question->target4);
				array_push($sortArray,$question->target5);
				array_push($sortArray,$question->target6);
				array_push($sortArray,$question->target7);
				array_push($sortArray,$question->target8);
				array_push($sortArray,$question->target9);
				array_push($sortArray,$question->target10);
				array_push($sortArray,$question->target11);
				array_push($sortArray,$question->target12);
				array_push($sortArray,$question->target13);
				array_push($sortArray,$question->target14);
				array_push($sortArray,$question->target15);
				array_push($sortArray,$question->target16);
				array_push($sortArray,$question->target17);
				array_push($sortArray,$question->target18);
				array_push($sortArray,$question->target19);
				array_push($sortArray,$question->target20);
				array_push($sortArray,$question->target21);
				array_push($sortArray,$question->target22);
				array_push($sortArray,$question->target23);
				array_push($sortArray,$question->target24);
				array_push($sortArray,$question->target25);
				array_push($sortArray,$question->target26);
				array_push($sortArray,$question->target27);
				array_push($sortArray,$question->target28);
				array_push($sortArray,$question->target29);
				array_push($sortArray,$question->target30);
				array_push($sortArray,$question->target31);
				array_push($sortArray,$question->target32);
				array_push($sortArray,$question->target33);
				array_push($sortArray,$question->target34);
				array_push($sortArray,$question->target35);
				array_push($sortArray,$question->target36);
				array_push($sortArray,$question->target37);
				array_push($sortArray,$question->target38);
				array_push($sortArray,$question->target39);
				array_push($sortArray,$question->target40);
				
				$originArr=Array();
				array_push($originArr,$question->target1);
				array_push($originArr,$question->target2);
				array_push($originArr,$question->target3);
				array_push($originArr,$question->target4);
				array_push($originArr,$question->target5);
				array_push($originArr,$question->target6);
				array_push($originArr,$question->target7);
				array_push($originArr,$question->target8);
				array_push($originArr,$question->target9);
				array_push($originArr,$question->target10);
				array_push($originArr,$question->target11);
				array_push($originArr,$question->target12);
				array_push($originArr,$question->target13);
				array_push($originArr,$question->target14);
				array_push($originArr,$question->target15);
				array_push($originArr,$question->target16);
				array_push($originArr,$question->target17);
				array_push($originArr,$question->target18);
				array_push($originArr,$question->target19);
				array_push($originArr,$question->target20);
				array_push($originArr,$question->target21);
				array_push($originArr,$question->target22);
				array_push($originArr,$question->target23);
				array_push($originArr,$question->target24);
				array_push($originArr,$question->target25);
				array_push($originArr,$question->target26);
				array_push($originArr,$question->target27);
				array_push($originArr,$question->target28);
				array_push($originArr,$question->target29);
				array_push($originArr,$question->target30);
				array_push($originArr,$question->target31);
				array_push($originArr,$question->target32);
				array_push($originArr,$question->target33);
				array_push($originArr,$question->target34);
				array_push($originArr,$question->target35);
				array_push($originArr,$question->target36);
				array_push($originArr,$question->target37);
				array_push($originArr,$question->target38);
				array_push($originArr,$question->target39);
				array_push($originArr,$question->target40);
				
				shuffle($sortArray);
				
				$returnArr=Array();
				for($i=0;$i<40;$i++){
					for($j=0;$j<40;$j++){
						if(strcmp($sortArray[$i],$originArr[$j])==0){
							array_push($returnArr, $j);
							break;
						}
					}
				}
				$ansArr=Array();
			    array_push($ansArr,$question->targetAns1);
				array_push($ansArr,$question->targetAns2);
				array_push($ansArr,$question->targetAns3);
				array_push($ansArr,$question->targetAns4);
				array_push($ansArr,$question->targetAns5);
				array_push($ansArr,$question->targetAns6);
				array_push($ansArr,$question->targetAns7);
				array_push($ansArr,$question->targetAns8);
				array_push($ansArr,$question->targetAns9);
				array_push($ansArr,$question->targetAns10);
				array_push($ansArr,$question->targetAns11);
				array_push($ansArr,$question->targetAns12);
				array_push($ansArr,$question->targetAns13);
				array_push($ansArr,$question->targetAns14);
				array_push($ansArr,$question->targetAns15);
				array_push($ansArr,$question->targetAns16);
				array_push($ansArr,$question->targetAns17);
				array_push($ansArr,$question->targetAns18);
				array_push($ansArr,$question->targetAns19);
				array_push($ansArr,$question->targetAns20);
				array_push($ansArr,$question->targetAns21);
				array_push($ansArr,$question->targetAns22);
				array_push($ansArr,$question->targetAns23);
				array_push($ansArr,$question->targetAns24);
				array_push($ansArr,$question->targetAns25);
				array_push($ansArr,$question->targetAns26);
				array_push($ansArr,$question->targetAns27);
				array_push($ansArr,$question->targetAns28);
				array_push($ansArr,$question->targetAns29);
				array_push($ansArr,$question->targetAns30);
				array_push($ansArr,$question->targetAns31);
				array_push($ansArr,$question->targetAns32);
				array_push($ansArr,$question->targetAns33);
				array_push($ansArr,$question->targetAns34);
				array_push($ansArr,$question->targetAns35);
				array_push($ansArr,$question->targetAns36);
				array_push($ansArr,$question->targetAns37);
				array_push($ansArr,$question->targetAns38);
				array_push($ansArr,$question->targetAns39);
				array_push($ansArr,$question->targetAns40);

				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTarget1" => $sortArray[0],
						"ranTarget2" => $sortArray[1],
						"ranTarget3" => $sortArray[2],
						"ranTarget4" => $sortArray[3],
						"ranTarget5" => $sortArray[4],
						"ranTarget6" => $sortArray[5],
						"ranTarget7" => $sortArray[6],
						"ranTarget8" => $sortArray[7],
						"ranTarget9" => $sortArray[8],
						"ranTarget10" => $sortArray[9],
						"ranTarget11" => $sortArray[10],
						"ranTarget12" => $sortArray[11],
						"ranTarget13" => $sortArray[12],
						"ranTarget14" => $sortArray[13],
						"ranTarget15" => $sortArray[14],
						"ranTarget16" => $sortArray[15],
						"ranTarget17" => $sortArray[16],
						"ranTarget18" => $sortArray[17],
						"ranTarget19" => $sortArray[18],
						"ranTarget20" => $sortArray[19],
						"ranTarget21" => $sortArray[20],
						"ranTarget22" => $sortArray[21],
						"ranTarget23" => $sortArray[22],
						"ranTarget24" => $sortArray[23],
						"ranTarget25" => $sortArray[24],
						"ranTarget26" => $sortArray[25],
						"ranTarget27" => $sortArray[26],
						"ranTarget28" => $sortArray[27],
						"ranTarget29" => $sortArray[28],
						"ranTarget30" => $sortArray[29],
						"ranTarget31" => $sortArray[30],
						"ranTarget32" => $sortArray[31],
						"ranTarget33" => $sortArray[32],
						"ranTarget34" => $sortArray[33],
						"ranTarget35" => $sortArray[34],
						"ranTarget36" => $sortArray[35],
						"ranTarget37" => $sortArray[36],
						"ranTarget38" => $sortArray[37],
						"ranTarget39" => $sortArray[38],
						"ranTarget40" => $sortArray[39]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
				$wpdb->update(
					$wpdb->prefix . "chained_questions",
					array(
						"ranTargetAns1" => $ansArr[$returnArr[0]],
						"ranTargetAns2" => $ansArr[$returnArr[1]],
						"ranTargetAns3" => $ansArr[$returnArr[2]],
						"ranTargetAns4" => $ansArr[$returnArr[3]],
						"ranTargetAns5" => $ansArr[$returnArr[4]],
						"ranTargetAns6" => $ansArr[$returnArr[5]],
						"ranTargetAns7" => $ansArr[$returnArr[6]],
						"ranTargetAns8" => $ansArr[$returnArr[7]],
						"ranTargetAns9" => $ansArr[$returnArr[8]],
						"ranTargetAns10" => $ansArr[$returnArr[9]],
						"ranTargetAns11" => $ansArr[$returnArr[10]],
						"ranTargetAns12" => $ansArr[$returnArr[11]],
						"ranTargetAns13" => $ansArr[$returnArr[12]],
						"ranTargetAns14" => $ansArr[$returnArr[13]],
						"ranTargetAns15" => $ansArr[$returnArr[14]],
						"ranTargetAns16" => $ansArr[$returnArr[15]],
						"ranTargetAns17" => $ansArr[$returnArr[16]],
						"ranTargetAns18" => $ansArr[$returnArr[17]],
						"ranTargetAns19" => $ansArr[$returnArr[18]],
						"ranTargetAns20" => $ansArr[$returnArr[19]],
						"ranTargetAns21" => $ansArr[$returnArr[20]],
						"ranTargetAns22" => $ansArr[$returnArr[21]],
						"ranTargetAns23" => $ansArr[$returnArr[22]],
						"ranTargetAns24" => $ansArr[$returnArr[23]],
						"ranTargetAns25" => $ansArr[$returnArr[24]],
						"ranTargetAns26" => $ansArr[$returnArr[25]],
						"ranTargetAns27" => $ansArr[$returnArr[26]],
						"ranTargetAns28" => $ansArr[$returnArr[27]],
						"ranTargetAns29" => $ansArr[$returnArr[28]],
						"ranTargetAns30" => $ansArr[$returnArr[29]],
						"ranTargetAns31" => $ansArr[$returnArr[30]],
						"ranTargetAns32" => $ansArr[$returnArr[31]],
						"ranTargetAns33" => $ansArr[$returnArr[32]],
						"ranTargetAns34" => $ansArr[$returnArr[33]],
						"ranTargetAns35" => $ansArr[$returnArr[34]],
						"ranTargetAns36" => $ansArr[$returnArr[35]],
						"ranTargetAns37" => $ansArr[$returnArr[36]],
						"ranTargetAns38" => $ansArr[$returnArr[37]],
						"ranTargetAns39" => $ansArr[$returnArr[38]],
						"ranTargetAns40" => $ansArr[$returnArr[39]]
					),
					array('id'=> $question->id),
					array('%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
						  '%s',
					),
					array('%d')
				);
			}
		}
		//$retrieve_question = $wpdb->get_row( "SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", 6 ); 
		//6번째 id question 가져와서서
		if($question->target==1){
			$retrieve_question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", 5));
		}
		else if($question->target==2){
			$retrieve_question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", 66));
		}
		else if($question->target==3){
			$retrieve_question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", 77));
		}
		else if($question->target==4){
			$retrieve_question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", 99));
		}
		else if($queston->target==5){
			$retrieve_question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", 140));
		}
		else if($question->target==6){
			$retrieve_question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", 151));
		}
		else if($question->target==7){
			$retrieve_question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", 173));
		}
		if($question->id==141 or $question->id==142 or $question->id==143 or $question->id==144 or $question->id==145 or $question->id==146 or $question->id==147 or $question->id==148 or $question->id==149 or $question->id==150){
			$retrieve_question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." WHERE id=%d", 140));
		}

		$content = str_replace('{{target1}}', @$retrieve_question->ranTarget1, $content);
		$content = str_replace('{{target2}}', @$retrieve_question->ranTarget2, $content);
		$content = str_replace('{{target3}}', @$retrieve_question->ranTarget3, $content);
		$content = str_replace('{{target4}}', @$retrieve_question->ranTarget4, $content);
		$content = str_replace('{{target5}}', @$retrieve_question->ranTarget5, $content);
		$content = str_replace('{{target6}}', @$retrieve_question->ranTarget6, $content);
		$content = str_replace('{{target7}}', @$retrieve_question->ranTarget7, $content);
		$content = str_replace('{{target8}}', @$retrieve_question->ranTarget8, $content);
		$content = str_replace('{{target9}}', @$retrieve_question->ranTarget9, $content);
		$content = str_replace('{{target10}}', @$retrieve_question->ranTarget10, $content);
		
		$content = str_replace('{{target11}}', @$retrieve_question->ranTarget11, $content);
		$content = str_replace('{{target12}}', @$retrieve_question->ranTarget12, $content);
		$content = str_replace('{{target13}}', @$retrieve_question->ranTarget13, $content);
		$content = str_replace('{{target14}}', @$retrieve_question->ranTarget14, $content);
		$content = str_replace('{{target15}}', @$retrieve_question->ranTarget15, $content);
		$content = str_replace('{{target16}}', @$retrieve_question->ranTarget16, $content);
		$content = str_replace('{{target17}}', @$retrieve_question->ranTarget17, $content);
		$content = str_replace('{{target18}}', @$retrieve_question->ranTarget18, $content);
		$content = str_replace('{{target19}}', @$retrieve_question->ranTarget19, $content);
		$content = str_replace('{{target20}}', @$retrieve_question->ranTarget20, $content);
		
		$content = str_replace('{{target21}}', @$retrieve_question->ranTarget21, $content);
		$content = str_replace('{{target22}}', @$retrieve_question->ranTarget22, $content);
		$content = str_replace('{{target23}}', @$retrieve_question->ranTarget23, $content);
		$content = str_replace('{{target24}}', @$retrieve_question->ranTarget24, $content);
		$content = str_replace('{{target25}}', @$retrieve_question->ranTarget25, $content);
		$content = str_replace('{{target26}}', @$retrieve_question->ranTarget26, $content);
		$content = str_replace('{{target27}}', @$retrieve_question->ranTarget27, $content);
		$content = str_replace('{{target28}}', @$retrieve_question->ranTarget28, $content);
		$content = str_replace('{{target29}}', @$retrieve_question->ranTarget29, $content);
		$content = str_replace('{{target30}}', @$retrieve_question->ranTarget30, $content);
		
		$content = str_replace('{{target31}}', @$retrieve_question->ranTarget31, $content);
		$content = str_replace('{{target32}}', @$retrieve_question->ranTarget32, $content);
		$content = str_replace('{{target33}}', @$retrieve_question->ranTarget33, $content);
		$content = str_replace('{{target34}}', @$retrieve_question->ranTarget34, $content);
		$content = str_replace('{{target35}}', @$retrieve_question->ranTarget35, $content);
		$content = str_replace('{{target36}}', @$retrieve_question->ranTarget36, $content);
		$content = str_replace('{{target37}}', @$retrieve_question->ranTarget37, $content);
		$content = str_replace('{{target38}}', @$retrieve_question->ranTarget38, $content);
		$content = str_replace('{{target39}}', @$retrieve_question->ranTarget39, $content);
		$content = str_replace('{{target40}}', @$retrieve_question->ranTarget40, $content);
	
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
							text-align:center;
						}
     					</style>
     					<div class='chained-quiz-choice'><label class='chained-quiz-label'><input class='chained-quiz-frontend chained-quiz-$type' type='button' style= 'width:80pt; height:80pt; border-radius: 13em/8em' name='$name' value='".$choice->id."' $autocontinue>$choice_text</label></div>
     					<script type=\"text/javascript\">
     					function postdata(link) {
							$.ajax({
							    url: link,
							    type: \"POST\",
							    data: {
							        date:$(link).val();
							    }
							})
							}
     					</script>";
					}
					else{
						$output .= "<div class='chained-quiz-choice'><label class='chained-quiz-label'><input class='chained-quiz-frontend chained-quiz-$type' type='$type' name='$name' value='".$choice->id."' $autocontinue>$choice_text</label></div>";	
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
			if($question->qtype=='button'){
				//$answer=<script>document.answer.value</script>;
			}
			echo $answer;
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
	
	
	  if(is_numeric($key)) {
	  	$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUESTIONS." 
				WHERE quiz_id=%d AND id=%d LIMIT 1", $question->quiz_id, $key));
			return $question;	
	  }
	
	  // just in case
	  return false;		
	} // end next()

}
