<?php if(!empty($first_load)):?><div class="chained-research" id="chained-research-div-<?php echo $research->id?>"><?php endif;?>
<form method="post" id="chained-research-form-<?php echo $research->id?>">
	<div class="chained-research-area" id="chained-research-wrap-<?php echo $research->id?>">
		<?php if(!empty($research->email_user) and !is_user_logged_in()):?>
			<div class="chained-research-email">
				<p><label><?php _e('Your email address:', 'chained');?></label> <input type="text" name="chained_email" value="<?php echo @$_POST['chained_email']?>"></p>
			</div>
		<?php endif;?> 
		<div class="chained-research-question">
			<?php echo $_question->display_question($question);?>
		</div>
		
		<div class="chained-research-choices">
				<?php echo $_question->display_choices($question, $choices);?>
		</div>
		
		<div class="chained-research-action">
			<input type="button" id="chained-research-action-<?php echo $research->id?>" value="<?php _e('확인', 'chained')?>" onclick="chainedResearch.goon(<?php echo $research->id?>, '<?php echo admin_url('admin-ajax.php')?>');" disabled="true">
		</div>
	</div>
	<input type="hidden" name="question_id" value="<?php echo $question->id?>">
	<input type="hidden" name="research_id" value="<?php echo $research->id?>">
	<input type="hidden" name="question_type" value="<?php echo $question->qtype?>">
	<input type="hidden" name="points" value="0">
</form>
<?php if(!empty($first_load)):?>
</div>
<script type="text/javascript" >
jQuery(function(){
	chainedResearch.initializeQuestion(<?php echo $research->id?>);	
});
</script><?php endif;?>