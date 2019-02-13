<?php 
class ChainedResearchShortcodes {
	static function research($atts) {
		global $wpdb;
		$research_id = @$atts[0];
		if(empty($research_id) or !is_numeric($research_id)) return __('No research to load', 'chained');
		ob_start();
		ChainedResearchResearchzes :: display($research_id);
		$content = ob_get_clean();
		return $content;
	} // end research()
}