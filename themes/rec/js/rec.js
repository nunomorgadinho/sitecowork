// js/rec.js

// REC application holder
// Ensure the global `rec` object exists.
window.rec = window.rec || {};

//Rec JS View
(function($, rec){

	// ----------------------------------------
	// Render everything only when DOM is ready
	// ----------------------------------------
	$(document).ready(function(){
		
		// If is homepage...
		if ( rec.is.home() ) {
			
			// ...start the slider
			rec.homeslider = new rec.App.Slider({
				el : $('#banner-fade'),
				width : 700,
				height : 258
			});
			
			// ...start the jQuery UI Tabs
			$('.tabs').tabs();
		
		}
		
		
	});
	
	
})(jQuery, rec);
