<p><textarea rows="3" cols="40" name="<?php echo empty($target->question_id)?'targets[]':'target'.$target->id?>"><?php echo stripslashes(@$target->target_id)?></textarea>
<?php if(!empty($target->target_id)):?>
	<input type="checkbox" name="dels[]" value="<?php echo $target->target_id?>"> <?php _e('Delete this target', 'chained')?>
<?php endif;?></p>