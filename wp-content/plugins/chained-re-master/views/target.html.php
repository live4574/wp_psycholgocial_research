<p><textarea rows="3" cols="40" name="<?php echo empty($target->id)?'target_answers[]':'target_answer'.$target->id?>"><?php echo stripslashes(@$target->target)?></textarea> 
	<input type="checkbox" name="dels[]" value="<?php echo $target->target_id?>"> <?php _e('Delete this target', 'chained')?>
<?php endif;?></p>