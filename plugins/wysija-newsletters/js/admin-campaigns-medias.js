var formsubmit=false;jQuery(function(a){a(".wysija-thumb").click(function(){if(a(this).hasClass("selected")){a(this).removeClass("selected");unset("wp-"+a(this).find("span.identifier").html())}else{a(this).addClass("selected");insert({identifier:"wp-"+a(this).find("span.identifier").html(),width:a(this).find("span.width").html(),height:a(this).find("span.height").html(),url:a(this).find("span.url").html(),thumb_url:a(this).find("span.thumb_url").html()})}return true});a("#wysija-close, #close-pop-alt").click(function(){closeLbox();return false});a(".del-attachment").click(function(b){b.stopPropagation();if(confirm(wysijatrans.deleteimg)){a("#wysija-browse-form").append('<input type="hidden" name="subaction" value="delete" /><input type="hidden" name="imgid" value="'+parseInt(a(this).html())+'" />');formsubmit=true;unset("wp-"+a(this).html());jQuery("#overlay").show();window.parent.ajaxOver=false;hideShowOverlay()}return false})});function closeLbox(){window.parent.WysijaPopup.close()}function unset(a){window.parent.removeImage(a)}function insert(a){window.parent.addImage(a)}function hideShowOverlay(){if(window.parent.ajaxOver){if(!formsubmit){jQuery("#overlay").hide()}}else{if(formsubmit){jQuery("#wysija-browse-form").submit();formsubmit=false;return false}}};