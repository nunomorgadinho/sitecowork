<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Rec
 * @since Rec 1.0
 */
?>


   <ul>
		        	<li>
		        		<?php _e('choose workplace','cowork');?>
		        	</li>
		        	<li>
		        		<?php _e('choose plan','cowork');?>
		        	</li>
		        	<li>
		        		<?php _e('choose desk','cowork');?>
		        	</li>
		        	<li>
		        		<?php _e('choose life','cowork');?>
		        	</li>
		        </ul>
    
    <div class="competition_slider">

	    <article class="competition_form_landing">
	        
	        
	        <h3><?php _e('Add a life to your work today:','cowork')?></h3>
	        <form action="#" name="quiz_update" id="quiz_update" method="post">
	        
	        <div class="wrapper">
			<div class="expand_wrapper">
			
				<div class="question-group closed">
					<h4 class="expand_heading"><?php _e('choose workplace','cowork');?></h4>
					<div class="toggle_container">
						<div class="form-row form-row-first">
            			<ul>
						<li><span title="Selecione até três opções" class="option5 filtrounico <?php echo is_selected("mais_gosta", "Perfumes"); ?>">Perfumes</span></li>
						<li><span title="Selecione até três opções" class="option5 filtrounico <?php echo is_selected("mais_gosta", "Cabelo"); ?>">Cabelo</span></li>
						<li><span title="Selecione até três opções" class="option5 filtrounico <?php echo is_selected("mais_gosta", "Tratamento do Rosto"); ?>">Tratamento do Rosto</span></li>
						<li><span title="Selecione até três opções" class="option5 filtrounico <?php echo is_selected("mais_gosta", "Solares"); ?>">Solares</span></li>
					</ul>
          </div>
						        
						        <p class="form-row">
						          <label class="question" for="tipo_sensibilidade">E quanto à sensibilidade?<abbr class="required" title="required">*</abbr></label>
						          <select id="tipo_sensibilidade" name="tipo_sensibilidade" class="element-med">
						            
						            <option value="Neutra/Normal" <?php if ($meta['tipo_sensibilidade'] == "Neutra/Normal") echo 'selected'; ?>>Neutra/Normal</option>
						            <option value="Sensível" <?php if ($meta['tipo_sensibilidade'] == "Sensível") echo 'selected'; ?>>Sensível</option>
						          </select>
						        </p>
						
						        <p class="form-row">
						          <label class="question" for="tom_pele">Qual é o seu tom de pele?<abbr class="required" title="required">*</abbr></label>
						          <select id="tom_pele" name="tom_pele" class="element-med">
						            
						            <option value="Claro" <?php if ($meta['tom_pele'] == "Claro") echo 'selected'; ?>>Claro</option>
						            <option value="Médio" <?php if ($meta['tom_pele'] == "Médio") echo 'selected'; ?>>Médio</option>
						            <option value="Moreno" <?php if ($meta['tom_pele'] == "Moreno") echo 'selected'; ?>>Moreno</option>
						            <option value="Escuro" <?php if ($meta['tom_pele'] == "Escuro") echo 'selected'; ?>>Escuro</option>
						          </select>
						        </p>
						      </div>  <!-- toggle_container -->
				</div>  <!-- question-group -->
	  
			
			
	        
		       
		        
		     </div> <!-- .expand_wrapper -->
		     </div> <!-- .wrapper -->
		    
		    	<p class="small">
		    		<?php _e("Yes, it's that simple. Now a human will take your subscription from here. We love you already dear.", 'cowork')?>
		    	</p>
		        
		       
		         <a class="navigate-link" href="#"><?php _e('> next','cowork');?></a>	
        	</form>
	    </article>

	
	    <article class="competition_form_register">
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
			<!-- <div class="competition_output"></div> -->
			</div>	      
	      </div>
	    </article>

	    <article class="competition_form_completed">
	      <div class="marker"></div>
	      <h1>Thanks!</h1>
	      <div class="marker"></div>
	      <h2><?php _e('You will hear from us in a short notice','rec');?></h2>
	      
	    </article>
	
	    <span class="clear"></span>
    
    </div>

