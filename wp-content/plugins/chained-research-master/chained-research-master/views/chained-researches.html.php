<div class="wrap">
	<h1><?php _e('Chained Researches', 'chained')?></h1>
	
	<p><a href="admin.php?page=chained_Researches&action=add"><?php _e('Create new chained Research', 'chained')?></a></p>
	
	<?php if(sizeof($Researches)):?>
	<table class="widefat">
		<tr><th><?php _e('Research title', 'chained')?></th><th><?php _e('Research Shortcode', 'chained')?></th><th><?php _e('Questions', 'chained')?></th>
			<th><?php _e('Results', 'chained')?></th><th><?php _e('Submitted by', 'chained')?></th><th><?php _e('Edit/Delete', 'chained')?></th></tr>
		<?php foreach($Researches as $Research):
			$class = ('alternate' == @$class) ? '' : 'alternate';?>
			<tr class="<?php echo $class?>"><td><?php if(!empty($Research->post)) echo "<a href='".get_permalink($Research->post->ID)."' target='_blank'>"; 
				echo stripslashes($Research->title);
				if(!empty($Research->post)) echo "</a>";?></td><td><input type="text" size="12" value="[chained-Research <?php echo $Research->id?>]" readonly onclick="this.select();"></td>
			<td><a href="admin.php?page=chainedResearch_questions&Research_id=<?php echo $Research->id?>"><?php _e('Manage', 'chained')?></a></td>
			<td><a href="admin.php?page=chainedResearch_results&Research_id=<?php echo $Research->id?>"><?php _e('Manage', 'chained')?></a></td>
			<td><?php if($Research->submissions):?>
				<a href="admin.php?page=chainedResearch_list&Research_id=<?php echo $Research->id?>"><?php printf(__('%d users', 'chained'), $Research->submissions);?></a>
			<?php else: _e('No users', 'chained');
			endif;?>	</td>
			<td><a href="admin.php?page=chained_Researches&action=edit&id=<?php echo $Research->id?>"><?php _e('Edit', 'chained')?></a>
			| <a href="#" onclick="confirmDelResearch(<?php echo $Research->id?>);return false;"><?php _e('Delete', 'chained')?></a></td></tr>
		<?php endforeach;?>	
	</table>
	<p><?php _e('Note: if a Research title is not hyperlinked this means you have not published its shortcode yet. You must place the shortcode in a post or page in order to make the Research accessible to the public.', 'chained')?></p>
	
	<h3>Did you know?</h3>
	
	<p>Now you can use <a href="http://blog.calendarscripts.info/chained-Research-logic-free-add-on-for-watupro/" target="_blank">this tool</a> to transfer your Researches to the best premium Research plugin <a href="http://calendarscripts.info/watupro/" target="_blank">WatuPRO</a>. This will give you access to premuim support and a lot of great fatures like user registration, randomizing, categorization, super-high flexibility, lots of question types, and more.</p>
	<?php else:?>
		<p><?php _e('There are no Researches yet.', 'chained')?></p>
	<?php endif;?>	
</div>

<script type="text/javascript" >
function confirmDelResearch(id) {
	if(confirm("<?php _e('Are you sure?', 'chained')?>")) {
		window.location = 'admin.php?page=chained_Researches&del=1&id=' + id;
	}
}
</script>