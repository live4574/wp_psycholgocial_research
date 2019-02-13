<?php
// results or "grades"
class ChainedResearchResults {
	static function manage() {
		global $wpdb;
 		$_result = new ChainedResearchResult();
		
 		// select research
		$research = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_QUIZZES." WHERE id=%d", $_GET['research_id']));
 		
 		if(!empty($_POST['add'])) {
 			try {
 				$_POST['research_id'] = $research->id;
 				$_result->add($_POST);
 				chained_redirect("admin.php?page=chainedresearch_results&research_id=".$research->id);
 			}
 			catch(Exception $e) {
 				$error = __('The result was not added', 'chained');
 			}
 		}
 		
 		if(!empty($_POST['save'])) {
 			try {
 				$_POST['description'] = $_POST['description'.$_POST['id']];
 				$_result->save($_POST, $_POST['id']);
 			}
 			catch(Exception $e) {
 				$error = __('The result was not saved', 'chained');
 			}
 		}
 		
 		if(!empty($_POST['del'])) {
 			try {
 				$_result->delete($_POST['id']);
 			}
 			catch(Exception $e) {
 				$error = __('The result was not deleted', 'chained');
 			}
 		}
 		
 		// select results
 		$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_RESULTS." WHERE research_id=%d ORDER BY id", $research->id));
 		include(CHAINED_PATH."/views/results.html.php");
	} // end manage()
}