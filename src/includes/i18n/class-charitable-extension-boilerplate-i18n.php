<?php
/**
 * Sets up translations for Charitable Extension Boilerplate.
 *
 * @package     Charitable/Classes/Charitable_i18n
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2016, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/* Ensure that Charitable_i18n exists */
if ( ! class_exists( 'Charitable_i18n' ) ) :
	return;
endif;

if ( ! class_exists( 'Charitable_Extension_Boilerplate_i18n' ) ) :

	/**
	 * Charitable_Extension_Boilerplate_i18n
	 *
	 * @since       1.0.0
	 */
	class Charitable_Extension_Boilerplate_i18n extends Charitable_i18n {

		/**
		 * @var     string
		 */
		protected $textdomain = 'charitable-extension-boilerplate';

		/**
		 * Set up the class.
		 *
		 * @access  protected
		 * @since   1.0.0
		 */
		protected function __construct() {
			$this->languages_directory = apply_filters( 'charitable_extension_boilerplate_languages_directory', 'charitable-extension-boilerplate/languages' );
			$this->locale = apply_filters( 'plugin_locale', get_locale(), $this->textdomain );
			$this->mofile = sprintf( '%1$s-%2$s.mo', $this->textdomain, $this->locale );

			$this->load_textdomain();
		}
	}

endif;