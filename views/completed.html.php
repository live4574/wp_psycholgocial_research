<div class="wrap">
	<h1><?php printf(__('Users who submitted research "%s"', 'chained'), $quiz->title)?></h1>
	<p><a href="admin.php?page=chained_quizzes"><?php _e('Back to researchs', 'chained')?></a> | <a href="admin.php?page=chainedquiz_questions&quiz_id=<?php echo $quiz->id?>"><?php _e('Manage questions', 'chained')?></a>
		| <a href="admin.php?page=chainedquiz_results&quiz_id=<?php echo $quiz->id?>"><?php _e('Manage Results', 'chained')?></a>
		| <a href="admin.php?page=chained_quizzes&action=edit&id=<?php echo $quiz->id?>"><?php _e('Edit This Research', 'chained')?></a>
	</p>
	
	
	<?php if(sizeof($records)):?>
		<p><a href="admin.php?page=chainedquiz_list&quiz_id=<?php echo $quiz->id?>&chained_export=1&noheader=1"><?php _e('CSV 추출', 'chained')?></a>
		<?php _e('(Will export TAB delimited file.)', 'chained')?></p>
		<table class="widefat">
			<tr><th><a href="admin.php?page=chainedquiz_list&quiz_id=<?php echo $quiz->id?>&ob=tC.id&dir=<?php echo self :: define_dir('tC.id', $ob, $dir);?>"><?php _e('Record ID','chained')?></a></th><th><?php _e('User name or IP','chained')?></th><th><a href="admin.php?page=chainedquiz_list&quiz_id=<?php echo $quiz->id?>&ob=datetime&dir=<?php echo self :: define_dir('datetime', $ob, $dir);?>"><?php _e('Date/time','chained')?></a></th>
			<th><?php _e('Delete', 'chained')?></th></tr>
			<?php foreach($records as $record):
				$class = ('alternate' == @$class) ? '' : 'alternate';?>
				<tr class="<?php echo $class?>">				
				<td><?php echo $record->id?></td>
				<td><?php echo empty($record->user_id) ? $record->ip : $record->user_nicename?></td>
				<td><?php echo date_i18n($dateformat.' '.$timeformat, strtotime($record->datetime))?></td>
				<td><?php echo stripslashes($record->result_title);
				if(sizeof($record->details)):?><p><a href="#" onclick="jQuery('#recordDetails<?php echo $record->id?>').toggle();return false;"><?php _e('View details', 'chained');?></a></p><?php endif;?></td>
				<td><a href="#" onclick="chainedQuizDelete(<?php echo $record->id?>);return false;"><?php _e('Delete', 'chained')?></a></td></tr>
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
			<a href="admin.php?page=chainedquiz_list&quiz_id=<?php echo $quiz->id?>&offset=<?php echo ($offset - 25)?>&ob=<?php echo $ob?>&dir=<?php echo $dir?>"><?php _e('previous page', 'chained')?></a>
		<?php endif;?> <?php if($count > ($offset + 25)):?>
			<a href="admin.php?page=chainedquiz_list&quiz_id=<?php echo $quiz->id?>&offset=<?php echo ($offset + 25)?>&ob=<?php echo $ob?>&dir=<?php echo $dir?>"><?php _e('next page', 'chained')?></a> <?php endif;?></p>
			
			<form method="post">
				<p><input type="checkbox" onclick="this.checked ? jQuery('#chainedCleanupButton').show() : jQuery('#chainedCleanupButton').hide();"> <?php _e('데이터 전부 삭제 버튼 보여주기.', 'chained')?></p>
				
				<div id="chainedCleanupButton" style="display:none;">
					<p style="color:red;"><b><?php _e('한번 적용하면 되돌릴수 없습니다!', 'chained')?></b></p>
					<p><input type="submit" name="cleanup_all" value="<?php _e('Cleanup all data', 'chained')?>"></p>				
				</div>
			</form>
			
	
	
	<?php else:?>
		<p><?php _e('No one has submitted this research yet.', 'chained')?></p>
		
	<?php endif;?>
</div>

<script type="text/javascript" >
function chainedQuizDelete(id) {
	if(confirm("<?php _e('Are you sure?', 'chained')?>")) {
		window.location = 'admin.php?page=chainedquiz_list&quiz_id=<?php echo $quiz->id?>&offset=<?php echo $offset?>&ob=<?php echo $ob?>&dir=<?php echo $dir?>&del=' + id;
	}
}	
</script>