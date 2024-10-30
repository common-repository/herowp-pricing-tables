<?php
if(!class_exists('AQ_Pricing_Tables')) {
	class AQ_Pricing_Tables extends AQ_Block {
	
		function __construct() {
			$block_options = array(
				'name' => 'Pricing Tables',
				'size' => 'span12',
				'resizable' => 1,
				"class_css"=>'',
				"fa_icon" => 'fa fa-usd'
			);
			
			//create the widget
			parent::__construct('AQ_Pricing_Tables', $block_options);
			
			//add ajax functions
			add_action('wp_ajax_aq_block_price_1_add_new', array($this, 'add_box'));
			
		}
		function form($instance) {
		if (!isset($margintop))  $margintop='50';
		if (!isset($marginbottom))  $marginbottom='50';
		if (!isset($awesomefont_heading))  $awesomefont_heading='fa fa-laptop';
		if (!isset($pricing_features_color))  $pricing_features_color='#333';
		if (!isset($pricing_heading_color))  $pricing_heading_color='#333';
		if (!isset($horizontal_line_color))  $horizontal_line_color='#e8e8e8';
		if (!isset($price_color))  $price_color='#ff6b52';
		if (!isset($icon_color))  $icon_color='#333';
		if (!isset($button_text_icon_color))  $button_text_icon_color='#FFF';
		if (!isset($button_bg_color))  $button_bg_color='#ff6b52';
		if (!isset($items_on_row))  $items_on_row='4';
		
			$defaults = array(
				'boxes' => array(
					1 => array(
						'bold_title' => 'Normal',
						'title' => 'plan',
						'price' => '29',
						'billing' => 'per month',
						'awesomefont' => 'fa fa-laptop',
						'currency' => '$',
						'features' => 
										'
<li>Super duper feature</li>
<li>yet another cool feature here</li>
<li>i am the third feature</li>
<li>also the  Fourth</li>
										',
						'buy_text' => 'Join this plan',
						'buy_second_text' => '50% Off First Month',
						'buy_url' => '#'
					),
				),
				'type'	=> 'tab',
				
			
				
			);
			
			
			
			
			
			
			$instance = wp_parse_args($instance, $defaults);	
			extract($instance);
			
			$tab_types = array(
				'tab' => 'boxes'
			);
			
			?>
			<div class="description cf">
				<ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
					<?php
					$boxes = is_array($boxes) ? $boxes : $defaults['boxes'];
					$count = 1;
					foreach($boxes as $tab) {	
						$this->tab3($tab, $count);
						$count++;
					}
					?>
				</ul>
				<p></p>
                
                <a href="#" rel="price_1" class="aq-sortable-add-new button">Add New</a>
                
				
            <p class="description"><!--Select 1,2,3,4,6-->
					<label for="<?php echo $this->get_field_id('items_on_row') ?>">
						<strong>Items on row:</strong> How many items of this block, you want to be displayed on a line.
							<?php $options=array(12=>'1',6=>'2',4=>'3',3=>'4'); echo aq_field_select('items_on_row', $block_id, $options, $items_on_row); ?>
					</label>
				</p><!--Select 1,2,3,4,6-->
				
			<p class="description">
                    <label for="<?php echo $this->get_field_id('icon_color') ?>">
                        Icon color: <br/>
                        <?php echo aq_field_color_picker('icon_color', $block_id, $icon_color, '#8d8d8d') ?>
                    </label>
            </p>
				
			<p class="description">
                    <label for="<?php echo $this->get_field_id('price_color') ?>">
                         Price color: <br/>
                        <?php echo aq_field_color_picker('price_color', $block_id, $price_color, '#8d8d8d') ?>
                    </label>
            </p>
               
			<p class="description">
                    <label for="<?php echo $this->get_field_id('pricing_heading_color') ?>">
                         Pricing heading color: <br/>
                        <?php echo aq_field_color_picker('pricing_heading_color', $block_id, $pricing_heading_color, '#8d8d8d') ?>
                    </label>
            </p>
			
			<p class="description">
                    <label for="<?php echo $this->get_field_id('pricing_features_color') ?>">
                         Pricing features color: <br/>
                        <?php echo aq_field_color_picker('pricing_features_color', $block_id, $pricing_features_color, '#8d8d8d') ?>
                    </label>
            </p>	
			
			<p class="description">
                    <label for="<?php echo $this->get_field_id('horizontal_line_color') ?>">
                          Horizontal lines color: <br/>
                        <?php echo aq_field_color_picker('horizontal_line_color', $block_id, $horizontal_line_color, '#8d8d8d') ?>
                    </label>
            </p> 
			
			<p class="description">
                    <label for="<?php echo $this->get_field_id('button_text_icon_color') ?>">
                          Button text color: <br/>
                        <?php echo aq_field_color_picker('button_text_icon_color', $block_id, $button_text_icon_color, '#8d8d8d') ?>
                    </label>
            </p> 
			
			<p class="description">
                    <label for="<?php echo $this->get_field_id('button_bg_color') ?>">
                          Button background: <br/>
                        <?php echo aq_field_color_picker('button_bg_color', $block_id, $button_bg_color, '#8d8d8d') ?>
                    </label>
            </p> 	

                
                  <p class="description">
                    <label for="<?php echo $this->get_field_id('margintop') ?>">
                        <strong>Margin-top:</strong> Enter the margin from top of this block in pixels. Do not include the px.
                        <?php echo aq_field_input('margintop', $block_id, $margintop, $size = 'full') ?>
                    </label>
				</p>
				
                <p class="description">
                    <label for="<?php echo $this->get_field_id('marginbottom') ?>">
                        <strong>Margin-bottom:</strong> Enter the margin from bottom of this block in pixels. Do not include the px.
                        <?php echo aq_field_input('marginbottom', $block_id, $marginbottom, $size = 'full') ?>
                    </label>
			   </p>
				
				
                <p></p>
                
			</div>
			
			
            
        <div class="font-awesome-select"><!--font-awesome-select START-->
            	<div class="font-awesome-plus-minus">
                	<a href="#" class="font-awesome-plus">+</a>
                    <a href="#" class="font-awesome-minus">-</a>
                	<a href="#" class="font_awesome_close">Close</a>
                </div>
           	<ul>			
				<?php herowp_font_icon();?>
            </ul>
        </div><!--font-awesome-select END-->
		
		
			<?php
		}
		
		function tab3($tab = array(), $count = 0) {

				
			?>
<li id="<?php echo $this->get_field_id('boxes') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">
				
				<div class="sortable-head cf">
					<div class="sortable-title">
						<strong><?php echo $tab['title'] ?></strong>
					</div>
					<div class="sortable-handle">
						<a href="#">Open / Close</a>
					</div>
				</div>
				
	<div class="sortable-body">
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-featured">
							Featured?<br/>
							<input type="hidden" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][featured]" value="0" />
							<input type="checkbox" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-featured" class="input-checkbox" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][featured]" <?php if($tab['featured']==1) {echo "checked=checked";}?> value="1"/>
						</label>
					</p>
					 <p class="tab-desc  description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-awesomefont">
							<a class="font-awesome-icon-select" href="#">Select Icon</a>
							<input  type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-awesomefont" class="awesome-fonts-input input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][awesomefont]" value="<?php echo $tab['awesomefont'] ?>" />
						</label>
					</p>
					
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-bold_title">
							Bold title<br/>
							<input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-bold_title" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][bold_title]" value="<?php echo $tab['bold_title'] ?>" />
						</label>
					</p>
					
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-title">
							Title<br/>
							<input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][title]" value="<?php echo $tab['title'] ?>" />
						</label>
					</p>
					
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-price">
							Price<br/>
							<input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-price" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][price]" value="<?php echo $tab['price'] ?>" />
						</label>
					</p>
					
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-billing">
							Billing cycle (year, month, etc)<br/>
							<input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-billing" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][billing]" value="<?php echo $tab['billing'] ?>" />
						</label>
					</p>	
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-currency">
							Currency<br/>
							<input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-currency" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][currency]" value="<?php echo $tab['currency'] ?>" />
						</label>
					</p>
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-features">
							List of features<br/>
							<textarea type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-features" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][features]" ><?php echo $tab['features'] ?></textarea>
						</label>
					</p>
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-buy_text">
							Buy text<br/>
							<input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-buy_text" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][buy_text]" value="<?php echo $tab['buy_text'] ?>" />
						</label>
					</p>
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-buy_second_text">
							Buy second text<br/>
							<input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-buy_second_text" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][buy_second_text]" value="<?php echo $tab['buy_second_text'] ?>" />
						</label>
					</p>
					<p class="tab-desc description">
						<label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-buy_url">
							Buy URL<br/>
							<input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-buy_url" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][buy_url]" value="<?php echo $tab['buy_url'] ?>" />
						</label>
					</p>
     	
					<p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
    </div>
				
