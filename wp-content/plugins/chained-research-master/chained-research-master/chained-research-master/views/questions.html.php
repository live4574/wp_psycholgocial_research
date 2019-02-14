<div class="wrap">
	<h1><?php printf(__('Manage Questions in %s', 'chained'), $research->title);?> </h1>
	
	<p><a href="admin.php?page=chained_researchzes"><?php _e('Back to researchs', 'chained')?></a>
		| <a href="admin.php?page=chainedresearch_results&research_id=<?php echo $research->id?>"><?php _e('Manage Results', 'chained')?></a>
		| <a href="admin.php?page=chained_researchzes&action=edit&id=<?php echo $research->id?>"><?php _e('Edit This Research', 'chained')?></a>
	</p>
	
	<p><a href="admin.php?page=chainedresearch_questions&action=add&research_id=<?php echo $research->id?>"><?php _e('Click here to add new question', 'chained')?></a></p>
	<?php if(sizeof($questions)):?>
		<table class="widefat">
			<tr><th>#</th><th><?php _e('ID', 'chained')?></th><th><?php _e('Question', 'chained')?></th><th><?php _e('Type', 'chained')?></th>
				<th><?php _e('Edit / Delete', 'chained')?></th></tr>
			<?php foreach($questions as $cnt=>$question):
				$class = ('alternate' == @$class) ? '' : 'alternate';?>
				<tr class="<?php echo $class?>">
					<td><?php if($count > 1 and $cnt):?>
						<a href="admin.php?page=chainedresearch_questions&research_id=<?php echo $research->id?>&move=<?php echo $question->id?>&dir=up"><img src="<?php echo CHAINED_URL."/img/arrow-up.png"?>" alt="<?php _e('Move Up', 'hostelpro')?>" border="0"></a>
					<?php else:?>&nbsp;<?php endif;?>
					<?php if($count > $cnt+1):?>	
						<a href="admin.php?page=chainedresearch_questions&research_id=<?php echo $research->id?>&move=<?php echo $question->id?>&dir=down"><img src="<?php echo CHAINED_URL."/img/arrow-down.png"?>" alt="<?php _e('Move Down', 'hostelpro')?>" border="0"></a>
					<?php else:?>&nbsp;<?php endif;?></td>					
					<td><?php echo $question->id?></td><td><?php echo stripslashes($question->title)?></td>
					<td><?php echo $question->qtype?></td><td><a href="admin.php?page=chainedresearch_questions&action=edit&id=<?php echo $question->id?>"><?php _e('Edit', 'chained')?></a> | <a href="#" onclick="chainedConfirmDelete(<?php echo $question->id?>);return false;"><?php _e('Delete', 'chained')?></a></td>
				</tr>
			<?php endforeach;?>	
		</table>
		
		<h3>Ajou Univ R.</h3>
	

	<?php endif;?>
</div>

<script type="text/javascript" >
function chainedConfirmDelete(qid) {
	if(confirm("<?php _e('Are you sure?', 'chained')?>")) {
		window.location = 'admin.php?page=chainedresearch_questions&research_id=<?php echo $research->id?>&del=1&id='+qid;
	}
}
</script>