<?php
/**
 * Charitable Extension Boilerplate Core Functions.
 *
 * @package   Charitable Extension Boilerplate/Functions/Core
 * @copyright Copyright (c) 2019, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This returns the original Charitable_Extension_Boilerplate object.
 *
 * Use this whenever you want to get an instance of the class. There is no
 * reason to instantiate a new object, though you can do so if you're stubborn :)
 *
 * @since   1.0.0
 *
 * @return Charitable_Extension_Boilerplate
 */
function charitable_extension_boilerplate() {
	return Charitable_Extension_Boilerplate::get_instance();
}

/**
 * This returns the Charitable_Extension_Boilerplate_Deprecated object.
 *
 * @since  1.0.0
 *
 * @return Charitable_Extension_Boilerplate_Deprecated
 */
function charitable_extension_boilerplate_deprecated() {
	return Charitable_Extension_Boilerplate_Deprecated::get_instance();
}

/**
 * Displays a template.
 *
 * @since  1.0.0
 *
 * @param  string|array $template_name A single template name or an ordered array of template.
 * @param  array        $args          Optional array of arguments to pass to the view.
 * @return Charitable_Extension_Boilerplate_Template
 */
function charitable_extension_boilerplate_template( $template_name, array $args = array() ) {
	if ( empty( $args ) ) {
		$template = new Charitable_Extension_Boilerplate_Template( $template_name );
	} else {
		$template = new Charitable_Extension_Boilerplate_Template( $template_name, false );
		$template->set_view_args( $args );
		$template->render();
	}

	return $template;
}
