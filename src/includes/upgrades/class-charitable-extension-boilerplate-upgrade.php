<?php
/**
 * Upgrade class.
 *
 * @package   Charitable Extension Boilerplate/Classes/Charitable_Extension_Boilerplate_Upgrade
 * @copyright Copyright (c) 2019, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Charitable_Extension_Boilerplate_Upgrade' ) ) :

	/**
	 * Charitable_Extension_Boilerplate_Upgrade
	 *
	 * @since 1.0.0
	 */
	class Charitable_Extension_Boilerplate_Upgrade extends Charitable_Upgrade {

		/**
		 * The single class instance.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Extension_Boilerplate_Upgrade
		 */
		private static $instance = null;

		/**
		 * Option key for upgrade log.
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		protected $upgrade_log_key = 'charitable_extension_boilerplate_upgrade_log';

		/**
		 * Option key for plugin version.
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		protected $version_key = 'charitable_extension_boilerplate_version';

		/**
		 * Create class object.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->upgrade_actions = array();
		}

		/**
		 * Create and return the class object.
		 *
		 * @since  1.0.0
		 *
		 * @return Charitable_Extension_Boilerplate_Upgrade
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Add a completed upgrade to the upgrade log.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $upgrade The upgrade action.
		 * @return boolean False if value was not updated and true if value was updated.
		 */
		protected function update_upgrade_log( $upgrade ) {
			$log = get_option( $this->upgrade_log_key );

			$log[ $upgrade ] = array(
				'time'    => time(),
				'version' => charitable_Extension_Boilerplate()->get_version(),
			);

			return update_option( $this->upgrade_log_key, $log );
		}
	}

endif;
