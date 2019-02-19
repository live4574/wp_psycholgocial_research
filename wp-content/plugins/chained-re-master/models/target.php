<?php
class ChainedQuizTarget{
	function add($vars){
		global $wpdb;
		$target=$wpdb->query($wpdb->prepare("INSERT INTO ".CHAINED_TARGETS." SET question_id=$d, target_id=$d, target_name=$s",$vars['question_id'],$vars['target_id'],$vars['target_name'] ));
		if($target==false) thow new Exception(__('DB Error'),'chained'));
		return $wpdb->insert_id;
	}
	function save($vars,$id){
		global $wpdb;
		$target=$wpdb->query($wpdb->prepare("UPDATE ".CHAINED_TARGETS." SET question_id=$d, target_id=$d, target_name=$s"),$vars['question_id'],$vars['target_id'],$vars['target_name']),$id);
		if($target==false) throw new Exception(__('DB Error'), 'chained'));
		return true;
	}
	function delete($id){
		global $wpdb;
		$target=$wpdb=>query($wpdb->prepare("DELETE FROM ".CHAINED_TARGETS." WHERE id=%d", $id));
		if($result==false) throw new Exception(__'DB Error', 'chained'));
		return true;
	}
	
	return null;//in case of nohting 
}