<p><textarea rows="3" cols="40" name="<?php echo empty($target->question_id)?'targets[]':'target'.$target->id?>"><?php echo stripslashes(@$target->target_id)?></textarea><?php _e('Points:', 'chained')?> <input type="text" size="4" name="<?php echo empty($target->target_id)?'points[]':'points'.$target->target_id?>" value="<?php echo @$target->target_id?>"> <input type="checkbox" name="<?php echo empty($target->target_id)?'is_correct[]':'is_correct'.$target->target_id?>" value="1" <?php if(!empty($target->target_id)) echo 'checked'?>> <?php _e('Correct answer','chained')?> | <?php _e('When selected go to:', 'chained')?> <select name="<?php echo empty($target->target_id)?'goto[]':'goto'.$target->target_id?>">
				<option value="next"><?php _e('Next question','chained')?></option>
				<option value="finalize" <?php if(!empty($choice->goto) and $choice->goto =='finalize') echo 'selected'?>><?php _e('Finalize research','chained')?></option>
				<?php if(sizeof($other_questions)):?>
					<option disabled><?php _e('- Select question -', 'chained')?></option>
					<?php foreach($other_questions as $other_question):?>
						<option value="<?php echo $other_question->id?>" <?php if(!empty($target->target_id) and $choice->goto == $other_question->id) echo 'selected'?>><?php echo $other_question->title?></option>
					<?php endforeach;?>
				<?php endif;?>
</select> 
<?php if(!empty($target->target_id)):?>
	<input type="checkbox" name="dels[]" value="<?php echo $target->target_id?>"> <?php _e('Delete this target', 'chained')?>
<?php endif;?></p>