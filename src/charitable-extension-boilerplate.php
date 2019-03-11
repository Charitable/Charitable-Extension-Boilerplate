<?php
/**
 * Plugin Name:       Charitable - Extension Boilerplate
 * Plugin URI:
 * Description:
 * Version:           1.0.0
 * Author:            WP Charitable
 * Author URI:        https://www.wpcharitable.com
 * Requires at least: 4.2
 * Tested up to:      5.1
 *
 * Text Domain: charitable-extension-boilerplate
 * Domain Path: /languages/
 *
 * @package  Charitable Extension Boilerplate
 * @category Core
 * @author   WP Charitable
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load plugin class, but only if Charitable is found and activated.
 *
 * @return false|Charitable_Extension_Boilerplate Whether the class was loaded.
 */
add_action( 'plugins_loaded', function() {
	require_once( 'includes/class-charitable-extension-boilerplate.php' );

	/* Check for Charitable */
	if ( ! class_exists( 'Charitable' ) ) {
		if ( ! class_exists( 'Charitable_Extension_Activation' ) ) {
			require_once 'includes/admin/class-charitable-extension-activation.php';
		}

		$activation = new Charitable_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();
	} else {
		new Charitable_Extension_Boilerplate( __FILE__ );
	}
} );
