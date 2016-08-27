<?php
/**
 * Plugin Name: 		Charitable - Extension Boilerplate
 * Plugin URI:
 * Description:
 * Version: 			1.0.0
 * Author: 				WP Charitable
 * Author URI: 			https://www.wpcharitable.com
 * Requires at least: 	4.2
 * Tested up to: 		4.4
 *
 * Text Domain: 		charitable-extension-boilerplate
 * Domain Path: 		/languages/
 *
 * @package 			Charitable Extension Boilerplate
 * @category 			Core
 * @author 				WP Charitable
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Load plugin class, but only if Charitable is found and activated.
 *
 * @return 	void
 * @since 	1.0.0
 */
function charitable_extension_boilerplate_load() {
	require_once( 'includes/class-charitable-extension-boilerplate.php' );

	$has_dependencies = true;

	/* Check for Charitable */
	if ( ! class_exists( 'Charitable' ) ) {

		if ( ! class_exists( 'Charitable_Extension_Activation' ) ) {

			require_once 'includes/class-charitable-extension-activation.php';

		}

		$activation = new Charitable_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();

		$has_dependencies = false;

	} else {

		new Charitable_Extension_Boilerplate( __FILE__ );

	}
}

add_action( 'plugins_loaded', 'charitable_extension_boilerplate_load', 1 );
