<div class="wrap">
	<h1><?php printf(__('Users who submitted Research "%s"', 'chained'), $Research->title)?></h1>
	<p><a href="admin.php?page=chained_Researches"><?php _e('Back to Researches', 'chained')?></a> | <a href="admin.php?page=chainedResearch_questions&Research_id=<?php echo $Research->id?>"><?php _e('Manage questions', 'chained')?></a>
		| <a href="admin.php?page=chainedResearch_results&Research_id=<?php echo $Research->id?>"><?php _e('Manage Results', 'chained')?></a>
		| <a href="admin.php?page=chained_Researches&action=edit&id=<?php echo $Research->id?>"><?php _e('Edit This Research', 'chained')?></a>
	</p>
	
		<h3 align="center"><a href="http://wordpress.org/support/view/plugin-reviews/chained-Research" target="_blank"><?php _e('Please rate us 5 Stars!', 'chained')?></a></h3>
	
	<?php if(sizeof($records)):?>
		<p><a href="admin.php?page=chainedResearch_list&Research_id=<?php echo $Research->id?>&chained_export=1&noheader=1"><?php _e('Export CSV', 'chained')?></a>
		<?php _e('(Will export TAB delimited file.)', 'chained')?></p>
		<table class="widefat">
			<tr><th><a href="admin.php?page=chainedResearch_list&Research_id=<?php echo $Research->id?>&ob=tC.id&dir=<?php echo self :: define_dir('tC.id', $ob, $dir);?>"><?php _e('Record ID','chained')?></a></th><th><?php _e('User name or IP','chained')?></th><th><a href="admin.php?page=chainedResearch_list&Research_id=<?php echo $Research->id?>&ob=datetime&dir=<?php echo self :: define_dir('datetime', $ob, $dir);?>"><?php _e('Date/time','chained')?></a></th>
			<th><a href="admin.php?page=chainedResearch_list&Research_id=<?php echo $Research->id?>&ob=points&dir=<?php echo self :: define_dir('points', $ob, $dir);?>"><?php _e('Points','chained')?></a></th><th><a href="admin.php?page=chainedResearch_list&Research_id=<?php echo $Research->id?>&ob=result_title&dir=<?php echo self :: define_dir('result_title', $ob, $dir);?>"><?php _e('Result','chained')?></a></th>
			<th><?php _e('Delete', 'chained')?></th></tr>
			<?php foreach($records as $record):
				$class = ('alternate' == @$class) ? '' : 'alternate';?>
				<tr class="<?php echo $class?>">				
				<td><?php echo $record->id?></td>
				<td><?php echo empty($record->user_id) ? $record->ip : $record->user_nicename?></td>
				<td><?php echo date_i18n($dateformat.' '.$timeformat, strtotime($record->datetime))?></td>
				<td><?php echo $record->points?></td><td><?php echo stripslashes($record->result_title);
				if(sizeof($record->details)):?><p><a href="#" onclick="jQuery('#recordDetails<?php echo $record->id?>').toggle();return false;"><?php _e('View details', 'chained');?></a></p><?php endif;?></td>
				<td><a href="#" onclick="chainedResearchDelete(<?php echo $record->id?>);return false;"><?php _e('Delete', 'chained')?></a></td></tr>
				<?php if(sizeof($record->details)):?>
					<tr class="<?php echo $class?>" id="recordDetails<?php echo $record->id?>" style="display:none;">
						<td colspan="6">
							<table  width="100%"><tr><th><?php _e('Question', 'chained')?></th><th><?php _e('Answer', 'chained')?></th>
								<th><?php _e('Points', 'chained')?></th></tr>
							<?php foreach($record->details as $detail):?>
								<tr style="background:#EEE;"><td><?php echo stripslashes($detail->question)?></td><td><?php echo $detail->answer_text;?></td>
									<td><?php echo $detail->points?></td></tr>
							<?php endforeach;?>	
							</table>						
						</td>	
					</tr>
				<?php endif;?>				
			<?php endforeach;?>
		</table>
		
		<p align="center"><?php if($offset > 0):?>
			<a href="admin.php?page=chainedResearch_list&Research_id=<?php echo $Research->id?>&offset=<?php echo ($offset - 25)?>&ob=<?php echo $ob?>&dir=<?php echo $dir?>"><?php _e('previous page', 'chained')?></a>
		<?php endif;?> <?php if($count > ($offset + 25)):?>
			<a href="admin.php?page=chainedResearch_list&Research_id=<?php echo $Research->id?>&offset=<?php echo ($offset + 25)?>&ob=<?php echo $ob?>&dir=<?php echo $dir?>"><?php _e('next page', 'chained')?></a> <?php endif;?></p>
			
			<form method="post">
				<p><input type="checkbox" onclick="this.checked ? jQuery('#chainedCleanupButton').show() : jQuery('#chainedCleanupButton').hide();"> <?php _e('Show me a button to cleanup all submitted data on this Research.', 'chained')?></p>
				
				<div id="chainedCleanupButton" style="display:none;">
					<p style="color:red;"><b><?php _e('These operations cannot be undone!', 'chained')?></b></p>
					<p><input type="submit" name="cleanup_all" value="<?php _e('Cleanup all data', 'chained')?>"></p>				
				</div>
			</form>
			
			<h3>Did you know?</h3>
	
		<p>Now you can use <a href="http://blog.calendarscripts.info/chained-Research-logic-free-add-on-for-watupro/" target="_blank">this tool</a> to transfer your Researches to the best premium Research plugin <a href="http://calendarscripts.info/watupro/" target="_blank">WatuPRO</a>. This will give you access to premuim support and a lot of great fatures like user registration, randomizing, categorization, super-high flexibility, lots of question types, and more.</p>
	<?php else:?>
		<p><?php _e('No one has submitted this Research yet.', 'chained')?></p>
		
	<?php endif;?>
</div>

<script type="text/javascript" >
function chainedResearchDelete(id) {
	if(confirm("<?php _e('Are you sure?', 'chained')?>")) {
		window.location = 'admin.php?page=chainedResearch_list&Research_id=<?php echo $Research->id?>&offset=<?php echo $offset?>&ob=<?php echo $ob?>&dir=<?php echo $dir?>&del=' + id;
	}
}	
</script>