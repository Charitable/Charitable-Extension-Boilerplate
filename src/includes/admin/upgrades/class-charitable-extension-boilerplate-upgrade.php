<?php
/**
 * Charitable Extension Boilerplate Upgrade class.
 *
 * The responsibility of this class is to manage migrations between versions of Charitable Extension Boilerplate.
 *
 * @package     Charitable Extension Boilerplate
 * @subpackage  Charitable Extension Boilerplate/Upgrade
 * @copyright   Copyright (c) 2016, Eric Daams
 * @license     http://opensource.org/licenses/gpl-1.0.0.php GNU Public License
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'Charitable_Extension_Boilerplate_Upgrade' ) ) :

	class Charitable_Extension_Boilerplate_Upgrade extends Charitable_Upgrade {

		/**
		 * @var     Charitable_Extension_Boilerplate_Upgrade
		 * @access  private
		 * @static
		 * @since   1.0.0
		 */
		private static $instance = null;

		/**
		 * Array of methods to perform when upgrading to specific versions.
		 *
		 * @var 	array
		 * @access 	protected
		 */
		protected $upgrade_actions;

		/**
		 * Option key for upgrade log.
		 *
		 * @var 	string
		 * @access 	protected
		 */
		protected $upgrade_log_key = 'charitable_extension_boilerplate_upgrade_log';

		/**
		 * Option key for plugin version.
		 *
		 * @var 	string
		 * @access 	protected
		 */
		protected $version_key = 'charitable_extension_boilerplate_version';

		/**
		 * Create and return the class object.
		 *
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Charitable_Extension_Boilerplate_Upgrade();
		}

		return self::$instance;
		}

		/**
		 * Manages the upgrade process.
		 *
		 * @param 	deprecated $db_version
		 * @param 	deprecated $edge_version
		 * @access 	protected
		 * @since 	1.0.0
		 */
		protected function __construct( $db_version = '', $edge_version = '' ) {

		$this->upgrade_actions = array(
		// 'sample_upgrade_routine' => array(
		// 	'version' => '1.0.0',
		// 	'message' => __( 'This is an example upgrade.', 'charitable-edd' ),
		// 	'prompt' => true, // Set to false if upgrade happens automatically
		// ),
		);

		}
	}
}

endif; // End class_exists check
