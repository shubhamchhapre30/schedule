<?php
$theme_url = base_url().getThemeName();

 ?>
 <link href="<?php echo $theme_url; ?>/jquery-tooltip/jquery.qtip.css?Ver=<?php echo VERSION;?>" rel="stylesheet">
 <style>
.modal  {
	top: 0;
	
}
</style>
<!-- #################################################################################### -->

<div class="wrapper row2 page-padding">
	<!--==================Start Messages=================================== -->
	
	<?php if($msg!="" && $msg=="feedbackinactive"){  ?>
    	<script>
    		jQuery(document).ready(function() {  	//$('#messages_sdsad').delay(5000).fadeOut();
    			$('#messages_sdsad').modal('show');
    			setTimeout(function(){
				  $('#messages_sdsad').modal('hide')
				}, 5000);
    	});  
    	</script>
    	<div class="modal fade login_pop" id="messages_sdsad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog" id="login">
		    <div class='alert alert-success'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $this->lang->line("home.msg_feedback_inactive"); ?></span></div>
		      <!-- <div class="modal-footer">
		        <button type="button" class="send" data-dismiss="modal">Close</button>
		      </div> -->
		    </div>
 		 </div>
		 
	<?php } ?>
	
	<?php if($msg!="" && $msg=="signup_sucess"){  ?>
    	<script>
    		jQuery(document).ready(function() {  	//$('#messages_sdsad').delay(5000).fadeOut();
    			$('#messages_sdsad').modal('show');
    			setTimeout(function(){
				  $('#messages_sdsad').modal('hide')
				}, 5000);
    	});  
    	</script>
    	<div class="modal fade login_pop" id="messages_sdsad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog" id="login">
		    <div class='alert alert-success'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $this->lang->line("home.msg_register_success"); ?></span></div>
		      <!-- <div class="modal-footer">
		        <button type="button" class="send" data-dismiss="modal">Close</button>
		      </div> -->
		    </div>
 		 </div>
		 
	<?php } ?>
	
	<?php if($msg!="" && $msg=="expired"){  ?>
		<script>
    		jQuery(document).ready(function() {  	//$('#messages_sdsad').delay(5000).fadeOut();
    			$('#messages_sdsad').modal('show');
    			setTimeout(function(){
				  $('#messages_sdsad').modal('hide')
				}, 5000);
    	});
    	</script>
		<div class="modal fade login_pop" id="messages_sdsad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog" id="login">
		    <div class='alert alert-error'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $this->lang->line("home.msg_activate_fail"); ?></span></div>"
		      <!-- <div class="modal-footer">
		        <button type="button" class="send" data-dismiss="modal">Close</button>
		      </div> -->
		    </div>
  </div>
		 
	<?php } ?>
	
	<?php if($msg!="" && $msg=="forgetsuccess"){  ?>
		<script>
    		jQuery(document).ready(function() {  	//$('#messages_sdsad').delay(5000).fadeOut();
    			$('#messages_sdsad').modal('show');
    			setTimeout(function(){
				  $('#messages_sdsad').modal('hide')
				}, 5000);
    	});
    	</script>
		<div class="modal fade login_pop" id="messages_sdsad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog" id="login">
		    <div class='alert alert-success'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $this->lang->line("home.msg_forgot_send"); ?></span></div>"
		      <!-- <div class="modal-footer">
		        <button type="button" class="send" data-dismiss="modal">Close</button>
		      </div> -->
		    </div>
  		</div>
		 
	<?php } ?>
	
	<?php if($msg!="" && $msg=="fail"){  ?>
		<script>
    		jQuery(document).ready(function() {  	//$('#messages_sdsad').delay(5000).fadeOut();
    			$('#messages_sdsad').modal('show');
    			setTimeout(function(){
				  $('#messages_sdsad').modal('hide')
				}, 5000);
    	});
    	</script>
		<div class="modal fade login_pop" id="messages_sdsad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog" id="login">
		    <div class='alert alert-success'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $this->lang->line("home.msg_activate_fail"); ?></span></div>"
		      <!-- <div class="modal-footer">
		        <button type="button" class="send" data-dismiss="modal">Close</button>
		      </div> -->
		    </div>
  </div>
		 
	<?php } ?>
	
	<?php if($msg!="" && $msg=="reset"){  ?>
		<script>
    		jQuery(document).ready(function() {  	//$('#messages_sdsad').delay(5000).fadeOut();
    			$('#messages_sdsad').modal('show');
    			setTimeout(function(){
				  $('#messages_sdsad').modal('hide')
				}, 5000);
    	});
    	</script>
		<div class="modal fade login_pop" id="messages_sdsad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog" id="login">
		    <div class='alert alert-success'><a class='closemsg' data-dismiss='alert'></a><span><?php echo $this->lang->line('home.msg_reset_pass_succ'); ?></span></div>"
		      <!-- <div class="modal-footer">
		        <button type="button" class="send" data-dismiss="modal">Close</button>
		      </div> -->
		    </div>
  </div>
		 
	<?php } ?>
	
	<!--==================End of Messages=================================== -->
	
	<div class="container">
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
    <li data-target="#carousel-example-generic" data-slide-to="3"></li>
    <li data-target="#carousel-example-generic" data-slide-to="4"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
  	
    <div class="item active"><img src="<?php echo $theme_url ?>/img/banner_1.jpg" alt="Banner"></div>
    <div class="item"><img src="<?php echo $theme_url ?>/img/banner_2.jpg" alt="Banner "></div>
	<div class="item"><img src="<?php echo $theme_url ?>/img/banner_3.jpg" alt="Banner "></div>
	<div class="item"><img src="<?php echo $theme_url ?>/img/banner_4.jpg" alt="Banner "></div>
	<div class="item"><img src="<?php echo $theme_url ?>/img/banner_5.jpg" alt="Banner "></div>
    
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
	</div>
