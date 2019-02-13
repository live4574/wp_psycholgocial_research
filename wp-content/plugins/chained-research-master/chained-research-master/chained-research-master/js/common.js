chainedResearch = {};
chainedResearch.points = 0; // initialize the points as 0
chainedResearch.questions_answered = 0;

chainedResearch.goon = function(researchID, url) {
	// make sure there is answer selected
	var qType = jQuery('#chained-research-form-' + researchID + ' input[name=question_type]').val();	
	var chkClass = 'chained-research-' + qType;
	jQuery('#chained-research-action-' + researchID).attr('disabled', true);
	
	// is any checked?
	var anyChecked = false;
	jQuery('#chained-research-form-' + researchID + ' .' + chkClass).each(function(){
		if(this.checked) anyChecked = true; 	
	});
	
	if(!anyChecked && (qType != 'text')) {
		alert(chained_i18n.please_answer);
		jQuery('#chained-research-action-' + researchID).removeAttr('disabled');
		return false;
	}

  if(qType == 'text' && jQuery('#chained-research-form-' + researchID + ' textarea[name=answer]').val() == '') {
  		alert(chained_i18n.please_answer);
  		jQuery('#chained-research-action-' + researchID).removeAttr('disabled');
		return false;
  }
	
	// submit the answer by ajax
	data = jQuery('#chained-research-form-'+researchID).serialize();
	data += '&action=chainedresearch_ajax';
	data += '&chainedresearch_action=answer';
	this.questions_answered++;
	data += '&total_questions=' + this.questions_answered;
	
	// console.log(data);
	jQuery.post(url, data, function(msg) {
		  parts = msg.split("|CHAINEDQUIZ|");
		  points = parseFloat(parts[0]);
		  if(isNaN(points)) points = 0;
		  chainedResearch.points += points;		  
			
			if(jQuery('body').scrollTop() > 250) {				
				jQuery('html, body').animate({
			   		scrollTop: jQuery('#chained-research-wrap-'+researchID).offset().top -100
			   }, 500);   
			}		  
			
		  jQuery('#chained-research-action-' + researchID).removeAttr('disabled');
		  
		  // redirect?
		  if(parts[1].indexOf('[CHAINED_REDIRECT]') != -1) {
		  	  var sparts = parts[1].split('[CHAINED_REDIRECT]');
		  	  window.location=sparts[1];
		  }
		  
		  jQuery('#chained-research-div-'+researchID).html(parts[1]);		  
		  jQuery('#chained-research-form-' + researchID + ' input[name=points]').val(chainedResearch.points);
		  
		  chainedResearch.initializeQuestion(researchID);
	});
}

chainedResearch.initializeQuestion = function(researchID) {
	jQuery(".chained-research-frontend").click(function() {		
		if(this.type == 'radio' || this.type == 'checkbox') {		
			// enable button			
			jQuery('#chained-research-action-' + researchID).removeAttr('disabled');
		}
	});
	
	jQuery(".chained-research-frontend").keyup(function() {
		if(this.type == 'textarea') {
			jQuery('#chained-research-action-' + researchID).removeAttr('disabled');
		}
	});
}