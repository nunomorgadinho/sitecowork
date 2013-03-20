jQuery(function() {
	/*
	number of fieldsets
	*/
	var fieldsetCount = jQuery('#formElem').children().length;
	
	/*
	current position of fieldset / navigation link
	*/
	var current 	= 1;
    
	/*
	sum and save the widths of each one of the fieldsets
	set the final sum as the total width of the steps element
	*/
	var stepsWidth	= 0;
    var widths 		= new Array();
	jQuery('#steps .step').each(function(i){
        var jQuerystep 		= jQuery(this);
		widths[i]  		= stepsWidth;
        stepsWidth	 	+= jQuerystep.width();
    });
	jQuery('#steps').width(stepsWidth);
	
	/*
	to avoid problems in IE, focus the first input of the form
	*/
	jQuery('#formElem').children(':first').find(':input:first').focus();	
	
	/*
	show the navigation bar
	*/
	//jQuery('#navigation').show();

	var lock_workplace = 0;
	var lock_plan = 0;
	var lock_desk = 0;
	var lock_life = 0;
	
	jQuery('#workplace').change(function(){
		lock_workplace++;
		unblock_next();
	});
	jQuery('#plan').change(function(){
		lock_plan++;
		unblock_next();
	});
	jQuery('#desk').change(function(){
		lock_desk++;
		unblock_next();
	});
	jQuery('#life').change(function(){
		lock_life++;
		unblock_next();
	});
	
	function unblock_next()
	{
		//console.log(jQuery('#life').val());
		
		if (jQuery('#workplace').val() == "choose workplace")
			lock_workplace = 0;
		if (jQuery('#plan').val() == "choose plan")
			lock_plan = 0;
		if (jQuery('#desk').val() == "choose desk")
			lock_desk = 0;
		if (jQuery('#life').val() == "choose life")
			lock_life = 0;
		
		if (lock_workplace > 0 && lock_plan > 0 && lock_desk > 0 && lock_life > 0 ) {
			jQuery('a.navigate-link').fadeIn(1000);
		} else {
			jQuery('a.navigate-link').fadeOut(100);
		}
	}
	/*
	when clicking on a navigation link 
	the form slides to the corresponding fieldset
	*/
	jQuery('a.navigate-link').hide();
    jQuery('a.navigate-link').bind('click',function(e){
		var jQuerythis	= jQuery(this);
		var prev	= current;
		
		// validate form
		if (jQuery(this).html() == "&gt; send") {
			validateSteps();
			
			if(jQuery('#formElem').data('errors')){
				console.log("form errors");
				return false;
			} 
			
		  	if (jQuery('#formElem').serialize() != "") serializedFormData = jQuery('#formElem').serialize();
		  		
	  		var data = {
		 		action: 'new_submit',
		 		submit_data: serializedFormData
		 	};
	  		
	  		jQuery.post(ajaxurl, data, function(response) {
	  					  			
	  		});
		
		}

		
		jQuerythis.closest('ul').find('li').removeClass('selected');
        jQuerythis.parent().addClass('selected');
		/*
		we store the position of the link
		in the current variable	
		*/
		current = jQuerythis.parent().index() + 1;
		/*
		animate / slide to the next or to the corresponding
		fieldset. The order of the links in the navigation
		is the order of the fieldsets.
		Also, after sliding, we trigger the focus on the first 
		input element of the new fieldset
		If we clicked on the last link (confirmation), then we validate
		all the fieldsets, otherwise we validate the previous one
		before the form slided
		*/
        jQuery('#steps').stop().animate({
            marginLeft: '-' + widths[current-1] + 'px'
        },500,function(){
			if(current == fieldsetCount)
				validateSteps();
			else
				validateStep(prev);
			jQuery('#formElem').children(':nth-child('+ parseInt(current) +')').find(':input:first').focus();	
		});
        e.preventDefault();
    });
	
	/*
	clicking on the tab (on the last input of each fieldset), makes the form
	slide to the next step
	*/
	jQuery('#formElem > fieldset').each(function(){
		var jQueryfieldset = jQuery(this);
		jQueryfieldset.children(':last').find(':input').keydown(function(e){
			if (e.which == 9){
				jQuery('#navigation li:nth-child(' + (parseInt(current)+1) + ') a').click();
				/* force the blur for validation */
				jQuery(this).blur();
				e.preventDefault();
			}
		});
	});
	
	/*
	validates errors on all the fieldsets
	records if the Form has errors in jQuery('#formElem').data()
	*/
	function validateSteps(){
		var FormErrors = false;
		for(var i = 1; i < fieldsetCount; ++i){
			var error = validateStep(i);
			if(error == -1) {
				console.log("falhei o validate no "+i);
				FormErrors = true;
			}
		}
		jQuery('#formElem').data('errors',FormErrors);	
	}
	
	/*
	validates one fieldset
	and returns -1 if errors found, or 1 if not
	*/
	function validateStep(step){
		if(step == fieldsetCount) return;
		
		var error = 1;
		var hasError = false;
		jQuery('#formElem').children(':nth-child('+ parseInt(step) +')').find(':input:not(button)').each(function(){
			var jQuerythis 		= jQuery(this);

			if (step == 1)
				return;
			
			var valueLength = jQuery.trim(jQuerythis.val()).length;
			
			if(valueLength == ''){
				console.log("para o el "+jQuerythis+" o val eh vazio");
				hasError = true;
				jQuerythis.css('background-color','#FFEDEF');
			}
			else
				jQuerythis.css('background-color','#FFFFFF');	
		});
		
		
		
		var jQuerylink = jQuery('#navigation li:nth-child(' + parseInt(step) + ') a');
		jQuerylink.parent().find('.error,.checked').remove();
		
		var valclass = 'checked';
		if(hasError){
			error = -1;
			valclass = 'error';
		}
		jQuery('<span class="'+valclass+'"></span>').insertAfter(jQuerylink);
		
		return error;
	}
	
	/*
	if there are errors don't allow the user to submit
	*/
	jQuery('#registerButton').bind('click',function(){
		if(jQuery('#formElem').data('errors')){
			return false;
		}	
	});
});