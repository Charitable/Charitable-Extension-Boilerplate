<?php
/**
 * The main Charitable Extension Boilerplate class.
 *
 * The responsibility of this class is to load all the plugin's functionality.
 *
 * @package   Charitable Extension Boilerplate
 * @copyright Copyright (c) 2019, Eric Daams
 * @license   http://opensource.org/licenses/gpl-1.0.0.php GNU Public License
 * @version   1.0.0
 * @since     1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Charitable_Extension_Boilerplate' ) ) :

	/**
	 * Charitable_Extension_Boilerplate
	 *
	 * @since 1.0.0
	 */
	class Charitable_Extension_Boilerplate {

		/** Plugin version. */
		const VERSION = '1.0.0';

		/** The extension name. */
		const NAME = 'Charitable Extension Boilerplate';

		/** The extension author. */
		const AUTHOR = 'Studio 164a';

		/**
		 * Single static instance of this class.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Extension_Boilerplate
		 */
		private static $instance = null;

		/**
		 * The root file of the plugin.
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		private $plugin_file;

		/**
		 * The root directory of the plugin.
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		private $directory_path;

		/**
		 * The root directory of the plugin as a URL.
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		private $directory_url;

		/**
		 * Create class instance.
		 *
		 * @since 1.0.0
		 *
		 * @param string $plugin_file Absolute path to the main plugin file.
		 */
		public function __construct( $plugin_file ) {
			$this->plugin_file    = $plugin_file;
			$this->directory_path = plugin_dir_path( $plugin_file );
			$this->directory_url  = plugin_dir_url( $plugin_file );

			add_action( 'charitable_start', array( $this, 'start' ), 6 );
		}

		/**
		 * Returns the original instance of this class.
		 *
		 * @since  1.0.0
		 *
		 * @return Charitable
		 */
		public static function get_instance() {
			return self::$instance;
		}

		/**
		 * Run the startup sequence on the charitable_start hook.
		 *
		 * This is only ever executed once.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function start() {
			if ( $this->started() ) {
				return;
			}

			self::$instance = $this;

			$this->load_dependencies();

			$this->maybe_start_admin();

			$this->maybe_start_public();

			$this->setup_licensing();

			$this->setup_i18n();

			$this->attach_hooks_and_filters();

			/**
			 * Do something when the plugin is first started.
			 *
			 * @since 1.0.0
			 *
			 * @param Charitable_Extension_Boilerplate $plugin This class instance.
			 */
			do_action( 'charitable_extension_boilerplate_start', $this );
		}

		/**
		 * Include necessary files.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function load_dependencies() {
			require_once( $this->get_path( 'includes' ) . 'charitable-extension-boilerplate-core-functions.php' );

			/* Deprecated */
			require_once( $this->get_path( 'includes/deprecated/class-charitable-extension-boilerplate-deprecated.php' ) );

			/* Upgrades */
			require_once( $this->get_path( 'includes/upgrades/class-charitable-extension-boilerplate-upgrade.php' ) );
		}

		/**
		 * Load the admin-only functionality.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function maybe_start_admin() {
			if ( ! is_admin() ) {
				return;
			}

			require_once( $this->get_path( 'includes' ) . 'admin/class-charitable-extension-boilerplate-admin.php' );
			require_once( $this->get_path( 'includes' ) . 'admin/charitable-extension-boilerplate-admin-hooks.php' );
		}

		/**
		 * Load the public-only functionality.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function maybe_start_public() {
			require_once( $this->get_path( 'includes' ) . 'public/class-charitable-extension-boilerplate-template.php' );
		}

		/**
		 * Set up licensing for the extension.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function setup_licensing() {
			charitable_get_helper( 'licenses' )->register_licensed_product(
				Charitable_Extension_Boilerplate::NAME,
				Charitable_Extension_Boilerplate::AUTHOR,
				Charitable_Extension_Boilerplate::VERSION,
				$this->plugin_file
			);
		}

		/**
		 * Set up the internationalisation for the plugin.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function setup_i18n() {
			if ( class_exists( 'Charitable_i18n' ) ) {

				require_once( $this->get_path( 'includes' ) . 'i18n/class-charitable-extension-boilerplate-i18n.php' );

				Charitable_Extension_Boilerplate_i18n::get_instance();
			}
		}

		/**
		 * Set up hooks and filters.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		private function attach_hooks_and_filters() {
			/**
			 * Set up upgrade process.
			 */
			// add_action( 'admin_notices', array( Charitable_Extension_Boilerplate_Upgrade::get_instance(), 'add_upgrade_notice' ) );
			// add_action( 'init', array( Charitable_Extension_Boilerplate_Upgrade::get_instance(), 'do_immediate_upgrades' ), 5 );
		}

		/**
		 * Returns whether the plugin has already started.
		 *
		 * @since  1.0.0
		 *
		 * @return boolean
		 */
		public function started() {
			return did_action( 'charitable_extension_boilerplate_start' ) || current_filter() == 'charitable_extension_boilerplate_start';
		}

		/**
		 * Returns the plugin's version number.
		 *
		 * @since  1.0.0
		 *
		 * @return string
		 */
		public function get_version() {
			return self::VERSION;
		}

		/**
		 * Returns plugin paths.
		 *
		 * @since   1.0.0
		 *
		 * @param  string  $type          If empty, returns the path to the plugin.
		 * @param  boolean $absolute_path If true, returns the file system path. If false, returns it as a URL.
		 * @return string
		 */
		public function get_path( $type = '', $absolute_path = true ) {
			$base = $absolute_path ? $this->directory_path : $this->directory_url;

			switch ( $type ) {
				case 'includes':
					$path = $base . 'includes/';
					break;

				case 'templates':
					$path = $base . 'templates/';
					break;

				case 'directory':
					$path = $base;
					break;

				default:
					$path = $this->plugin_file;
			}

			return $path;
		}

		/**
		 * Throw error on object clone.
		 *
		 * This class is specifically designed to be instantiated once. You can retrieve the instance using charitable()
		 *
		 * @since   1.0.0
		 *
		 * @return void
		 */
		public function __clone() {
			charitable_extension_boilerplate_deprecated()->doing_it_wrong(
				__FUNCTION__,
				__( 'Cloning this object is forbidden.', 'charitable-extension-boilerplate' ),
				'1.0.0'
			);
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since   1.0.0
		 *
		 * @return void
		 */
		public function __wakeup() {
			charitable_extension_boilerplate_deprecated()->doing_it_wrong(
				__FUNCTION__,
				__( 'Unserializing instances of this class is forbidden.', 'charitable-extension-boilerplate' ),
				'1.0.0'
			);
		}
	}

endif;
