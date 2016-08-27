<?php
/**
 * Charitable Extension Boilerplate Gateway Hooks
 *
 * @package     Charitable Extension Boilerplate/Functions/Gateway
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Register our new gateway.
 *
 * @see     Charitable_Gateway_Extension_Boilerplate::register_gateway()
 */
add_filter( 'charitable_payment_gateways', array( 'Charitable_Gateway_Extension_Boilerplate', 'register_gateway' ) );

/**
 * Validate the donation form submission before processing.
 *
 * @see     Charitable_Gateway_Extension_Boilerplate::validate_donation()
 */
add_filter( 'charitable_validate_donation_form_submission_gateway', array( 'Charitable_Gateway_Extension_Boilerplate', 'validate_donation' ), 10, 3 );

/**
 * Process the donation.
 *
 * @see     Charitable_Gateway_Extension_Boilerplate::process_donation()
 */
add_filter( 'charitable_process_donation_gateway_id', array( 'Charitable_Gateway_Extension_Boilerplate', 'process_donation' ), 10, 3 );