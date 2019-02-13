chainedResearch = {};
chainedResearch.points = 0; // initialize the points as 0
chainedResearch.questions_answered = 0;

chainedResearch.goon = function(ResearchID, url) {
	// make sure there is answer selected
	var qType = jQuery('#chained-Research-form-' + ResearchID + ' input[name=question_type]').val();	
	var chkClass = 'chained-Research-' + qType;
	jQuery('#chained-Research-action-' + ResearchID).attr('disabled', true);
	
	// is any checked?
	var anyChecked = false;
	jQuery('#chained-Research-form-' + ResearchID + ' .' + chkClass).each(function(){
		if(this.checked) anyChecked = true; 	
	});
	
	if(!anyChecked && (qType != 'text')) {
		alert(chained_i18n.please_answer);
		jQuery('#chained-Research-action-' + ResearchID).removeAttr('disabled');
		return false;
	}

  if(qType == 'text' && jQuery('#chained-Research-form-' + ResearchID + ' textarea[name=answer]').val() == '') {
  		alert(chained_i18n.please_answer);
  		jQuery('#chained-Research-action-' + ResearchID).removeAttr('disabled');
		return false;
  }
	
	// submit the answer by ajax
	data = jQuery('#chained-Research-form-'+ResearchID).serialize();
	data += '&action=chainedResearch_ajax';
	data += '&chainedResearch_action=answer';
	this.questions_answered++;
	data += '&total_questions=' + this.questions_answered;
	
	// console.log(data);
	jQuery.post(url, data, function(msg) {
		  parts = msg.split("|CHAINEDResearch|");
		  points = parseFloat(parts[0]);
		  if(isNaN(points)) points = 0;
		  chainedResearch.points += points;		  
			
			if(jQuery('body').scrollTop() > 250) {				
				jQuery('html, body').animate({
			   		scrollTop: jQuery('#chained-Research-wrap-'+ResearchID).offset().top -100
			   }, 500);   
			}		  
			
		  jQuery('#chained-Research-action-' + ResearchID).removeAttr('disabled');
		  
		  // redirect?
		  if(parts[1].indexOf('[CHAINED_REDIRECT]') != -1) {
		  	  var sparts = parts[1].split('[CHAINED_REDIRECT]');
		  	  window.location=sparts[1];
		  }
		  
		  jQuery('#chained-Research-div-'+ResearchID).html(parts[1]);		  
		  jQuery('#chained-Research-form-' + ResearchID + ' input[name=points]').val(chainedResearch.points);
		  
		  chainedResearch.initializeQuestion(ResearchID);
	});
}

chainedResearch.initializeQuestion = function(ResearchID) {
	jQuery(".chained-Research-frontend").click(function() {		
		if(this.type == 'radio' || this.type == 'checkbox') {		
			// enable button			
			jQuery('#chained-Research-action-' + ResearchID).removeAttr('disabled');
		}
	});
	
	jQuery(".chained-Research-frontend").keyup(function() {
		if(this.type == 'textarea') {
			jQuery('#chained-Research-action-' + ResearchID).removeAttr('disabled');
		}
	});
}