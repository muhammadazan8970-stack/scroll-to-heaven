<?php
/**
 * Plugin Name: Scroll to Heaven
 * Description: A powerful scrollbar customization plugin to style vertical, horizontal, and corner scrollbars globally across WordPress.
 * Version:     1.0.0
 * Author:      Ultimate Leverage Arts
 * Text Domain: scroll-to-heaven
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'STH_VERSION', '1.0.0' );
define( 'STH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'STH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load classes
require_once STH_PLUGIN_DIR . 'includes/class-sth-admin.php';
require_once STH_PLUGIN_DIR . 'includes/class-sth-frontend.php';

// Initialize the plugin
function sth_init() {
	if ( is_admin() ) {
		$sth_admin = new STH_Admin();
		$sth_admin->init();
	}

	$sth_frontend = new STH_Frontend();
	$sth_frontend->init();
}
add_action( 'plugins_loaded', 'sth_init' );
