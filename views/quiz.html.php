<div class="wrap">
	<h1><?php _e('Add/Edit Chained Research', 'chained')?></h1>
	
	<p><a href="admin.php?page=chained_quizzes"><?php _e('Back to researches', 'chained')?></a>
	<?php if(!empty($quiz->id)):?>
		| <a href="admin.php?page=chainedquiz_questions&quiz_id=<?php echo $quiz->id?>"><?php _e('Manage Questions', 'chained')?></a>
		| <a href="admin.php?page=chainedquiz_results&quiz_id=<?php echo $quiz->id?>"><?php _e('Manage Results / Outcomes', 'chained')?></a>
	<?php endif;?></p>
	
	<form method="post" onsubmit="return validateChainedQuiz(this);">
		<p><label><?php _e('Research Title', 'chained')?></label> <input type="text" name="title" size="60" value="<?php echo @$quiz->title?>"></p>
		
		<p><label><?php _e('Final Output', 'chained')?></label> <?php echo wp_editor($output, 'output')?></p>
		
		<p><?php _e('다음과 같은 변수들이 사용될 수 있습니다.', 'chained')?></p>
		
		<ul>
			<li>{{result-title}} <?php _e('- The result (점수) title', 'chained')?></li>
			<li>{{result-text}} <?php _e('- The result (점수) 텍스트/설명', 'chained')?></li>
			<li>{{points}} <?php _e('- 모은 점수', 'chained')?></li>
			<li>{{questions}} <?php _e('- 총 대답한 질문 수', 'chained')?></li>
		</ul>
		
		<p><input type="submit" value="<?php _e('Save Research', 'chained')?>" class="button-primary"></p>
		<input type="hidden" name="ok" value="1">
	</form>
</div>

<script type="text/javascript" >
function validateChainedQuiz(frm){
	if(frm.title.value == '') {
		alert("<?php _e('Title is required', 'chained')?>");
		frm.title.focus();
		return false;
	}
	
	return true;
}
</script>