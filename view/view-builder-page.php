<?php
/** 
 * Builder Page
 *
 * @description Main admin UI settings page
 * @package Aqua Page Builder
 *
 */
 
// Debugging
if(isset($_POST) && $this->args['debug'] == true) {
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
}

// Permissions Check
if ( ! current_user_can('edit_theme_options') )
	wp_die( __( 'Cheatin&#8217; uh?', 'bbold' ) );
	
$messages = array();

// Get selected template id
$selected_template_id = isset($_REQUEST['template']) ? (int) $_REQUEST['template'] : 0;

// Actions
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'edit';
$template = isset($_REQUEST['template']) ? $_REQUEST['template'] : 0;

// DEBUG
//echo '<pre>';
//print_r($_POST);
//echo '</pre>';

// Template title & layout
$template_name = isset($_REQUEST['template-name']) && !empty($_REQUEST['template-name']) ? htmlspecialchars($_REQUEST['template-name']) : 'No Title';

// Get all templates
$templates = $this->get_templates();

// Get recently edited template
$recently_edited_template = (int) get_user_option( 'recently_edited_template' );

if( ! isset( $_REQUEST['template'] ) && $recently_edited_template && $this->is_template( $recently_edited_template )) {
	$selected_template_id = $recently_edited_template;
} elseif ( ! isset( $_REQUEST['template'] ) && $selected_template_id == 0 && !empty($templates)) {
	$selected_template_id = $templates[0]->ID;
}

//define selected template object
$selected_template_object = get_post($selected_template_id);

// saving action
switch($action) {

	case 'create' :
		
		$new_id = $this->create_template($template_name);
		
		if(!is_wp_error($new_id)) {
			$selected_template_id = $new_id;
		
			//refresh templates var
			$templates = $this->get_templates();
			$selected_template_object = get_post($selected_template_id);
			
			$messages[] = '<div id="message" class="updated"><p>' . __('The ', 'bbold') . '<strong>' . $template_name . '</strong>' . __(' pricing template has been successfully created', 'bbold') . '</p></div>';
		} else {
			$errors = '<ul>';
			foreach( $new_id->get_error_messages() as $error ) {
				$errors .= '<li><strong>'. $error . '</strong></li>';
			}
			$errors .= '</ul>';
			
			$messages[] = '<div id="message" class="error"><p>' . __('Sorry, the operation was unsuccessful for the following reason(s): ', 'bbold') . '</p>' . $errors . '</div>';
		}
		
		break;
		
	case 'clone' :
		
		$new_id = $this->clone_template( $selected_template_id );
		
		if(!is_wp_error($new_id)) {
			$selected_template_id = $new_id;
		
			//refresh templates var
			$templates = $this->get_templates();
			$selected_template_object = get_post($selected_template_id);
			
			$messages[] = '<div id="message" class="updated"><p>' . __('The ', 'aqpb-l10n') . '<strong>' . $template_name . '</strong>' . __(' pricing template been successfully cloned', 'aqpb-l10n') . '</p></div>';
		} else {
			$errors = '<ul>';
			foreach( $new_id->get_error_messages() as $error ) {
				$errors .= '<li><strong>'. $error . '</strong></li>';
			}
			$errors .= '</ul>';
			
			$messages[] = '<div id="message" class="error"><p>' . __('Sorry, the operation was unsuccessful for the following reason(s): ', 'aqpb-l10n') . '</p>' . $errors . '</div>';
		}
		
		break;	
	
		
	case 'update' :
	
		$blocks = isset($_REQUEST['aq_blocks']) ? $_REQUEST['aq_blocks'] : '';
		
		$this->update_template($selected_template_id, $blocks, $template_name);
		
		//refresh templates var
		$templates = $this->get_templates();
		$selected_template_object = get_post($selected_template_id);
		
		$messages[] = '<div id="message" class="updated"><p>' . __('The ', 'bbold') . '<strong>' . $template_name . '</strong>' . __(' pricing table has been updated', 'bbold') . '</p></div>';
		break;
		
	case 'delete' :
		
		$this->delete_template($selected_template_id);
		
		//refresh templates var
		$templates = $this->get_templates();
		$selected_template_id =	!empty($templates) ? $templates[0]->ID : 0;
		$selected_template_object = get_post($selected_template_id);
		
		$messages[] = '<div id="message" class="updated"><p>' . __('The pricing table has been successfully deleted', 'bbold') . '</p></div>';
		break;
}

