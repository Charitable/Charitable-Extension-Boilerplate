<?php

/**
 * Charitable Extension Boilerplate Core Functions.
 *
 * General core functions.
 *
 * @author      Studio164a
 * @category    Core
 * @package     Charitable Extension Boilerplate
 * @subpackage  Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * This returns the original Charitable_Extension_Boilerplate object.
 *
 * Use this whenever you want to get an instance of the class. There is no
 * reason to instantiate a new object, though you can do so if you're stubborn :)
 *
 * @return  Charitable_Extension_Boilerplate
 * @since   1.0.0
 */
function charitable_extension_boilerplate() {
	return Charitable_Extension_Boilerplate::get_instance();
}

/**
 * Displays a template.
 *
 * @param   string|array $template_name A single template name or an ordered array of template
 * @param   array        $args Optional array of arguments to pass to the view.
 * @return  Charitable_Extension_Boilerplate_Template
 * @since   1.0.0
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