</div>
<!-- #################################################################################### -->
 <div class="wrapper row3">
    <div class="container">
		<!-- Title -->
		<div class="text-center margin-bottom-20 clearfix">
			<h1 class="center-title"> <?php echo $this->lang->line("home.About_us"); ?> </h1>
			<div class="line">  <span class="septor-img"></span></div>	
		 </div>	
		<!-- End Title -->
		
		<div class="services-boxes">
			<div class="row">
				<div class="col-md-3">
				   <div class="service-box">
				   		<a href="<?php echo site_url("home/aboutus") ?>">	
							<h2><?php echo $this->lang->line("home.we_are_naturalia"); ?></h2>
							<div class="margin-bottom-15 margin-top-10">
								<img alt="" src="<?php echo $theme_url ?>/img/img1.png" class="img-responsive">
							</div>
							<div class="service-desc">
								<p><?php echo $this->lang->line("home.we_are_naturalia_text"); ?></p>
							</div>
				  		 </a>
				   </div>
				    
				</div><!-- /.col-lg-3 -->
				<div class="col-sm-3 col-md-3">
				   <div class="service-box">	
				   	<a href="<?php echo site_url("home/aboutus") ?>">	
				   		<h2><?php echo $this->lang->line("home.cook_for_you"); ?></h2>
						<div class="margin-bottom-15 margin-top-10">
				  			<img alt="" src="<?php echo $theme_url ?>/img/img2.png" class="img-responsive">
						</div>
				  		<div class="service-desc">
				  			<p><?php echo $this->lang->line("home.cook_for_you_text"); ?></p>
						</div>
					 </a>
				   </div>
				</div><!-- /.col-lg-3 -->
				<div class=" col-md-3">
				   <div class="service-box">	
				   	<a href="<?php echo site_url("home/aboutus") ?>">	
				   		<h2><?php echo $this->lang->line("home.yau_care"); ?></h2>
						<div class="margin-bottom-15 margin-top-10">
				  			<img alt="" src="<?php echo $theme_url ?>/img/img3.png" class="img-responsive">
						</div>
				  		<div class="service-desc">
				  			<p><?php echo $this->lang->line("home.yau_care_text"); ?></p>
						</div>
					</a>
				   </div>
				</div><!-- /.col-lg-3-->
				<div class="col-sm-3 col-md-3">
				   <div class="service-box">	
				   	<a href="<?php echo site_url("home/aboutus") ?>">	
				   		<h2><?php echo $this->lang->line("home.we_deliver_it"); ?></h2>
						<div class="margin-bottom-15 margin-top-10">
				  			<img alt="" src="<?php echo $theme_url ?>/img/img4.jpg" class="img-responsive">
						</div>
				  		<div class="service-desc">
				  			<p><?php echo $this->lang->line("home.we_deliver_it_text"); ?></p>
						</div>
					</a>
				   </div> 
				</div><!-- /.col-lg-3-->
			  </div>
			  <div class="margin-bottom-20 margin-top-20 text-center"> 
					<a class="btn green active" href="<?php echo site_url().'user/client_buy';?>"> <?php echo $this->lang->line("home.order_now"); ?></a>  
			</div>
		</div> 
	 
 	</div>  
