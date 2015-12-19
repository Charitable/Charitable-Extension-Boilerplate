<?php 
/**
 * Charitable Extension Boilerplate admin hooks.
 * 
 * @package     Charitable Extension Boilerplate/Functions/Admin
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add a "Extension Boilerplate" section to the Extensions settings area of Charitable.
 *
 * @see Charitable_Extension_Boilerplate_Admin::add_extension_boilerplate_settings()
 */
add_filter( 'charitable_settings_tab_fields_extensions', array( Charitable_Extension_Boilerplate_Admin::get_instance(), 'add_extension_boilerplate_settings' ), 6 );