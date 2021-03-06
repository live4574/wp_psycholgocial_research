<?php if(!empty($first_load)):?><div class="chained-quiz" id="chained-quiz-div-<?php echo $quiz->id?>"><?php endif;?>
<form method="post" id="chained-quiz-form-<?php echo $quiz->id?>" name="myform">
	<div class="chained-quiz-area" id="chained-quiz-wrap-<?php echo $quiz->id?>">
		<?php if(!empty($quiz->email_user) and !is_user_logged_in()):?>
			<div class="chained-quiz-email">
				<p><label><?php _e('Your email address:', 'chained');?></label> <input type="text" name="chained_email" value="<?php echo @$_POST['chained_email']?>"></p>
			</div>
		<?php endif;?>
	
		<div class="chained-quiz-question">
			<?php echo $_question->display_question($question);?>
		</div>
		
		<div class="chained-quiz-choices">
				<?php echo $_question->display_choices($question, $choices);?>
		</div>
	
		<?php if($question->qtype!='button'){?>
		<div class="chained-quiz-action">
			<p align="right">
				<input type="button" id="chained-quiz-action-<?php echo $quiz->id?>" value="<?php _e('확인', 'chained')?>" onclick="chainedQuiz.goon(<?php echo $quiz->id?>, '<?php echo admin_url('admin-ajax.php')?>');">
		    </p>
		</div>
		<?php }?>
		
	</div>
	<input type="hidden" name="question_id" value="<?php echo $question->id?>">
	<input type="hidden" name="quiz_id" value="<?php echo $quiz->id?>">
	<input type="hidden" name="question_type" value="<?php echo $question->qtype?>">
	<input type="hidden" name="points" value="0">
	<input type="hidden" name= "postvar" id="postvar" value="<?php echo $question->choice?>">
	
</form>
<?php if(!empty($first_load)):?>
</div>
<script type="text/javascript" >
var btns = document.querySelectorAll('input');
	//console.log(btns);
btns[0].addEventListener('click', updateBtn0);
btns[1].addEventListener('click', updateBtn1);

jQuery(function(){
	chainedQuiz.initializeQuestion(<?php echo $quiz->id?>);	
});
function showImage(){
	 document.getElementById("imageX").style.display="block";
	 sleep(0.3);
};

function updateBtn0() {
  var btns = document.querySelectorAll('input');
	//console.log(btns);
	btns[0].addEventListener('click', updateBtn0);
	btns[1].addEventListener('click', updateBtn1);

  if (btns[0].value == 'L') {
    var postValue='L';
    document.getElementById('postvar').value='L';
     $.ajax( {
	        type: 'POST',
	        url: "./wp-content/plugins/wp_psycholgocial_research/controllers/quizzes.php", //file where like unlike status change in database
	        data:{postValue},
	        success: function(data) {
	            //code you want to do after successfull process
	            console.log("L post to postvar successed");
	        }
	    } );
	
    console.log(postValue);
    document.getElementById('postvar').value='L';
  } 
}
function updateBtn1() {
 	var btns = document.querySelectorAll('input');
	console.log(btns);
	btns[0].addEventListener('click', updateBtn0);
	btns[1].addEventListener('click', updateBtn1);

  if (btns[1].value == 'R') {
    var postValue='R';
    document.getElementById("postvar").value='R';
	 $.ajax( {
	        type: 'POST',
	        url: "./wp-content/plugins/wp_psycholgocial_research/controllers/quizzes.php", //file where like unlike status change in database
	        data:{postValue},
	        success: function(data) {
	            //code you want to do after successfull process

	            console.log("R post to postvar successed");
	        }
	    } );
		
    console.log(postValue);
    document.getElementById('postvar').value='R';
  } 
}
</script><?php endif;?>