</li>

			<?php
		}
		
		function block($instance) {
			extract($instance);
			
			wp_enqueue_script('jquery-ui-boxes');
			
			?>
			
			<?php echo '<div class="pricing_tables" style="margin-top:'.$margintop.'px; margin-bottom:'.$marginbottom.'px;"><!--PRICING START-->'; ?>
			
		<?php 
			
					$i = 0;
					$j = $delayanimation;
					$l = 1;
					
					$total = count($boxes);
					$counter=0;
					
					$output='<div class="pricing_tables_holder"><!--PRICING TABLES HOLDER START-->';
					
					
					foreach( $boxes as $tab ){

					
						    if($tab['featured']==1){
								$featured='featured_plan';
								$featured_ribbon='<div class="featured_ribbon"></div>';
							}
							else{
								$featured='';
								$featured_ribbon='';
							} 

							 
							//we clear the first element from the next row
							 $items_on_row_divided=12/$items_on_row;
							 if($counter % $items_on_row_divided == 0): $clear="clear_box"; else : $clear=""; endif;
							 
							
								$output .='
								
								
								<div class="col-md-'.$items_on_row.' col-sm-6 col-xs-12 '.$clear.'"><!--PRICING TABLE END -->
											
									<div class="box-pricing '.$featured.'"><!--BOX PRICING START-->
									'.$featured_ribbon.'
									';
								
										
								
											$output .='<p class="plan_icon"><i class="'.$tab['awesomefont'].'" style="color:'.$icon_color.'"></i></p>';
									
										
									$output .='<h1 style="color:'.$pricing_heading_color.'"><span>'.$tab['bold_title'].'</span>&nbsp;'.$tab['title'].'<span class="dotcolor"></h1>
										
										
										<div class="line" style="background:'.$horizontal_line_color.'"></div>
										
										<div class="price_circle"><!--PRICE CIRCLE START-->
											<div class="price_circle_inner"><!--PRICE CIRCLE INNER START-->
												<h1 class="price" style="color:'.$price_color.'">
													'.$tab['price'].'
													
												</h1>
												<span class="billing" style="color:'.$price_color.'">'.$tab['currency'].'/'.$tab['billing'].'</span>
											</div><!--PRICE CIRCLE INNER END-->
												
										</div><!--PRICE CIRCLE END-->
										
										<div class="line" style="background:'.$horizontal_line_color.'"></div>
										
										<ul style="color:'.$pricing_features_color.'">
											'.html_entity_decode($tab['features']).'
										</ul>
										
										<div class="button_holder">
										
											<a class="quote-button trim" style="background:'.$button_bg_color.'" href="'.$tab['buy_url'].'">
												
												<span class="btn_text" style="color:'.$button_text_icon_color.'">'.$tab['buy_text'].'</span><br>
												<span class="btn_text2" style="color:'.$button_text_icon_color.'">'.$tab['buy_second_text'].'</span>
											</a>
											
										</div>
										
									

									</div><!--BOX PRICING END-->
									
									
								
								</div><!--PRICING TABLE END -->
								';	
								
	
					$i++;
					$j=$j+$delayanimation;
					$l++;
					$counter++;
				}
					
			$output .='
	</div><!--PRICING TABLES HOLDER END-->
</div><!--PRICING END-->';
			
		
			echo $output;
		}
		
		
		function add_box() {
			$nonce = $_POST['security'];
			if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');
			
			$count = isset($_POST['count']) ? absint($_POST['count']) : false;
			$this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';
			
			//default key/value for the tab
			$tab = array(
						'bold_title' => 'Normal',
						'title' => 'plan',
						'price' => '29',
						'billing' => 'per month',
						'awesomefont' => 'fa fa-laptop',
						'currency' => '$',
						'features' => 
										'
<li>Super duper feature</li>
<li>yet another cool feature here</li>
<li>i am the third feature</li>
<li>also the  Fourth</li>
										',
						'buy_text' => 'Join this plan',
						'buy_second_text' => '50% Off First Month',
						'buy_url' => '#'
										
			);
			
			if($count) {
				$this->tab3($tab, $count);
			} else {
				die(-1);
			}
			
			die();
		}
		
		function update($new_instance, $old_instance) {
			$new_instance = aq_recursive_sanitize($new_instance);
			return $new_instance;
		}
	}
}