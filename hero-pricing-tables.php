<?php
/**
 * 
 * Plugin Name: HeroWP Pricing Tables
 * Plugin URI: http://herowp.com/hero-pricing-tables-a-cool-wordpress-plugin-to-create-pricing-tables
 * Description: Drag and drop responsive pricing tables, with many customization options
 * Version: 1.1
 * Author: Simion Radu
 * Author URI: http://herowp.com
 *
 */ 

 
/**
 * Copyright (c) 2014 Simion Radu. All rights reserved. Based under the Aqua Page Builder Plugin by Syamil MJ. 
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

//we activate the plugin only if class AQ_Page_Builder does not exist already
if (!class_exists('AQ_Page_Builder')) {
 
//definitions
if(!defined('HEROWP_PATH')) define( 'HEROWP_PATH', plugin_dir_path(__FILE__) );
if(!defined('HEROWP_DIR')) define( 'HEROWP_DIR', plugin_dir_url(__FILE__) );


//required functions & classes
require_once(HEROWP_PATH . 'functions/aqpb_config.php');
require_once(HEROWP_PATH . 'functions/aqpb_blocks.php');
require_once(HEROWP_PATH . 'classes/class-aq-page-builder.php');
require_once(HEROWP_PATH . 'classes/class-aq-block.php');
require_once(HEROWP_PATH . 'functions/aqpb_functions.php');

//used blocks in MYWAY
require_once(HEROWP_PATH . 'blocks/aq-pricing-tables.php'); 



//register used blocks in MYWAY
aq_register_block('AQ_Pricing_Tables');



//fire up page builder
$aqpb_config = aq_page_builder_config();
$aq_page_builder = new AQ_Page_Builder($aqpb_config);
if(!is_network_admin()) $aq_page_builder->init();
}

//otherwise output an error
else {
add_action('admin_notices', create_function('', "echo __('Hero Pricing Tables Plugin has been activated, however it won\'t work because the required class is in use by other plugin. Most likely, the plugin who already use this class is Aqua Page Builder. Deactivate that plugin, or contact the plugin author.', 'hero_pricing_tables');"));
}