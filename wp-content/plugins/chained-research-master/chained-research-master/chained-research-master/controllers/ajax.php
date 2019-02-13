<?php
// handle all ajax
function chainedresearch_ajax() {
	$action = empty($_POST['chainedresearch_action']) ? 'answer' : $_POST['chainedresearch_action'];
	
	switch($action) {
		// answer a question or research
		case 'answer':
		default:
			echo ChainedResearchResearchzes :: answer_question();
		break;
	}

	exit;
}