</div> 
<!-- #################################################################################### -->
  <div class="wrapper row3">
    <div class="container">
		<!-- Title -->
		<div class="text-center margin-bottom-20 clearfix">
			<h1 class="center-title"><?php echo $this->lang->line("home.we_cook_today"); ?></h1>
			<div class="line">  <span class="septor-img"></span></div>	
		 </div>	
		<!-- End Title -->

<!--############ Change Product list Start ############-->



		

	
<!--############ Change Product list End ############-->	
		
		 <div class="cookblock margin-bottom-20 ">
			<div class="row">				
				<?php
				$checkallmenu = all_product_menu();
				$count_product='';
				if($checkallmenu){
	            	foreach($checkallmenu as $mainmenu)
					{
						$catid=$mainmenu->category_id;
						$catname=$mainmenu->category_name;
						$checksubmenu = all_product_sub_menu($catid);
				?>
				<div class="col-md-6">
					<div class="centerbtn">
						<div class="dropdown single_menudropdwn">
						  <a  data-target="#" href="#" class="newgreen" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span> <?php echo $this->lang->line("home.menus").' '.ucfirst($catname); ?> </span>
						  </a>
						  <ul class="dropdown-menu list-unstyled submenu" role="menu" aria-labelledby="dLabel">
						  	<?php 
						  	if($checksubmenu){
						  		$count = count($checksubmenu);
								$i=0;
								foreach($checksubmenu as $submenu)
								{
									$i++;
									$product_type_id=$submenu->product_type_id;
									$product_type=$submenu->product_type;
									if($count == $i){$active_class='active'; } else { $active_class='';}
					
			                    	echo '<li><a href="javascript://" class="chnageSubmenu productMenu_'.$product_type_id.' '.$active_class.'" data-product="'.$product_type_id.'" data-category="'.$catid.'">'.ucfirst($product_type).'</a></li>';
								}
							}
						  	?>
						  </ul>
						</div>
					</div>   
					<?php 
						
						
						//echo $product_type_id."===";
						if(isset($product_type_id)){
						    $date = date('Y-m-d'); //'2014-11-21'; //
						    $product_lists = get_product_list($catid,$product_type_id,$date);
                            $count_product[] = count($product_lists);    
						}
						 
						
						//echo '<pre>'; print_r($product_lists); echo '</pre>';
					?>
					<div class="clearfix"> </div> 
					<?php  if(isset($product_type_id)){ ?>
				  	<h2 class="greentitle margin-bottom-20" id="greentitle_<?php echo $catid;?>"> <?php echo ucfirst($product_type);?> </h2>
				  	
				  	<?php } ?>
				  	<div class="fulldaylist-block" >
				   		<div class="table-responsive" id="product_<?php echo $catid;?>"  >
				   			<!--<link href="<?php echo $theme_url ?>/jquery-tooltip/tooltip.css?Ver=<?php echo VERSION;?>" rel="stylesheet" type="text/css"/>										
							<script src="<?php echo $theme_url ?>/jquery-tooltip/jtip.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>	-->
							<table class="table" >
								<tbody>
									<?php 
									 if(isset($product_type_id)){ 
										if($product_lists){
											foreach($product_lists as $list){										
									?>
									<tr>
										
										<td> 
											<div class="listtitle" > 
												<?php /* <a  id="HoverModal_<?php echo $list->menu_meal_id;?>" href="<?php echo site_url(); ?>product/tooltip_ingredients/<?php echo $list->recipi_id; ?>?width=350" class="jTip" name="<?php echo get_energy_kcal($list->recipi_id); //$list->meal; ?>"><?php echo $list->meal; ?></a> */?>
												<a  id="HoverModal_<?php echo $list->menu_meal_id;?>" href="javascript://" class="scrollTip" name="<?php echo get_energy_kcal($list->recipi_id); ?>"><?php echo $list->meal; ?></a>
												<div id="ing_<?php echo $list->menu_meal_id;?>" style="display:none">
													<?php 
													$ingredients = get_ingredients($list->recipi_id);
													$ing_content = '<ul class="">';
												 	if($ingredients){
														foreach($ingredients as $ingredient){
															$ing_content .= '<li>'.$ingredient->ingredient_name.'</li>';
														}
													} else {
														$ing_content .= '<li>'.$this->lang->line('home.No_Ingredient_Found').'</li>';
													}
													$ing_content .= '</ul>';
													echo $ing_content;
	
													?>
												</div>
											</div>
											
									    	<div> <?php echo $list->recipi; ?>   </div>
									    </td>
										<td class="listprice"><!--$ 10--></td>
									</tr>

									<?php } } }?>
								</tbody>
							</table>
			   			</div>
		   			</div>	
		   			<div class="action margin-bottom-20 margin-top-20 text-center"> 
		   			    <?php  if(isset($product_type_id)){ ?>
						<a class="btn green active" href="<?php echo site_url().'product/menu/'.base64_encode($product_type_id);?>"> <?php echo $this->lang->line("home.info"); ?></a> 

						<?php } if(isset($product_type_id)){ if($catid == 1){ ?>
					 		<a href="<?php echo site_url('fullday/buy_fulldaymenu/'.base64_encode($product_type_id))?>" class="btn green active"> <?php echo $this->lang->line('home.giftcard_buy'); ?> </a>
					 	<?php } else { ?>
					 		<a href="<?php echo site_url('halfday/buy_halfdaymenu/'.base64_encode($product_type_id))?>" class="btn green active"> <?php echo $this->lang->line('home.giftcard_buy'); ?> </a>
					 	<?php } } ?>

					</div>
				</div>
				<?php
					}
				}
				?>
			</div>
		</div>		
		 
	 </div>