global $current_user;
update_user_option($current_user->ID, 'recently_edited_template', $selected_template_id);

//display admin notices & messages
if(!empty($messages)) foreach($messages as $message) { echo $message; }

//disable blocks archive if no template
$disabled = $selected_template_id === 0 ? 'metabox-holder-disabled' : '';

?>

<div class="wrap">

	<!-- Just a little advertisment-->
	<div class="promo_box">
		<a href="http://herowp.com/?ref=hero-pricing-tables" target="_blank"><img src="<?php echo HEROWP_DIR.'assets/images/promo.png' ?>" /></a>
		<a id="hide" href="#">Hide advertisment</a>
	</div>
	<!-- Just a little advertisment-->
	
	<div id="icon-themes" class="icon32"><br/></div>
	<h2><?php echo $this->args['page_title'] ?></h2>
	
	<div id="page-builder-frame">
	
		<div id="page-builder-column" class="metabox-holder <?php echo $disabled ?>">
			<div id="page-builder-archive" class="postbox">
				<h3 class="hndle"><span><?php _e('Available Blocks', 'bbold') ?></span><span id="removing-block"><?php _e('Deleting', 'bbold') ?></span></h3>
				<div class="inside">
                <p><?php _e('Drag pricing table into the right area and edit details to suit your needs.', 'bbold') ?></p>
                <p><?php _e('When finish editing, <strong>copy the shortcode</strong> from the right (i.e [pricing_layout id="your_id"]), into any page or post .', 'bbold') ?></p>
				<div class="page-builder-filter"></div>
					<ul id="blocks-archive" class="cf">
						<?php $this->blocks_archive() ?>
					</ul>
				<p class="promo-link"><a href="https://herowp.com/herowp-premium-pricing-tables-wordpress-plugin-demo/?ref=hero-pricing-tables" target="_blank">Check the premium version of this plugin.</a></p>	
				</div>
			</div>
		</div>
	
		<div id="page-builder-fixed">
			<div id="page-builder">
				<div class="aqpb-tabs-nav">
				
					<div class="aqpb-tabs-arrow aqpb-tabs-arrow-left">
						<a>&laquo;</a>
					</div>
					
					<div class="aqpb-tabs-wrapper">
						<div class="aqpb-tabs">
							
							<?php
							foreach ( (array) $templates as $template ) {
								if($selected_template_id == $template->ID) {
									echo '<span id="template_'.$template->ID.'" class="aqpb-tab aqpb-tab-active aqpb-tab-sortable">'. htmlspecialchars($template->post_title) .'</span>';
								} else {
									echo '<a id="template_'.$template->ID.'" class="aqpb-tab aqpb-tab-sortable" href="' . esc_url(add_query_arg(
										array(
											'page' => $this->args['page_slug'], 
											'action' => 'edit',
											'template' => $template->ID,
										),
										admin_url( 'themes.php' )
									)) . '">'. htmlspecialchars($template->post_title) .'</a>';
								}
							}
							?>
							
							<!--add new template button-->
							<?php if($selected_template_id == 0) { ?>
							<span class="aqpb-tab aqpb-tab-add aqpb-tab-active"><abbr title="Add Template">+</abbr></span>
							<?php } else { ?>
							<a class="aqpb-tab aqpb-tab-add" href="<?php
								echo esc_url(add_query_arg(
									array(
										'page' => $this->args['page_slug'], 
										'action' => 'edit',
										'template' => 0,
									),
									admin_url( 'themes.php' )
								));
							?>">
								<abbr title="Add Template">+</abbr>
							</a>
							<?php } ?>
							
						</div>
					</div>
					
					<div class="aqpb-tabs-arrow aqpb-tabs-arrow-right">
						<a>&raquo;</a>
					</div>
					
				</div>
				<div class="aqpb-wrap aqpbdiv">
					<form id="update-page-template" action="<?php echo $this->args['page_url'] ?>" method="post" enctype="multipart/form-data">
						<div id="aqpb-header">
							
								<div id="submitpost" class="submitbox">
									<div class="major-publishing-actions cf">
									
										<label class="open-label" for="template-name">
											<span><?php _e('Pricing Template', 'bbold') ?></span>
											<input name="template-name" id="template-name" style="width:23em;" type="text" class="template-name regular-text" title="Enter pricing table name here" placeholder="Enter pricing table name here" value="<?php echo is_object($selected_template_object) ? $selected_template_object->post_title : ''; ?>">
										</label>
										
										<div id="template-shortcode">
											<input type="text" readonly="readonly" value='[pricing_layout id="<?php echo $selected_template_id ?>"]' onclick="select()"/>
										</div>
										
										<div class="publishing-action">
											<?php submit_button( empty( $selected_template_id ) ? __( 'Create Template', 'bbold' ) : __( 'Save Template', 'bbold' ), 'button-primary ', 'save_template', false, array( 'id' => 'save_template_header' ) ); ?>
										</div><!-- END .publishing-action -->
										
										<?php if(!empty($selected_template_id)) { ?>
										<div class="delete-action">
											<?php 
											echo '<a class="submitdelete deletion template-delete" href="' . esc_url(add_query_arg(
												array(
													'page' => $this->args['page_slug'], 
													'action' => 'delete',
													'template' => $selected_template_id,
													'_wpnonce' => wp_create_nonce('delete-template'),
												),
												admin_url( 'themes.php' )
											)) . '">'. __('Delete Template', 'bbold') .'</a>';
											?>
										</div><!-- END .delete-action -->
										
										
										
								<div class="clone-action">
                                    <?php 
                                    echo '<a class="submitclone cloning template-clone" href="' . esc_url(add_query_arg(
                                        array(
                                            'page' => $this->args['page_slug'], 
                                            'action' => 'clone',
                                            'template' => $selected_template_id,
                                            'template-name' => is_object($selected_template_object) ? urlencode($selected_template_object->post_title) : '',
                                            '_wpnonce' => wp_create_nonce('clone-template'),
                                        ),
                                        admin_url( 'themes.php' )
                                    )) . '">'. __('Duplicate Template', 'aqpb-l10n') .'</a>';
                                    ?>
                                </div><!-- END .clone-action -->
										
										
										
										
										<?php } ?>
										
									</div><!-- END .major-publishing-actions -->
								</div><!-- END #submitpost .submitbox -->
								
								<?php 
								if($selected_template_id === 0) {
									wp_nonce_field( 'create-template', 'create-template-nonce' ); 
								} else {
									wp_nonce_field( 'update-template', 'update-template-nonce' );
								}
								?>	
								<input type="hidden" name="action" value="<?php echo empty( $selected_template_id ) ? 'create' : 'update' ?>"/>
								<input type="hidden" name="template" id="template" value="<?php echo $selected_template_id ?>"/>
								<input type="hidden" id="aqpb-nonce" name="aqpb-nonce" value="<?php echo wp_create_nonce('aqpb-settings-page-nonce') ?>"/>
							
						</div>
						
						<div id="aqpb-body">
							
							<ul class="blocks cf" id="blocks-to-edit">
								<?php 
								if($selected_template_id === 0) {
									echo '<p class="empty-template">';
									echo __('To create a template, give it a name above and click Create Template. Then add blocks from the left and drag them into this area.
									<br/><br/>
									You can drag and drop the blocks to put them in the order you want. <br/><br/>
									Click on the small arrow at the corner of the block to reveal additional configuration options. <br/><br/>
									You can also resize each block by clicking on either side of the block. <br/><br/>
									When you have finished building your custom page template, make sure you click the Save Template button.<br/><br/>
									Then copy the shortcode to any page or post.
									', 'herowp_pricing_tables');
									echo '</p>';
									
									
								} else {
									$this->display_blocks($selected_template_id); 
								}
								?>
							</ul>
							
						</div>
						
						<div id="aqpb-footer">
							<div class="major-publishing-actions cf">
								<div class="publishing-action">
									<?php if(!empty($selected_template_id)) {
										submit_button( __( 'Save Template','bbold' ), 'button-primary ', 'save_template', false, array( 'id' => 'save_template_footer' ) ); 
									} ?>
								</div><!-- END .publishing-action -->
							</div><!-- END .major-publishing-actions -->
						</div>
						
					</div>
				</form>
			</div>
			
			
		</div>
		
		
	</div>
</div>