jQuery(document).ready(function($) {
	var supportsvideo = supportsVideo();
	var min_w = 480; // minimum video width allowed
	var vid_w_orig;  // original video dimensions
	var vid_h_orig;
	
	if ( supportsvideo.ogg || supportsvideo.h264 || supportsvideo.webm ) {
		/*jQuery.post( rec.get_video_background, {
			'width'   : screen.width,
			'height'  : screen.height
		},function(data){
			jQuery('body').append(data.html);
			vid_w_orig = parseInt(jQuery('video').attr('width'));
		    vid_h_orig = parseInt(jQuery('video').attr('height'));
		    jQuery(window).resize(function () { resizeToCover(); });
		    jQuery(window).trigger('resize');
		}, 'json' );	*/
	}
	
	function supportsVideo() {
        var elem = document.createElement('video'),
            bool = false;

        // IE9 Running on Windows Server SKU can cause an exception to be thrown, bug #224
        try {
            if ( bool = !!elem.canPlayType ) {
                bool      = new Boolean(bool);
                bool.ogg  = elem.canPlayType('video/ogg; codecs="theora"');

                // Workaround required for IE9, which doesn't report video support without audio codec specified.
                //   bug 599718 @ msft connect
                var h264 = 'video/mp4; codecs="avc1.42E01E';
                bool.h264 = elem.canPlayType(h264 + '"') || elem.canPlayType(h264 + ', mp4a.40.2"');

                bool.webm = elem.canPlayType('video/webm; codecs="vp8, vorbis"');
            }

        } catch(e) { }

        return bool;
	}
	
	function resizeToCover() {
    
	    // set the video viewport to the window size
	    jQuery('.video-viewport').width(jQuery(window).width());
	    jQuery('.video-viewport').height(jQuery(window).height());
	
	    // use largest scale factor of horizontal/vertical
	    var scale_h = jQuery(window).width() / vid_w_orig;
	    var scale_v = jQuery(window).height() / vid_h_orig;
	    var scale = scale_h > scale_v ? scale_h : scale_v;
	
	    // don't allow scaled width < minimum video width
	    if (scale * vid_w_orig < min_w) {scale = min_w / vid_w_orig;};
	
	    // now scale the video
	    jQuery('video').width(scale * vid_w_orig + 150 );
	    jQuery('video').height(scale * vid_h_orig);
	    // and center it by scrolling the video viewport
	    jQuery('.video-viewport').scrollLeft((jQuery('video').width() - jQuery(window).width()) / 2);
	    jQuery('.video-viewport').scrollTop((jQuery('video').height() - jQuery(window).height()) / 2);
	    
	};
	
	
	jQuery('#success_msg').hide();
	jQuery('#error_msg').hide();
	
	jQuery('#request_invite').submit(function(event){
		
		event.preventDefault();
		var email = jQuery('#email_2').attr('value');
		
		
		var data = {
				action: 'request_invite',
				user_email: email
			 };
		  	
		  	jQuery.post(ajaxurl, data, function(response) {
			  	//empty		
		  		
		  		var j= response;
		  		if(j)
		  			{
		  			jQuery('#error_msg').html(j);
		  			jQuery('#error_msg').fadeIn(500);
		  			console.log(j);
		  			}
		  		else
		  			{

		  			jQuery('#request_invite').fadeOut(500);
		  			jQuery('#success_msg').fadeIn(500);
		  			}
		  	});
		
		return false;
	
	});
	
	/* Landing Page Phase 2 */
	jQuery('#competition_compete').click(function(e){
		e.preventDefault();
		
		jQuery('.competition_form_landing').stop().animate({
			'margin-left':'-800px'
		}, 'slow' );
		
		jQuery('.competition_form_register').stop().animate({
			'margin-left':'0',
			'margin-top': '-430px'
		}, 'slow' );
		
	});
	
	jQuery('#competition_back').click(function(e){
		e.preventDefault();
		
		jQuery('.competition_form_landing').stop().animate({
			'margin-left':'0'
		}, 'slow' );
		
		jQuery('.competition_form_register').stop().animate({
			'margin-left':'800px',
			'margin-top': '-430px'
		}, 'slow' );
	});
	

	function split( val ) {
		return val.split( /,\s*/ );
	}

	function extractLast( term ) {
		return split( term ).pop();
	}
	 
	jQuery( "input.role" ).bind( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "autocomplete" ).menu.active ) {
			event.preventDefault();
		}
	}).autocomplete({
		minLength: 0,
		source: function( request, response ) {
			response( $.ui.autocomplete.filter( rec.available_roles, extractLast( request.term ) ) );
		}, focus: function() {
			return false;
		}, select: function( event, ui ) {
			var terms = split( this.value );
			terms.pop();
			terms.push( ui.item.value );
			terms.push( "" );
			this.value = terms.join( ", " );
				return false;
		}
	});
	      
	jQuery('.form_container').validate({
		errorElement: "div",
		submitHandler : function( form ) {
			jQuery(form).ajaxSubmit({
				dataType : 'json',
				success : function(data) {
					if ( data.error )
						return;
						
					jQuery('.competition_form_register').stop().animate({
						'margin-left':'-800px'
					}, 'slow' );
					
					jQuery('.competition_form_completed').stop().animate({
						'margin-left':'0',
						'margin-top': '-430px'
					}, 'slow' );
				}
			});				
		},
		messages: {
			name: {
				required: rec.invalid_name,
				minlength: jQuery.format( rec.minlength ),
				maxlength: rec.maxlength,
				remote: rec.name_incomplete
			},
			email: {
				required: rec.invalid_email,
				remote: rec.email_exists
			},
			role: {
				required: rec.invalid_role
			},
			firsturl: {
				required: rec.invalid_url,
				remote : rec.validate_url
			},
			secondurl: {
				required: rec.invalid_url,
				remote : rec.validate_url
			},
			thirdurl: {
				required: rec.invalid_url,
				remote : rec.validate_url
			}			
		},
		rules: {
			name: {
				required: true,
				minlength: 3,
				maxlength: 60,
				remote : rec.validate_name_ajax
			},
			email: {
				required: true,
				email : true,
				remote : rec.validate_email_ajax
			},
			role: {
				required: true,
			},
			firsturl: {
				required: true,
				url : true,
				remote : rec.valid_url,
				sameurls : {
					elements : [ '.secondurl', '.thirdurl' ]
				}
			},
			secondurl: {
				required: true,
				url : true,
				remote : rec.valid_url,
				sameurls : {
					elements : [ '.firsturl', '.thirdurl' ]
				}
			},
			thirdurl: {
				required: true,
				url : true,
				remote : rec.valid_url,
				sameurls : {
					elements : [ '.secondurl', '.firsturl' ]
				}
			}
		}
	});
	
	jQuery('.firsturl,.secondurl,.thirdurl').focusout(function(e){
		s = $(this).val();
		if ( s.indexOf("http://") === -1 )
			$(this).val("http://"+s);
	});
	
	
	jQuery('#competition_compete.final').click(function(e){
		e.preventDefault();
		jQuery('.form_container').submit();
	});
	
	
	jQuery.validator.addMethod( "sameurls", function( value, element, params ) {
		return ! ( value == $(params.elements[0]).val() || value == $(params.elements[1]).val() )
	}, jQuery.format( "URLs cannot be equal.") );

	
	console.log(jQuery(".chzn-select")); 
	
	jQuery(".chzn-select").chosen({disable_search:true, disable_search_threshold: 5});
	
});