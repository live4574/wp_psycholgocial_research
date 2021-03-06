<div class="wrap">
	<h1><?php printf(__('Add/Edit Question in "%s"', 'chained'), $quiz->title)?></h1>
	
	<p><a href="admin.php?page=chained_quizzes"><?php _e('Back to researches', 'chained')?></a> | <a href="admin.php?page=chainedquiz_questions&quiz_id=<?php echo $quiz->id?>"><?php _e('Back to questions', 'chained')?></a>
		| <a href="admin.php?page=chainedquiz_results&quiz_id=<?php echo $quiz->id?>"><?php _e('Manage Results', 'chained')?></a>
		| <a href="admin.php?page=chained_quizzes&action=edit&id=<?php echo $quiz->id?>"><?php _e('Edit This Research', 'chained')?></a>
	</p>
	
	<form method="post"">
		<p><label><?php _e('Question title', 'chained')?></label> <input type="text" name="title" size="40" value="<?php echo @$question->title?>"></p>
		<p><label><?php _e('Question contents', 'chained')?></label> <?php echo wp_editor(stripslashes(@$question->question), 'question')?></p>
		<p><label><?php _e('Question type:', 'chained')?></label> <select name="qtype" onchange="(this.value == 'radio' ||this.value=='button') ? jQuery('#chainedAutoContinue').show() : jQuery('#chainedAutoContinue').hide();">
			<option value="none" <?php if(!empty($question->id) and $question->qtype == 'none') echo 'selected'?>><?php _e('None','chained')?></option>
			<option value="button" <?php if(!empty($question->id) and $question->qtype == 'button') echo 'selected'?>><?php _e('Button (one possible answer)','chained')?></option>
		
			<option value="radio" <?php if(!empty($question->id) and $question->qtype == 'radio') echo 'selected'?>><?php _e('Radio buttons (one possible answer)','chained')?></option>
			<option value="checkbox" <?php if(!empty($question->id) and $question->qtype == 'checkbox') echo 'selected'?>><?php _e('Checkboxes (multiple possible answers)','chained')?></option>
			<option value="text" <?php if(!empty($question->id) and $question->qtype == 'text') echo 'selected'?>><?php _e('Text box (open-end, essay question)','chained')?></option>
		</select>
		
		<span id="chainedAutoContinue" style="display:<?php echo (empty($question->id) or $question->qtype == 'radio' or $question->qtype =='button') ? 'inline' : 'none';?>"><input type="checkbox" name="autocontinue" value="1" <?php if(!empty($question->autocontinue)) echo 'checked'?>> <?php _e('선택시 다음으로 자동으로 넘어가짐', 'chained')?></span> </p>
		
		<h3><?php _e('Choices/Answers for this question', 'chained')?></h3>
		
		<p> <input type="button" value="<?php _e('Add more rows', 'chained')?>" onclick="chainedQuizAddChoice();" class="button"></p>
		
		<div id="answerRows">
			<?php if(!empty($choices) and sizeof($choices)):
				foreach($choices as $choice):
					include(CHAINED_PATH."/views/choice.html.php");
				endforeach;
			endif;
			unset($choice);
			include(CHAINED_PATH."/views/choice.html.php");?>
		</div>
		
		<input type="hidden" name="ok" value="1">
		<input type="hidden" name="quiz_id" value="<?php echo $quiz->id?>">
	
	<h3><?php _e('Keywords for this question','chained')?></h3>
		<div id="targetRows">
			<textarea rows="1" cols="15" name="targetGroup"><?php echo $question->target?></textarea> 
			<br>
			<textarea rows="1" cols="15" name="target1"><?php echo $question->target1?></textarea> 
			<textarea rows="1" cols="15" name="target2"><?php echo $question->target2?></textarea>
			<textarea rows="1" cols="15" name="target3"><?php echo $question->target3?></textarea>
			<textarea rows="1" cols="15" name="target4"><?php echo $question->target4?></textarea>
			<textarea rows="1" cols="15" name="target5"><?php echo $question->target5?></textarea>
			<textarea rows="1" cols="15" name="target6"><?php echo $question->target6?></textarea>
			<textarea rows="1" cols="15" name="target7"><?php echo $question->target7?></textarea>
			<textarea rows="1" cols="15" name="target8"><?php echo $question->target8?></textarea>
			<textarea rows="1" cols="15" name="target9"><?php echo $question->target9?></textarea>
			<textarea rows="1" cols="15" name="target10"><?php echo $question->target10?></textarea>
			<br>
			<textarea rows="1" cols="15" name="target11"><?php echo $question->target11?></textarea> 
			<textarea rows="1" cols="15" name="target12"><?php echo $question->target12?></textarea>
			<textarea rows="1" cols="15" name="target13"><?php echo $question->target13?></textarea>
			<textarea rows="1" cols="15" name="target14"><?php echo $question->target14?></textarea>
			<textarea rows="1" cols="15" name="target15"><?php echo $question->target15?></textarea>
			<textarea rows="1" cols="15" name="target16"><?php echo $question->target16?></textarea>
			<textarea rows="1" cols="15" name="target17"><?php echo $question->target17?></textarea>
			<textarea rows="1" cols="15" name="target18"><?php echo $question->target18?></textarea>
			<textarea rows="1" cols="15" name="target19"><?php echo $question->target19?></textarea>
			<textarea rows="1" cols="15" name="target20"><?php echo $question->target20?></textarea>
			<br>
			<textarea rows="1" cols="15" name="target21"><?php echo $question->target21?></textarea> 
			<textarea rows="1" cols="15" name="target22"><?php echo $question->target22?></textarea>
			<textarea rows="1" cols="15" name="target23"><?php echo $question->target23?></textarea>
			<textarea rows="1" cols="15" name="target24"><?php echo $question->target24?></textarea>
			<textarea rows="1" cols="15" name="target25"><?php echo $question->target25?></textarea>
			<textarea rows="1" cols="15" name="target26"><?php echo $question->target26?></textarea>
			<textarea rows="1" cols="15" name="target27"><?php echo $question->target27?></textarea>
			<textarea rows="1" cols="15" name="target28"><?php echo $question->target28?></textarea>
			<textarea rows="1" cols="15" name="target29"><?php echo $question->target29?></textarea>
			<textarea rows="1" cols="15" name="target30"><?php echo $question->target30?></textarea>
			<br>
			<textarea rows="1" cols="15" name="target31"><?php echo $question->target31?></textarea> 
			<textarea rows="1" cols="15" name="target32"><?php echo $question->target32?></textarea>
			<textarea rows="1" cols="15" name="target33"><?php echo $question->target33?></textarea>
			<textarea rows="1" cols="15" name="target34"><?php echo $question->target34?></textarea>
			<textarea rows="1" cols="15" name="target35"><?php echo $question->target35?></textarea>
			<textarea rows="1" cols="15" name="target36"><?php echo $question->target36?></textarea>
			<textarea rows="1" cols="15" name="target37"><?php echo $question->target37?></textarea>
			<textarea rows="1" cols="15" name="target38"><?php echo $question->target38?></textarea>
			<textarea rows="1" cols="15" name="target39"><?php echo $question->target39?></textarea>
			<textarea rows="1" cols="15" name="target40"><?php echo $question->target40?></textarea>
			
		</div>
		<br>
		<h3><?php _e('이 키워드들에 대한 답','chained')?></h3>
		<div id="targetAnswerRows">
			<textarea rows="1" cols="15" name="targetAns1"><?php echo $question->targetAns1?></textarea>
			<textarea rows="1" cols="15" name="targetAns2"><?php echo $question->targetAns2?></textarea>
			<textarea rows="1" cols="15" name="targetAns3"><?php echo $question->targetAns3?></textarea>
			<textarea rows="1" cols="15" name="targetAns4"><?php echo $question->targetAns4?></textarea>
			<textarea rows="1" cols="15" name="targetAns5"><?php echo $question->targetAns5?></textarea>
			<textarea rows="1" cols="15" name="targetAns6"><?php echo $question->targetAns6?></textarea>
			<textarea rows="1" cols="15" name="targetAns7"><?php echo $question->targetAns7?></textarea>
			<textarea rows="1" cols="15" name="targetAns8"><?php echo $question->targetAns8?></textarea>
			<textarea rows="1" cols="15" name="targetAns9"><?php echo $question->targetAns9?></textarea>
			<textarea rows="1" cols="15" name="targetAns10"><?php echo $question->targetAns10?></textarea>
			<br>
			<textarea rows="1" cols="15" name="targetAns11"><?php echo $question->targetAns11?></textarea>
			<textarea rows="1" cols="15" name="targetAns12"><?php echo $question->targetAns12?></textarea>
			<textarea rows="1" cols="15" name="targetAns13"><?php echo $question->targetAns13?></textarea>
			<textarea rows="1" cols="15" name="targetAns14"><?php echo $question->targetAns14?></textarea>
			<textarea rows="1" cols="15" name="targetAns15"><?php echo $question->targetAns15?></textarea>
			<textarea rows="1" cols="15" name="targetAns16"><?php echo $question->targetAns16?></textarea>
			<textarea rows="1" cols="15" name="targetAns17"><?php echo $question->targetAns17?></textarea>
			<textarea rows="1" cols="15" name="targetAns18"><?php echo $question->targetAns18?></textarea>
			<textarea rows="1" cols="15" name="targetAns19"><?php echo $question->targetAns19?></textarea>
			<textarea rows="1" cols="15" name="targetAns20"><?php echo $question->targetAns20?></textarea>
			<br>
			<textarea rows="1" cols="15" name="targetAns21"><?php echo $question->targetAns21?></textarea>
			<textarea rows="1" cols="15" name="targetAns22"><?php echo $question->targetAns22?></textarea>
			<textarea rows="1" cols="15" name="targetAns23"><?php echo $question->targetAns23?></textarea>
			<textarea rows="1" cols="15" name="targetAns24"><?php echo $question->targetAns24?></textarea>
			<textarea rows="1" cols="15" name="targetAns25"><?php echo $question->targetAns25?></textarea>
			<textarea rows="1" cols="15" name="targetAns26"><?php echo $question->targetAns26?></textarea>
			<textarea rows="1" cols="15" name="targetAns27"><?php echo $question->targetAns27?></textarea>
			<textarea rows="1" cols="15" name="targetAns28"><?php echo $question->targetAns28?></textarea>
			<textarea rows="1" cols="15" name="targetAns29"><?php echo $question->targetAns29?></textarea>
			<textarea rows="1" cols="15" name="targetAns30"><?php echo $question->targetAns30?></textarea>
			<br>
			<textarea rows="1" cols="15" name="targetAns31"><?php echo $question->targetAns31?></textarea>
			<textarea rows="1" cols="15" name="targetAns32"><?php echo $question->targetAns32?></textarea>
			<textarea rows="1" cols="15" name="targetAns33"><?php echo $question->targetAns33?></textarea>
			<textarea rows="1" cols="15" name="targetAns34"><?php echo $question->targetAns34?></textarea>
			<textarea rows="1" cols="15" name="targetAns35"><?php echo $question->targetAns35?></textarea>
			<textarea rows="1" cols="15" name="targetAns36"><?php echo $question->targetAns36?></textarea>
			<textarea rows="1" cols="15" name="targetAns37"><?php echo $question->targetAns37?></textarea>
			<textarea rows="1" cols="15" name="targetAns38"><?php echo $question->targetAns38?></textarea>
			<textarea rows="1" cols="15" name="targetAns39"><?php echo $question->targetAns39?></textarea>
			<textarea rows="1" cols="15" name="targetAns40"><?php echo $question->targetAns40?></textarea>
		<?php
		global $wpdb;
		$wpdb->update(
			$wpdb->prefix . "chained_questions",
			array(
				"target"=> $_POST['targetGroup'],
				"target1" => $_POST['target1'],
				"target2" => $_POST['target2'],
				"target3" => $_POST['target3'],
				"target4" => $_POST['target4'],
				"target5" => $_POST['target5'],
				"target6" => $_POST['target6'],
				"target7" => $_POST['target7'],
				"target8" => $_POST['target8'],
				"target9" => $_POST['target9'],
				"target10" => $_POST['target10'],
				"target11" => $_POST['target11'],
				"target12" => $_POST['target12'],
				"target13" => $_POST['target13'],
				"target14" => $_POST['target14'],
				"target15" => $_POST['target15'],
				"target16" => $_POST['target16'],
				"target17" => $_POST['target17'],
				"target18" => $_POST['target18'],
				"target19" => $_POST['target19'],
				"target20" => $_POST['target20'],
				"target21" => $_POST['target21'],
				"target22" => $_POST['target22'],
				"target23" => $_POST['target23'],
				"target24" => $_POST['target24'],
				"target25" => $_POST['target25'],
				"target26" => $_POST['target26'],
				"target27" => $_POST['target27'],
				"target28" => $_POST['target28'],
				"target29" => $_POST['target29'],
				"target30" => $_POST['target30'],
				"target31" => $_POST['target31'],
				"target32" => $_POST['target32'],
				"target33" => $_POST['target33'],
				"target34" => $_POST['target34'],
				"target35" => $_POST['target35'],
				"target36" => $_POST['target36'],
				"target37" => $_POST['target37'],
				"target38" => $_POST['target38'],
				"target39" => $_POST['target39'],
				"target40" => $_POST['target40']
			),
			array('id'=> $question->id),
			array('%d',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
			),
			array('%d')
		);
		$wpdb->update(
			$wpdb->prefix . "chained_questions",
			array(
				"targetAns1" => $_POST['targetAns1'],
				"targetAns2" => $_POST['targetAns2'],
				"targetAns3" => $_POST['targetAns3'],
				"targetAns4" => $_POST['targetAns4'],
				"targetAns5" => $_POST['targetAns5'],
				"targetAns6" => $_POST['targetAns6'],
				"targetAns7" => $_POST['targetAns7'],
				"targetAns8" => $_POST['targetAns8'],
				"targetAns9" => $_POST['targetAns9'],
				"targetAns10" => $_POST['targetAns10'],
				"targetAns11" => $_POST['targetAns11'],
				"targetAns12" => $_POST['targetAns12'],
				"targetAns13" => $_POST['targetAns13'],
				"targetAns14" => $_POST['targetAns14'],
				"targetAns15" => $_POST['targetAns15'],
				"targetAns16" => $_POST['targetAns16'],
				"targetAns17" => $_POST['targetAns17'],
				"targetAns18" => $_POST['targetAns18'],
				"targetAns19" => $_POST['targetAns19'],
				"targetAns20" => $_POST['targetAns20'],
				"targetAns21" => $_POST['targetAns21'],
				"targetAns22" => $_POST['targetAns22'],
				"targetAns23" => $_POST['targetAns23'],
				"targetAns24" => $_POST['targetAns24'],
				"targetAns25" => $_POST['targetAns25'],
				"targetAns26" => $_POST['targetAns26'],
				"targetAns27" => $_POST['targetAns27'],
				"targetAns28" => $_POST['targetAns28'],
				"targetAns29" => $_POST['targetAns29'],
				"targetAns30" => $_POST['targetAns30'],
				"targetAns31" => $_POST['targetAns31'],
				"targetAns32" => $_POST['targetAns32'],
				"targetAns33" => $_POST['targetAns33'],
				"targetAns34" => $_POST['targetAns34'],
				"targetAns35" => $_POST['targetAns35'],
				"targetAns36" => $_POST['targetAns36'],
				"targetAns37" => $_POST['targetAns37'],
				"targetAns38" => $_POST['targetAns38'],
				"targetAns39" => $_POST['targetAns39'],
				"targetAns40" => $_POST['targetAns40']
			),
			array('id'=> $question->id),
			array(
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
			),
			array('%d')
		);
		?>
		<p><input type="submit" value="<?php _e('저장','chained')?>" class="button-primary"></p>
		
	</form>
</div>

<script type="text/javascript" >
var numChoices = 1;
function chainedQuizAddChoice() {
	html = '<?php ob_start();
	include(CHAINED_PATH."/views/choice.html.php");
	$content = ob_get_clean();	
	$content = str_replace("\n", '', $content);
	echo $content; ?>';
	
	// the correct checkbox value
	numChoices++;
	html = html.replace('name="is_correct[]" value="1"', 'name="is_correct[]" value="'+numChoices+'"');
	
	jQuery('#answerRows').append(html);
}

function chainedQuizValidate(frm) {
	if(frm.title.value == '') {
		alert("<?php _e('question title을 입력해주세요', 'chained')?>");
		frm.title.focus();
		return false;
	}
	return true;
}
</script>