</div>			

<!-- #################################################################################### -->		
 
<div class="wrapper row4">
    <div class="container">	
		<div class="footer-block-one">
			<div class="row">
				<div class="col-sm-3 col-md-3">
				   <div class="footer-box">
				   		
							<h2><?php echo $this->lang->line("home.About_us"); ?></h2>
							<a href="<?php echo site_url('staff');?>"><div class="margin-bottom-15 margin-top-10">
								<img alt="" src="<?php echo $theme_url ?>/img/img5.png" class="img-responsive">
							</div>
							</a>
							<div>
								<p><?php echo $this->lang->line("home.About_us_tetx"); ?></p>
							</div>
				  		 
				   </div>
				   
				</div><!-- /.col-lg-3 -->
				<div class="col-sm-3 col-md-3">
				   <div class="footer-box">
				   		
							<h2><?php echo $this->lang->line("home.benefits_for_companies"); ?></h2>
							<a href="<?php echo site_url('home/benefit_of_naturalia');?>"><div class="margin-bottom-15 margin-top-10">
								<img alt="" src="<?php echo $theme_url ?>/img/img6.png" class="img-responsive">
							</div>  </a>
							<div >
								<p><?php echo $this->lang->line("home.benefits_for_companies_text"); ?></p>
							</div>
				  		  
				   </div>
				   
				</div><!-- /.col-lg-3 -->
				<div class="col-sm-3 col-md-3">
				   <div class="footer-box">
				   		
							<h2><?php echo $this->lang->line("home.gives_health"); ?></h2>
							<a href="<?php echo site_url('giftcard');?>"><div class="margin-bottom-15 margin-top-10">
								<img alt="" src="<?php echo $theme_url ?>/img/img7.png" class="img-responsive">
							</div> </a>
							<div>
								<p><?php echo $this->lang->line("home.gives_health_text"); ?></p>
							</div>
				  		 
				   </div>
				   
				</div><!-- /.col-lg-3 -->
				<div class="col-sm-3 col-md-3">
				   <div class="footer-box">
				   		 
							<h2><?php echo $this->lang->line("home.recent_tweets"); ?></h2>
							 
                    <!-- BEGIN TWITTER BLOCK -->                                                    
                  <!-- 
                    <dl class="dl-horizontal f-twitter">
                        <dt><i class="icon twitter"></i></dt>
                        <dd>
                            <a href="#">@LoremipsumImperdiet condimentum diam sit consectetur adipiscing </a>  <span>By User 3 Months Ago</span>
                        </dd>
                    </dl>                    
                    <dl class="dl-horizontal f-twitter">
                        <dt><i class="icon twitter"></i></dt>
                        <dd>
                            <a href="#">@Loremipsum
                            Sequat ipsum dolor onec eget orci fermentum condimentum lorem sit consectetur adipiscing</a>
                            <span>By User 3 Months Ago</span>
                        </dd>
                    </dl>                    
                    <dl class="dl-horizontal f-twitter">
                        <dt><i class="icon twitter"></i></dt>
                        <dd>
                            <a href="#">@Loremipsum
                            Remonde sequat ipsum dolor lorem sit consectetur adipiscing</a>
                            <span>By User 3 Months Ago</span>
                        </dd>
                    </dl>           -->      
                    
                   <a class="twitter-timeline" href="https://twitter.com/NaturaliaRD" data-widget-id="540016848314454016">Tweets by @NaturaliaRD</a>
                   <!-- <a class="twitter-timeline"
  href="https://twitter.com/NaturaliaRD"
  data-widget-id="540016848314454016"
  data-theme="light"
  data-link-color="#4A913C"
  data-related="twitterapi,twitter"
  data-aria-polite="assertive"
  width="300"
  height="300">
