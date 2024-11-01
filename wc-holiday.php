<?php
/*
Plugin Name: Holiday for WooCommerce
Plugin URI: https://github.com/paulmaloney/wc-holiday
Version: 1.1
Description: This plugin gives basic options to disable WooCommerce
Author: Paul Maloney
Author URI: https://paulmaloney.net
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wc-holiday
Copyright 2023 Paul Maloney

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action( 'admin_menu', 'WCH_admin', 65);
function WCH_admin() {
 	add_submenu_page( 'woocommerce', 'Holiday for WooCommerce', 'Holiday for WooCommerce', 'manage_options', 'wc-holiday', 'WCH_options', ); 
}
add_action( 'admin_init', 'WCH_admin_init' );

function WCH_admin_init() {
  	register_setting( 'WCH_group', 'WCH_settings' );	 
  	add_settings_section( 'WCH-section-1', __( 'Settings', 'textdomain' ), 'WCH_section1', 'wc-holiday' );
  	add_settings_field( 'WCH-field-1-1', __( 'Custom Message', 'textdomain' ), 'WCH_TEXT_1', 'wc-holiday', 'WCH-section-1' );
	add_settings_field( 'WCH-field-1-2', __( 'Holiday Mode', 'textdomain' ), 'WCH_TEXT_2', 'wc-holiday', 'WCH-section-1' );	
}

function WCH_options() {
?>

<style>.wc_wide {width: 350px!important;}</style>
  <div class="wrap">
      <h2><?php _e('Holiday for WooCommerce', 'textdomain'); ?></h2>
      <form action="options.php" method="POST">
        <?php settings_fields('WCH_group'); ?>
        <?php do_settings_sections('wc-holiday'); ?>
        <?php submit_button(); ?>
      </form>
  </div>
<?php }

function WCH_TEXT_1() {
	
	$settings = (array) get_option( 'WCH_settings' );
	$field = "WCH-field-1-1";
	$value = esc_attr( $settings[$field] );
	
	echo "<textarea class='wc_wide' type='text' name='WCH_settings[$field]' value='$value'>$value</textarea>";
}
function WCH_TEXT_2() {
	
	$settings = (array) get_option( 'WCH_settings' );
	$field = "WCH-field-1-2";
	$value = esc_attr( $settings[$field] );
	
?>
  <input type="radio" id="on" name="WCH_settings[<?php echo $field;?>]" value="on" <?php if ($value == "on"){ echo "checked"; };?>>
  <label>On</label><br>
  <input type="radio" id="off" name="WCH_settings[<?php echo $field;?>]" value="off" <?php if ($value == "off"){ echo "checked"; };?>>
  <label>Off</label><br>
<?php

}


function WCH_sanitize( $input ) {

	$settings = (array) get_option( 'WCH_settings' );
	
	if ( $some_condition == $input['WCH-field-1-1'] ) {
		$output['WCH-field-1-1'] = $input['WCH-field-1-1'];
	} else {
		add_settings_error( 'WCH_settings', 'invalid-WCH-field-1-1', 'You have entered an invalid value.' );
	}
	
	if ( $some_condition == $input['WCH-field-1-2'] ) {
		$output['WCH-field-1-2'] = $input['WCH-field-1-2'];
	} else {
		add_settings_error( 'WCH_settings', 'invalid-WCH-field-1-2', 'You have entered an invalid value.' );
	}
	
	return $output;

}

$settingss = (array) get_option( 'WCH_settings' );	
$field2 ="WCH-field-1-2";
$value2 = esc_attr( $settingss[$field2] );	

if ($value2 == "on"){ 

add_action ('init', 'WCH_mode');

function WCH_mode() {
   remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
   remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
   remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
   remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
   add_action( 'woocommerce_before_main_content', 'WCH_disabled', 5 );
   add_action( 'woocommerce_before_cart', 'WCH_disabled', 5 );
   add_action( 'woocommerce_before_checkout_form', 'WCH_disabled', 5 );

}

function WCH_disabled() {

	$settings = (array) get_option( 'WCH_settings' );
	$field = "WCH-field-1-1";
	$value = esc_attr( $settings[$field] );
	$msg = $value;
    wc_print_notice( $msg, 'error' );
    return $msg;
} 


}

