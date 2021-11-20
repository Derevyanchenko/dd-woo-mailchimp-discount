<?php
/*
Plugin Name: DD WooCommerce Mailchimp Discount
Plugin URI: #
Description:  DD WooCommerce Mailchimp Discount
Version: 1.0
Author: Danil Derevyanchenko
Author URI: https://derevyanchenko.github.io/portfolio/
Licence: GPLv2 or later
Text Domain: ddWooMD
Domain Path: /lang
*/ 

if( ! defined('ABSPATH') ) {
    die;
}  

define('DDWOOMD_PATH', plugin_dir_path(__FILE__));

// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', DDWOOMD_PATH . '/vendor/acf/' );
define( 'MY_ACF_URL', DDWOOMD_PATH . '/vendor/acf/' );

// Include the ACF plugin.
if ( ! class_exists( 'ACF' ) ) {
    require_once DDWOOMD_PATH . 'vendor/acf/acf.php';
}


// (Optional) Hide the ACF admin menu item.
add_filter('acf/settings/show_admin', 'my_acf_settings_show_admin');
function my_acf_settings_show_admin( $show_admin ) {
    return false;
}

require_once DDWOOMD_PATH . 'includes/ddWooMD-settings-page.php';
require_once DDWOOMD_PATH . 'includes/class-ddWooMD-core.php';
require_once DDWOOMD_PATH . 'includes/class-ddWooMD-shortcodes.php';
require_once DDWOOMD_PATH . 'includes/class-ddWooMD-ajax.php';

/******************************************
 * WooCommerce discount Mailchimp
 *****************************************/

	
	class ddWooMD
	{

		public function __construct() 
		{
			add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
		}


		/**
		 * enqueue frontend scripts method
		 */
		public function enqueue_styles()
		{
			wp_register_script('ddwoomd_main_script', plugins_url( '/assets/js/main.js', __FILE__ ), array('jquery'), time() );

			wp_localize_script('ddwoomd_main_script', 'ddWooMD_ajax', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('_wpnonce'),
				'title' => esc_html__('ddWooMD test title', 'ddWooMD'),
			));

			wp_enqueue_script( 'ddwoomd_main_script' );
		}

		/**
		 * activation hook
		 */
		static function activation() 
		{
			flush_rewrite_rules();
		}

		/**
		 * deactivation hook
		 */
		static function deactivation()
		{
			flush_rewrite_rules();
		}

	}


// if ( ! class_exists( 'ddWooMD' ) ) {
	$ddWooMD = new ddWooMD();
// }

register_activation_hook( __FILE__, array( $ddWooMD, 'activation' ) );
register_deactivation_hook( __FILE__, array( $ddWooMD, 'deactivation' ) );