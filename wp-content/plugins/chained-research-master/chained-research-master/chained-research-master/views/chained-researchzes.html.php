<div class="wrap">
	<h1><?php _e('Chained Researchzes', 'chained')?></h1>
	
	<p><a href="admin.php?page=chained_researchzes&action=add"><?php _e('Create new chained research', 'chained')?></a></p>
	
	<?php if(sizeof($researchzes)):?>
	<table class="widefat">
		<tr><th><?php _e('Research title', 'chained')?></th><th><?php _e('Research Shortcode', 'chained')?></th><th><?php _e('Questions', 'chained')?></th>
			<th><?php _e('Results', 'chained')?></th><th><?php _e('Submitted by', 'chained')?></th><th><?php _e('Edit/Delete', 'chained')?></th></tr>
		<?php foreach($researchzes as $research):
			$class = ('alternate' == @$class) ? '' : 'alternate';?>
			<tr class="<?php echo $class?>"><td><?php if(!empty($research->post)) echo "<a href='".get_permalink($research->post->ID)."' target='_blank'>"; 
				echo stripslashes($research->title);
				if(!empty($research->post)) echo "</a>";?></td><td><input type="text" size="12" value="[chained-research <?php echo $research->id?>]" readonly onclick="this.select();"></td>
			<td><a href="admin.php?page=chainedresearch_questions&research_id=<?php echo $research->id?>"><?php _e('Manage', 'chained')?></a></td>
			<td><a href="admin.php?page=chainedresearch_results&research_id=<?php echo $research->id?>"><?php _e('Manage', 'chained')?></a></td>
			<td><?php if($research->submissions):?>
				<a href="admin.php?page=chainedresearch_list&research_id=<?php echo $research->id?>"><?php printf(__('%d users', 'chained'), $research->submissions);?></a>
			<?php else: _e('No users', 'chained');
			endif;?>	</td>
			<td><a href="admin.php?page=chained_researchzes&action=edit&id=<?php echo $research->id?>"><?php _e('Edit', 'chained')?></a>
			| <a href="#" onclick="confirmDelResearch(<?php echo $research->id?>);return false;"><?php _e('Delete', 'chained')?></a></td></tr>
		<?php endforeach;?>	
	</table>
	<p><?php _e('Note: if a research title is not hyperlinked this means you have not published its shortcode yet. You must place the shortcode in a post or page in order to make the research accessible to the public.', 'chained')?></p>
	
	<h3>Did you know?</h3>
	
	<p>Now you can use <a href="http://blog.calendarscripts.info/chained-quiz-logic-free-add-on-for-watupro/" target="_blank">this tool</a> to transfer your researchzes to the best premium research plugin <a href="http://calendarscripts.info/watupro/" target="_blank">WatuPRO</a>. This will give you access to premuim support and a lot of great fatures like user registration, randomizing, categorization, super-high flexibility, lots of question types, and more.</p>
	<?php else:?>
		<p><?php _e('There are no researchzes yet.', 'chained')?></p>
	<?php endif;?>	
</div>

<script type="text/javascript" >
function confirmDelResearch(id) {
	if(confirm("<?php _e('Are you sure?', 'chained')?>")) {
		window.location = 'admin.php?page=chained_researchzes&del=1&id=' + id;
	}
}
</script>