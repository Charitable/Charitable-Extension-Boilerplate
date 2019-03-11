<?php
/**
 * The class responsible for adding & saving extra settings in the Charitable admin.
 *
 * @package   Charitable Extension Boilerplate/Classes/Charitable_Extension_Boilerplate_Admin
 * @copyright Copyright (c) 2019, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Charitable_Extension_Boilerplate_Admin' ) ) :

	/**
	 * Charitable_Extension_Boilerplate_Admin
	 *
	 * @since 1.0.0
	 */
	class Charitable_Extension_Boilerplate_Admin {

		/**
		 * The single static class instance.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Extension_Boilerplate_Admin
		 */
		private static $instance = null;

		/**
		 * Create and return the class object.
		 *
		 * @since 1.0.0
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
		 * @since  1.0.0
		 *
		 * @param  string[] $links Links to be added to plugin actions row.
		 * @return string[]
		 */
		public function add_plugin_action_links( $links ) {
			$links[] = '<a href="' . admin_url( 'admin.php?page=charitable-settings&tab=extensions' ) . '">' . __( 'Settings', 'charitable-newsletter-connect' ) . '</a>';
			return $links;
		}

		/**
		 * Add settings to the Extensions settings tab.
		 *
		 * @since  1.0.0
		 *
		 * @param  array[] $fields Settings to display in tab.
		 * @return array[]
		 */
		public function add_extension_boilerplate_settings( $fields = array() ) {
			if ( ! charitable_is_settings_view( 'extensions' ) ) {
				return $fields;
			}

			$custom_fields = array(
				'section_extension_boilerplate' => array(
					'title'    => __( 'Extension Boilerplate', 'charitable-extension-boilerplate' ),
					'type'     => 'heading',
					'priority' => 50,
				),
				'extension_boilerplate_setting_text' => array(
					'title'    => __( 'Text Field Setting', 'charitable-extension-boilerplate' ),
					'type'     => 'text',
					'priority' => 50.2,
					'default'  => __( '', 'charitable-extension-boilerplate' ),
				),
				'extension_boilerplate_setting_checkbox' => array(
					'title'    => __( 'Checkbox Setting', 'charitable-extension-boilerplate' ),
					'type'     => 'checkbox',
					'priority' => 50.6,
					'default'  => false,
					'help'     => __( '', 'charitable-extension-boilerplate' ),
				),
			);

			$fields = array_merge( $fields, $custom_fields );

			return $fields;
		}
	}

endif;
