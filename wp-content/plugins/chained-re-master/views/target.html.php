<p><textarea rows="3" cols="40" name="<?php echo empty($target->question_id)?'targets[]':'target'.$target->id?>"><?php echo stripslashes(@$target->target_id)?></textarea> <inp
<?php if(!empty($target->target_id)):?>ut type="checkbox" name="<?php echo empty($target->target_id)?'is_target[]':'is_target'.$target->target_id?>" value="1" <?php if(!empty($target->target_id)) echo 'checked'?>> 
	<input type="checkbox" name="dels[]" value="<?php echo $target->target_id?>"> <?php _e('Delete this target', 'chained')?>
<?php endif;?></p>