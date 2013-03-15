// js/application.js

// REC application holder
// Ensure the global `rec` object exists.
window.rec = window.rec || {};

// Using the jQuery ready event is excellent for ensuring all 
// code has been downloaded and evaluated and is ready to be 
// initialized.

(function($, rec){
	
	
	// ---------------------
	// Rec JS application
	// ---------------------
	
	rec.App = [];
	
	// Rec ajax aplication
	rec.App.ajaxurl = '/wp-admin/admin-ajax.php';
	
	// Create this closure to contain the cached modules
	rec.App.module = function() {
		// Internal module cache.
		var modules = {};
		
		// Create a new module reference scaffold or load an existing module.
		return function(name) {
			// If this module has already been created, return it.
			if (modules[name]) {
				return modules[name];
			}
	 
	 		// Create a module and save it under this name
	 		return modules[name] = {};
	 	};
	 	
	}();
	
	// Initialize this App
	rec.App.bootstrap = function( args ) {
		//$.getScript('http://recvideos.com/wp-content/themes/rec/js/rec.js',function(data){console.log('ola');});
	}
	
	// Get values from the server via AJAX
	// Wrapper to hold communications
	rec.App.getServer = function( args ) {
		var that = this;
				
		$.post( this.ajaxurl, {
			action  : args.action,
			data : args.data,
			
		}, function(returned) {
			args.callback(returned);
			
		}, 'json');
		
	};
	
	
	
	
	// ---------------------------------------------------
	// Supports Utilities to detect browser capabilities
	// ---------------------------------------------------
	
	rec.Supports = [];
	
	// Detects if the current browser supports
	// Local and Session storage natively
	rec.Supports.storage = function() {
		return ( typeof ( Storage ) !== "undefined" );
		
	};
	
	// Detects if the current browser supports
	// Web Workers natively
	rec.Supports.workers = function() {
		return ( typeof ( Worker ) !== "undefined" );
		
	};
	
	// Detects if the current browser supports
	// native HTML5 video playback.
	rec.Supports.video = function() {
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
	};
	
	
	
	
	// -------------------------------
	// Set of conditional functions
	// -------------------------------
	
	rec.is = [];
	
	rec.is.home = function() {
		return true;
	};





	

	// Module reference argument, assigned at the bottom
	// This is a slider abstraction
	rec.App.Slider = Backbone.View.extend({
		defaults: {
			el : '',
			width : '',
			height : '',
			responsive : true,
			showcontrols : false,
			showmarkers : false,
			animtype : 'slide'
		},
		
		initialize : function( args ) {
			this.args = _.extend(this.defaults, args);
			this.render();
		},
		
		render : function() {
			this.$el.bjqs( this.args );
		}
		
	});
	
	
	
	
	// Start REC application now
	$(document).ready(function(){
		rec.App.bootstrap();
	});
	
	
	

})(jQuery, rec);
