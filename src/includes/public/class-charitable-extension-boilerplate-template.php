<?php
/**
 * Charitable Extension Boilerplate template
 *
 * @version     1.0.0
 * @package     Charitable Extension Boilerplate/Classes/Charitable_Extension_Boilerplate_Template
 * @author      Eric Daams
 * @copyright   Copyright (c) 2016, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'Charitable_Extension_Boilerplate_Template' ) ) :

	/**
	 * Charitable_Extension_Boilerplate_Template
	 *
	 * @since       1.0.0
	 */
	class Charitable_Extension_Boilerplate_Template extends Charitable_Template {

		/**
		 * Set theme template path.
		 *
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function get_theme_template_path() {
			return trailingslashit( apply_filters( 'charitable_extension_boilerplate_theme_template_path', 'charitable/charitable-extension-boilerplate' ) );
		}

		/**
		 * Return the base template path.
		 *
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function get_base_template_path() {
			return charitable_extension_boilerplate()->get_path( 'templates' );
		}
	}

endif;
