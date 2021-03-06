<div class="wrap">
	<h1><?php _e('Chained Researches', 'chained')?></h1>
	
	<p><a href="admin.php?page=chained_quizzes&action=add"><?php _e('Create new chained research', 'chained')?></a></p>
	
	<?php if(sizeof($quizzes)):?>
	<table class="widefat">
		<tr><th><?php _e('Research title', 'chained')?></th><th><?php _e('Research Shortcode', 'chained')?></th><th><?php _e('Questions', 'chained')?></th>
			<th><?php _e('Results', 'chained')?></th><th><?php _e('Submitted by', 'chained')?></th><th><?php _e('Edit/Delete', 'chained')?></th></tr>
		<?php foreach($quizzes as $quiz):
			$class = ('alternate' == @$class) ? '' : 'alternate';?>
			<tr class="<?php echo $class?>"><td><?php if(!empty($quiz->post)) echo "<a href='".get_permalink($quiz->post->ID)."' target='_blank'>"; 
				echo stripslashes($quiz->title);
				if(!empty($quiz->post)) echo "</a>";?></td><td><input type="text" size="12" value="[chained-research <?php echo $quiz->id?>]" readonly onclick="this.select();"></td>
			<td><a href="admin.php?page=chainedquiz_questions&quiz_id=<?php echo $quiz->id?>"><?php _e('Manage', 'chained')?></a></td>
			<td><a href="admin.php?page=chainedquiz_results&quiz_id=<?php echo $quiz->id?>"><?php _e('Manage', 'chained')?></a></td>
			<td><?php if($quiz->submissions):?>
				<a href="admin.php?page=chainedquiz_list&quiz_id=<?php echo $quiz->id?>"><?php printf(__('%d users', 'chained'), $quiz->submissions);?></a>
			<?php else: _e('No users', 'chained');
			endif;?>	</td>
			<td><a href="admin.php?page=chained_quizzes&action=edit&id=<?php echo $quiz->id?>"><?php _e('Edit', 'chained')?></a>
			| <a href="#" onclick="confirmDelQuiz(<?php echo $quiz->id?>);return false;"><?php _e('Delete', 'chained')?></a></td></tr>
		<?php endforeach;?>	
	</table>
	<p><?php _e('Note: published가 안되었다면 shortcode를 사용하시면 됩니다', 'chained')?></p>
	
	<h3>Ajou Univ. Research</h3>
	
	<?php else:?>
		<p><?php _e('There are no researches yet.', 'chained')?></p>
	<?php endif;?>	
</div>

<script type="text/javascript" >
function confirmDelQuiz(id) {
	if(confirm("<?php _e('Are you sure?', 'chained')?>")) {
		window.location = 'admin.php?page=chained_quizzes&del=1&id=' + id;
	}
}
</script>