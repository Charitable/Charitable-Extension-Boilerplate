<?php
/**
 * The class responsible for adding & saving extra settings in the Charitable admin.
 *
 * @package     Charitable Extension Boilerplate/Classes/Charitable_Extension_Boilerplate_Admin
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2016, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'Charitable_Extension_Boilerplate_Admin' ) ) :

	/**
	 * Charitable_Extension_Boilerplate_Admin
	 *
	 * @since       1.0.0
	 */
	class Charitable_Extension_Boilerplate_Admin {

		/**
		 * @var     Charitable_Extension_Boilerplate_Admin
		 * @access  private
		 * @static
		 * @since   1.0.0
		 */
		private static $instance = null;

		/**
		 * Create class object. Private constructor.
		 *
		 * @access  private
		 * @since   1.0.0
		 */
		private function __construct() {
			require_once( 'upgrades/class-charitable-extension-boilerplate-upgrade.php' );
			require_once( 'upgrades/charitable-extension-boilerplate-upgrade-hooks.php' );
		}

		/**
		 * Create and return the class object.
		 *
		 * @access  public
		 * @static
		 * @since   1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new Charitable_Extension_Boilerplate_Admin();
			}

			return self::$instance;
		}

		/**
		 * Add custom links to the plugin actions.
		 *
		 * @param   string[] $links
		 * @return  string[]
		 * @access  public
		 * @since   1.0.0
		 */
		public function add_plugin_action_links( $links ) {
			$links[] = '<a href="' . admin_url( 'admin.php?page=charitable-settings&tab=extensions' ) . '">' . __( 'Settings', 'charitable-newsletter-connect' ) . '</a>';
			return $links;
		}

		/**
		 * Add settings to the Extensions settings tab.
		 *
		 * @param   array[] $fields
		 * @return  array[]
		 * @access  public
		 * @since   1.0.0
		*/
		public function add_extension_boilerplate_settings( $fields = array() ) {
			if ( ! charitable_is_settings_view( 'extensions' ) ) {
				return $fields;
			}

			$custom_fields = array(
				'section_extension_boilerplate' => array(
					'title'             => __( 'Extension Boilerplate', 'charitable-extension-boilerplate' ),
					'type'              => 'heading',
					'priority'          => 50,
				),
				'extension_boilerplate_setting_text' => array(
					'title'             => __( 'Text Field Setting', 'charitable-extension-boilerplate' ),
					'type'              => 'text',
					'priority'          => 50.2,
					'default'           => __( '', 'charitable-extension-boilerplate' ),
				),
				'extension_boilerplate_setting_checkbox' => array(
					'title'             => __( 'Checkbox Setting', 'charitable-extension-boilerplate' ),
					'type'              => 'checkbox',
					'priority'          => 50.6,
					'default'           => false,
					'help'              => __( '', 'charitable-extension-boilerplate' ),
				),
			);

			$fields = array_merge( $fields, $custom_fields );

			return $fields;
		}
	}

endif; // End class_exists check
