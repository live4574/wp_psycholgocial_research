<p><textarea rows="3" cols="40" name="<?php echo empty($target->id)?'target_answers[]':'target_answer'.$target->id?>"><?php echo stripslashes(@$target->id)?></textarea>
<?php if(!empty($target->id)):?><input type="checkbox" name="<?php echo empty($target->id)?'is_target[]':'is_target'.$target->id?>" value="1" <?php if(!empty($target->id)) echo 'checked'?>> 
<?php if(!empty($target->id)):?>
	<input type="checkbox" name="dels[]" value="<?php echo $target->id?>"> <?php _e('Delete this target', 'chained')?>
<?php endif;?></p>