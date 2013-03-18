<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Rec
 * @since Rec 1.0
 */
?>


  

    
    <div class="competition_slider">

	    <article class="box-middle">
	        
	        
	        <h1 class="general-title"><?php _e('Add a life to your work today:','cowork')?></h3>
	        
	        
	        <ul class="multiple-selects">
	        	<li>
	        
	        	<select data-placeholder="choose workplace" name="workplace" id="workplace" class="chzn-select" style="width:250px;" >
			      <option value="all">LX Factory</option>
			      <option value="classic">Central Station</option>
			      <option value="modern">Palácio</option>
			      <option value="sport"> Nomad (all locations)</option>
    			</select>
	        		
	        	
	        		
	        	</li>
	        	<li>
		        	<select data-placeholder="<?php _e('choose plan','cowork');?>" name="plan" id="plan" class="chzn-select" style="width:250px;">
				      <option value="all">Month Fixed Desk (M €120 / L €145) +iva</option>
				      <option value="classic">HUB x3 / x4 (ask!)</option>
				      <option value="modern">Month Flex Desk (€99) iva_inc</option>
				      <option value="sport"> Week Flex Desk (€39) iva_inc</option>
				      <option value="">Day Pass (€10) iva_inc</option>
				      <option value="">Nomad No Desk (€39) iva_inc</option>
	    			</select>
	        		
      	</li>
	        	<li>
	        		<select>
	        			<option><?php _e('choose desk','cowork');?></option>
	        		</select>
	        	</li>
	        	<li>
	        		<select>
	        			<option><?php _e('choose life','cowork');?></option>
	        		</select>
	        	</li>
	        </ul>
	    
	    	<p class="small">
	    		<?php _e("Yes, it's that simple. Now a human will take your subscription from here. We love you already dear.", 'cowork')?>
	    	</p>
	        
	       
	         <p class="navigate"><a class="navigate-link" href="#"><?php _e('> next','cowork');?></a></p>	
        	
	    </article>

	
	    <!-- <article class="competition_form_register">
	      <h4><?php _e('until March 25th','rec');?></h4>
	      <div class="marker"></div>
	      <h1>Registration</h1>
	      <div class="marker"></div>
	        <form class="form_container" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
	          <div class="first_row">
	          <input type="text" name="name" class="required name formfield" placeholder="name">
	          <input type="email" name="email" class="required email formfield" placeholder="email">
	          <input type="text" name="role" class="required role formfield" placeholder="role">
	          <input type="hidden" name="action" value="add_new_user_profile">
	          </div>
	          <div class="second_row">
	          <label for="firsturl">1</label><input class="firsturl formfield" type="url" class="required" name="firsturl" placeholder="(Vimeo or YouTube link)..."><span class="error"></span>
	          <label for="secondurl">2</label><input class="secondurl formfield" type="url" class="required" name="secondurl" placeholder="(Vimeo or YouTube link)..."><span class="error"></span>
	          <label for="thirdurl">3</label><input class="thirdurl formfield" type="url" class="required" name="thirdurl" placeholder="(Vimeo or YouTube link)..."><span class="error"></span>
	          </div>
	        </form>

	
	      <div class="button_container_register">
	      	<div class="button_container_2">
			<div id="competition_back"><div class="competition_back_button"></div><a href="#"><p><?php _e('Back','rec');?></p></a></div>
	        <div id="competition_compete" class="final"><a href="#"><p><?php _e('Lets do it','rec');?></p><p class="microcopy"><?php _e('Just click on the button','rec');?></p></a></div>
			<!-- <div class="competition_output"></div>
			</div>	      
	      </div>
	    </article>

	    <article class="competition_form_completed">
	      <div class="marker"></div>
	      <h1>Thanks!</h1>
	      <div class="marker"></div>
	      <h2><?php _e('You will hear from us in a short notice','rec');?></h2>
	      
	    </article>-->
	
	    <span class="clear"></span>
    
    </div>