Tweets by @NaturaliaRD
</a> -->
<!-- <a class="twitter-timeline"
  href="https://twitter.com/NaturaliaRD"
  data-widget-id="540016848314454016"
  data-chrome="nofooter noborders transparent">
Tweets by @NaturaliaRD
</a> -->
<!-- <a class="twitter-timeline"
  href="https://twitter.com/NaturaliaRD"
  data-widget-id="540016848314454016"
  data-chrome="nofooter"
  data-tweet-limit="5">
Tweets by @NaturaliaRD
</a> -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js?Ver=<?php echo VERSION;?>";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    <!-- END TWITTER BLOCK -->                                                                        
              
				   </div>
				   
				</div> 
			</div>	
		</div>
	</div>
</div>	


<!-- #################################################################################### -->	

<script src="<?php echo $theme_url ?>/jquery-tooltip/jquery.qtip.js?Ver=<?php echo VERSION;?>" type="text/javascript"></script>
<script type="text/javascript">

$(function(){ 
	$(".chnageSubmenu").click(function(){
		var category_id= $(this).data('category');
		var product_id = $(this).data('product');
		var title = $(this).text();
		
		$.ajax({
	        type: 'POST',
	        url: '<?php echo site_url('home/get_product_list') ?>',
	        //cache: false,
	        
	        data: {category_id:category_id,product_id:product_id},
	         dataType: '',
	        beforeSend : function(){ 	
	        	$(".chnageSubmenu").removeClass('active');  
	        	$('#product_'+category_id).html(''); 
	        	$('#product_'+category_id).html('<div id="show_loader1" class="text-center" ><img src="<?php echo base_url().getThemeName(); ?>/img/ajax-loader.gif" /></div>');
	        	
	        },
	        success : function(data){
				$('#greentitle_'+category_id).html(title);				
	            $('#product_'+category_id).html(data);
	            $(".productMenu_"+product_id).addClass('active'); 
	            
	            $('.scrollTip').each(function()
			   {
			   	
			   		var TipTitle = $(this).attr('name');
			   		var val = $(this).attr('id');
					val = val.split("_");
		
					var contentText = $("#ing_"+val[1]).html();
			
				     $(this).qtip({
				     content: {
				         title: {
				            text: TipTitle,
				         },
				         text: contentText
					 },		     
				   });
			   
			   });
	            
	        },
	        complete : function(){ 
	        	
	        },
	    });
	});
});

	



// Create the tooltips only on document load
$(document).ready(function() {
   // Match all link elements with href attributes within the content div
   $('.scrollTip').each(function()
   {
   		var TipTitle = $(this).attr('name');
   		var val = $(this).attr('id');
		val = val.split("_");
		var contentText = $("#ing_"+val[1]).html();

	     $(this).qtip({
	     content: {
	         title: {
	            text: TipTitle,
	         },
	         text: contentText
		 },		     
	   });
   
   });
});
</script>
