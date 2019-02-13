<?php
// results or "grades"
class ChainedResearchResults {
	static function manage() {
		global $wpdb;
 		$_result = new ChainedResearchResult();
		
 		// select Research
		$Research = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".CHAINED_RESEARCHES." WHERE id=%d", $_GET['Research_id']));
 		
 		if(!empty($_POST['add'])) {
 			try {
 				$_POST['Research_id'] = $Research->id;
 				$_result->add($_POST);
 				chained_redirect("admin.php?page=chainedResearch_results&Research_id=".$Research->id);
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
 		$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".CHAINED_RESULTS." WHERE Research_id=%d ORDER BY id", $Research->id));
 		include(CHAINED_PATH."/views/results.html.php");
	} // end manage()
}