<?php
/*
Plugin Name: DD WooCommerce Mailchimp Discount
Plugin URI: #
Description:  DD WooCommerce Mailchimp Discount
Version: 1.0
Author: Danil Derevyanchenko
Author URI: https://danilderevyanchenko.com.ua/portfolio/
Licence: GPLv2 or later
Text Domain: DDWMD
*/ 

/*
DDWMD is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

DDWMD is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with DDWMD. If not, see {URI to Plugin License}.
*/


// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

if ( ! defined( 'DDWMD_PATH') ) {
	/**
	 * Dir Path.
	 * */ 
	define( 'DDWMD_PATH', plugin_dir_path( __FILE__ ) );
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	/**
	 * Require once the Composer Autoload.
	 */
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

if ( file_exists( dirname( __FILE__ ) . '/includes/helpers/autoload.php' ) ) {
	/**
	 * Require custom autholoading for php classes.
	 */
	require_once dirname( __FILE__ ) . '/includes/helpers/autoload.php';
}

if ( ! class_exists( 'ACF' ) ) {
	/**
	 * Define path and URL to the ACF plugin.
	 * 
	 * Include the ACF plugin.
	 * */ 
	define( 'MY_ACF_PATH', DDWMD_PATH . '/vendor/acf/' );
	define( 'MY_ACF_URL', DDWMD_PATH . '/vendor/acf/' );

    require_once DDWMD_PATH . 'vendor/acf/acf.php';
}

if ( file_exists( dirname( __FILE__ ) . '/includes/helpers/custom-functions.php' ) ) {
	/**
	 * Require once the array of acf fields for Settings Page.
	 */
	require_once dirname( __FILE__ ) . '/includes/helpers/custom-functions.php';
}


if ( class_exists( DDWMD\Inc\Plugin::class ) ) {
	/**
	 * Initialize all the core classes of the plugin
	*/
	DDWMD\Inc\Plugin::get_instance();
}