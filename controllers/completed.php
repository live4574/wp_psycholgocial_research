<?php
class ChainedQuizCompleted {
	static function manage() {
		global $wpdb;
		
		// select research
		$quiz = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUIZZES." WHERE id=%d", $_GET['quiz_id']));
		$ob = empty($_GET['ob']) ? 'tC.id' : $_GET['ob'];
		$dir = empty($_GET['dir'])  ? 'desc' : $_GET['dir'];
		
		// select completed records, paginate by 50
		$offset = empty($_GET['offset']) ? 0 : $_GET['offset'];
		$limit_sql = empty($_GET['chained_export']) ? "LIMIT $offset, 25" : ""; 
		
		if(!empty($_GET['del'])) {
			$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_COMPLETED." WHERE id=%d", $_GET['del']));
		}		
		
		if(!empty($_POST['cleanup_all'])) {
			$wpdb->query($wpdb->prepare("DELETE FROM ".CHAINED_COMPLETED." WHERE quiz_id=%d", $quiz->id));
			chained_redirect("admin.php?page=chainedquiz_list&quiz_id=".$quiz->id);	 
		}
		
		$records = $wpdb->get_results( $wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS tC.*, tU.user_nicename as user_nicename, tR.title as result_title
			FROM ".CHAINED_COMPLETED." tC LEFT JOIN ".CHAINED_RESULTS." tR ON tR.id = tC.result_id
			LEFT JOIN {$wpdb->users} tU ON tU.ID = tC.user_id
			WHERE tC.quiz_id=%d AND tC.not_empty=1
			ORDER BY $ob $dir $limit_sql", $quiz->id));
			
		$count = $wpdb->get_var("SELECT FOUND_ROWS()"); 	
		
		// select all the given answers in these records
		$rids = array(0);
		foreach($records as $record) $rids[] = $record->id;		
		$answers = $wpdb->get_results( "SELECT tA.answer as answer, tA.points as points, tQ.question as question,
			tA.completion_id as completion_id, tQ.qtype as qtype 
			FROM ".CHAINED_USER_ANSWERS." tA JOIN ".CHAINED_QUESTIONS." tQ
			ON tQ.id = tA.question_id
			WHERE tA.completion_id IN (" .implode(',', $rids). ") ORDER BY tA.id" ); 
			
		// now for the answers we need to match the textual values of what the user has answered
		$aids = array(0);
		foreach($answers as $answer) {
			$ids = explode(',', $answer->answer);
			
			foreach($ids as $id) {
				if(!empty($id) and !in_array($id, $aids)) $aids[] = $id;
			}
		}	
		
		$choices = $wpdb->get_results("SELECT id, choice FROM ".CHAINED_CHOICES." WHERE id IN (" . implode(',', $aids) . ")");
		
		// now do the match
		foreach($answers as $cnt => $answer) {
			$ids = explode(',', $answer->answer);
			$answer_text = '';
			
			if($answer->qtype == 'text') $answer_text = $answer->answer;
			else { 
				foreach($ids as $id) {
					foreach($choices as $choice) {
						if($choice->id == $id) {
							if(!empty($answer_text)) $answer_text .= ", ";
							$answer_text .= stripslashes($choice->choice);
						}
					} // end foreach choice
				} // end foreach id
			} // end if not textarea	
			
			$answers[$cnt]->answer_text = $answer_text;
		} // end foreach answer
		
		// now match the answers to records
		foreach($records as $cnt=>$record) {
			$record_answers = array();
			
			foreach($answers as $answer) {
				if($record->id == $answer->completion_id) $record_answers[] = $answer;
			}
			
			$records[$cnt] -> details = $record_answers;
		}
		
		$dateformat = get_option('date_format');
		$timeformat = get_option('time_format');
		
		if(!empty($_GET['chained_export'])) {
			$newline=kiboko_define_newline();		
			
			$csv = "";
			$rows=array();
			$rows[]=__("Record ID", 'chained')."\t".__("User name or IP", 'chained')."\t".
				__("Date / time", 'chained')."\t".__("응답 아이디",'chained')."\t".__("B1_01",'chained')."\t".__("B1_02",'chained')."\t".__("B1_03",'chained')."\t".__("B1_04",'chained')."\t".__("B1_05",'chained')."\t".__("B1_06",'chained')."\t".__("B1_07",'chained')."\t".__("B1_08",'chained')."\t".__("B1_09",'chained')."\t".__("B1_10",'chained')."\t".__("B2_01",'chained')."\t".__("B2_02",'chained')."\t".__("B2_03",'chained')."\t".__("B2_04",'chained')."\t".__("B2_05",'chained')."\t".__("B2_06",'chained')."\t".__("B2_07",'chained')."\t".__("B2_08",'chained')."\t".__("B2_09",'chained')."\t".__("B2_10",'chained')."\t".__("B3_01",'chained')."\t".__("B3_02",'chained')."\t".__("B3_03",'chained')."\t".__("B3_04",'chained')."\t".__("B3_05",'chained')."\t".__("B3_06",'chained')."\t".__("B3_07",'chained')."\t".__("B3_08",'chained')."\t".__("B3_09",'chained')."\t".__("B3_10",'chained')."\t".__("B3_11",'chained')."\t".__("B3_12",'chained')."\t".__("B3_13",'chained')."\t".__("B3_014",'chained')."\t".__("B3_15",'chained')."\t".__("B3_16",'chained')."\t".__("B3_17",'chained')."\t".__("B3_18",'chained')."\t".__("B3_19",'chained')."\t".__("B3_20",'chained')."\t".__("B4_01",'chained')."\t".__("B4_02",'chained')."\t".__("B4_03",'chained')."\t".__("B4_05",'chained')."\t".__("B4_06",'chained')."\t".__("B4_07",'chained')."\t".__("B4_08",'chained')."\t".__("B4_09",'chained')."\t".__("B4_10",'chained')."\t".__("B4_11",'chained')."\t".__("B4_12",'chained')."\t".__("B4_13",'chained')."\t".__("B4_14",'chained')."\t".__("B4_15",'chained')."\t".__("B4_16",'chained')."\t".__("B4_17",'chained')."\t".__("B4_18",'chained')."\t".__("B4_19",'chained')."\t".__("B4_20",'chained')."\t".__("B4_21",'chained')."\t".__("B4_22",'chained')."\t".__("B4_23",'chained')."\t".__("B4_24",'chained')."\t".__("B4_25",'chained')."\t".__("B4_26",'chained')."\t".__("B4_27",'chained')."\t".__("B4_28",'chained')."\t".__("B4_29",'chained')."\t".__("B4_30",'chained')."\t".__("B4_31",'chained')."\t".__("B4_32",'chained')."\t".__("B4_33",'chained')."\t".__("B4_34",'chained')."\t".__("B4_35",'chained')."\t".__("B4_36",'chained')."\t".__("B4_37",'chained')."\t".__("B4_38",'chained')."\t".__("B4_39",'chained')."\t".__("B4_40",'chained')."\t".__("B5_01",'chained')."\t".__("B5_02",'chained')."\t".__("B5_03",'chained')."\t".__("B5_04",'chained')."\t".__("B5_05",'chained')."\t".__("B5_06",'chained')."\t".__("B5_07",'chained')."\t".__("B5_08",'chained')."\t".__("B5_09",'chained')."\t".__("B5_10",'chained')."\t".__("B6_01",'chained')."\t".__("B6_02",'chained')."\t".__("B6_03",'chained')."\t".__("B6_04",'chained')."\t".__("B6_05",'chained')."\t".__("B6_06",'chained')."\t".__("B6_07",'chained')."\t".__("B6_08",'chained')."\t".__("B6_09",'chained')."\t".__("B6_10",'chained')."\t".__("B6_11",'chained')."\t".__("B6_12",'chained')."\t".__("B6_13",'chained')."\t".__("B6_14",'chained')."\t".__("B6_15",'chained')."\t".__("B6_16",'chained')."\t".__("B6_17",'chained')."\t".__("B6_18",'chained')."\t".__("B6_19",'chained')."\t".__("B6_20",'chained')."\t".__("B7_01",'chained')."\t".__("B7_02",'chained')."\t".__("B7_03",'chained')."\t".__("B7_04",'chained')."\t".__("B7_05",'chained')."\t".__("B7_06",'chained')."\t".__("B7_07",'chained')."\t".__("B7_08",'chained')."\t".__("B7_09",'chained')."\t".__("B7_10",'chained')."\t".__("B7_11",'chained')."\t".__("B7_12",'chained')."\t".__("B7_13",'chained')."\t".__("B7_14",'chained')."\t".__("B7_15",'chained')."\t".__("B7_16",'chained')."\t".__("B7_17",'chained')."\t".__("B7_18",'chained')."\t".__("B7_19",'chained')."\t".__("B7_20",'chained')."\t".__("B7_21",'chained')."\t".__("B7_22",'chained')."\t".__("B7_23",'chained')."\t".__("B7_24",'chained')."\t".__("B7_25",'chained')."\t".__("B7_26",'chained')."\t".__("B7_27",'chained')."\t".__("B7_28",'chained')."\t".__("B7_29",'chained')."\t".__("B7_30",'chained')."\t".__("B7_31",'chained')."\t".__("B7_32",'chained')."\t".__("B7_33",'chained')."\t".__("B7_34",'chained')."\t".__("B7_35",'chained')."\t".__("B7_36",'chained')."\t".__("B7_37",'chained')."\t".__("B7_38",'chained')."\t".__("B7_39",'chained')."\t".__("B7_40",'chained');
			foreach($records as $record) {
				$row = $record->id . "\t" . (empty($record->user_id) ? $record->ip : $record->user_nicename) 
					. "\t" . date_i18n($dateformat.' '.$timeformat, strtotime($record->datetime)) ;
					foreach($record->details as $detail){
							$row=$row. "\t" . $detail->answer_text;
					}
				$rows[] = $row;		
			} // end foreach taking
			$csv=implode($newline,$rows);		
			
			$now = gmdate('D, d M Y H:i:s') . ' GMT';	
			$filename = 'research-'.$quiz->id.'-results.csv';	
			header('Content-Type: ' . kiboko_get_mime_type());
			header('Expires: ' . $now);
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Pragma: no-cache');
			echo $csv;
			exit;
		}	
			
		include(CHAINED_PATH."/views/completed.html.php");
	} // end manage
	
	// defines whether to sort by ASC or DESC
	static function define_dir($col, $ob, $dir) {		
		if($ob != $col) return $dir;
		
		// else reverse
		if($dir == 'asc') return 'desc';
		else return 'asc'; 
	}
}