<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Rec
 * @since Rec 1.0
 */
?>
    

	        
	        
	        <h1 class="general-title"><?php _e('Add a life to your work today:','cowork')?></h1>
	 <div id="wrapper">
     	<div id="steps">
	        
	        <form id="formElem" name="formElem" action="" method="post">
	       	
	       	 <fieldset class=" box-middle step">
	        
	        	<ul class="multiple-selects">
	        		<li>
			        	<select name="workplace" id="workplace" class="chzn-select" style="width:250px;" >
					      <option ><?php _e('choose workplace','cowork');?></option>
					      <option value="lx">LX Factory</option>
					      <option value="central">Central Station</option>
					      <option value="chiado">Palácio</option>
					      <option value="all"> Nomad (all locations)</option>
		    			</select>
		        	</li>
	      	  		
	      	  		<li>
			        	<select name="plan" id="plan" class="chzn-select" style="width:250px;">
					     <option><?php _e('choose plan','cowork');?></option>
					      <option value="fixed-desk">Month Fixed Desk (M €120 / L €145) +iva</option>
					      <option value="hub">HUB x3 / x4 (ask!)</option>
					      <option value="month-flex">Month Flex Desk (€99) iva_inc</option>
					      <option value="week-flex"> Week Flex Desk (€39) iva_inc</option>
					      <option value="day">Day Pass (€10) iva_inc</option>
					      <option value="no-desk">Nomad No Desk (€39) iva_inc</option>
		    			</select> 	     		
      				</li>
	        		<li>
		        		<select name="desk" id="desk" class="chzn-select" style="width:250px;">
		        			<option><?php _e('choose desk','cowork');?></option>
		        			<option value="flexible">Flexible Desk</option>
		        			<option value="typea">A Desk</option>
		        			<option value="typeb">B(ig) Desk</option>
		        		</select>
	        		</li>
	        		<li>
		        		<select name="life" id="life" class="chzn-select" style="width:250px;">
		        			<option><?php _e('choose life','cowork');?></option>
		        			<option value="work">Worklife</option>
		        			<option value="artist">Artist life</option>
		        			<option value="flaneur">Flaneur</option>
		        		</select>
	        		</li>
	        	</ul>
	        
	       
	         <p class="navigate"><a class="navigate-link red" href="#"><?php _e('> next','cowork');?></a></p>	
        	
	  
	    </fieldset>
		
		<!-- Second Phase -->
		<fieldset class="box-middle step">
	    	        
	        <h1 class="general-title"><?php _e('Your almost done!','cowork')?></h1>
	        <ul class="multiple-selects">
	        	<input type="text" name="name" placeholder="name" size="50" required/>
				<input type="text" name="address" placeholder="address" size="50" required/>
				<input type="email" name="email" placeholder="email" size="50" required/>
				<input type="text" name="mobile" placeholder="mobile phone" size="50" required/>
	        </ul>
	         <p class="navigate"><a id="" class="navigate-link red" href="#"><?php _e('> send','cowork');?></a></p>	       	
	    
	    </fieldset>

	    <fieldset class="box-middle step">
		<!-- Third Phase -->
		<fieldset class="box-middle step">
	        <h2 class="general"><?php _e('Yes, it’s this simple to start working with us. Now a human called Ana Dias will take your subscription from here. We love you already, you know?','cowork')?></h2>
			<p class="navigate"><a id="" class="navigate-link green" href="#"><?php _e('> done!','cowork');?></a></p>
		</fieldset>
	 </form>
	 </div><!-- #steps -->
	 </div> <!-- #wrapper -->
	
	    <span class="clear"></span>
	    
	    <div id="navigation" style="display:none;">
                    <ul>
                        <li class="selected">
                            <a href="#">Account</a>
                        </li>
                        <li>
                            <a href="#">Personal Details</a>
                        </li>
                        <li>
                            <a href="#">Payment</a>
                        </li>
	    	</ul>
	    	</div>

