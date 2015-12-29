<?php
/**
 * Extension Boilerplate Gateway class
 *
 * @version     1.0.0
 * @package     Charitable Extension Boilerplate/Classes/Charitable_Gateway_Extension_Boilerplate
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Gateway_Extension_Boilerplate' ) ) : 

/**
 * Extension Boilerplate Gateway 
 *
 * @since       1.0.0
 */
class Charitable_Gateway_Extension_Boilerplate extends Charitable_Gateway {
    
    /**
     * @var     string
     */
    CONST ID = 'gateway_id';

    /**
     * @var     boolean  Flags whether the gateway requires credit card fields added to the donation form.
     * @access  protected
     * @since   1.0.0
     */
    protected $credit_card_form = true;

    /**
     * Instantiate the gateway class, defining its key values.
     *
     * @access  public
     * @since   1.0.0
     */
    public function __construct() {
        $this->name = apply_filters( 'charitable_gateway_extension_boilerplate_name', __( 'Extension Boilerplate', 'charitable-extension-boilerplate' ) );

        $this->defaults = array(
            'label' => __( 'Extension Boilerplate', 'charitable-extension-boilerplate' )
        );
    }

    /**
     * Register gateway settings. 
     *
     * @param   array[] $settings
     * @return  array[]
     * @access  public
     * @since   1.0.0
     */
    public function gateway_settings( $settings ) {
        $settings[ 'test_secret_key' ] = array(
            'type'      => 'text', 
            'title'     => __( 'Test Secret Key', 'charitable-extension-boilerplate' ), 
            'priority'  => 6, 
            'class'     => 'wide'
        );

        $settings[ 'test_public_key' ] = array(
            'type'      => 'text', 
            'title'     => __( 'Test Publishable Key', 'charitable-extension-boilerplate' ), 
            'priority'  => 8,
            'class'     => 'wide'
        );

        return $settings;   
    }

    /**
     * Returns the current gateway's ID.  
     *
     * @return  string
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function get_gateway_id() {
        return self::ID;
    }

    /**
     * Return the keys to use. 
     *
     * This will return the test keys if test mode is enabled. Otherwise, returns
     * the production keys. 
     *
     * @return  string[]    
     * @access  public
     * @since   1.0.0
     */
    public function get_keys() {
        $keys = array();

        if ( charitable_get_option( 'test_mode' ) ) {
            $keys[ 'secret_key' ] = trim( $this->get_value( 'test_secret_key' ) );
            $keys[ 'public_key' ] = trim( $this->get_value( 'test_public_key' ) );
        }
        else {
            $keys[ 'secret_key' ] = trim( $this->get_value( 'live_secret_key' ) );
            $keys[ 'public_key' ] = trim( $this->get_value( 'live_public_key' ) );
        }

        return $keys;
    }

    /**
     * Return the submitted value for a gateway field. 
     *
     * @param   string $key 
     * @param   mixed[] $values
     * @return  string|false
     * @access  public
     * @since   1.0.0
     */
    public function get_gateway_value( $key, $values ) {
        return isset( $values[ 'gateways' ][ 'gateway_id' ][ $key ] ) ? $values[ 'gateways' ][ 'gateway_id' ][ $key ] : false;
    }

    /**
     * Return the submitted value for a gateway field. 
     *
     * @param   string $key 
     * @param   Charitable_Donation_Processor $processor
     * @return  string|false
     * @access  public
     * @since   1.0.0
     */
    public function get_gateway_value_from_processor( $key, Charitable_Donation_Processor $processor ) {
        return $this->get_gateway_value( $key, $processor->get_donation_data() );
    }

    /**
     * Validate the submitted credit card details.  
     *
     * @param   boolean $valid
     * @param   string $gateway
     * @param   mixed[] $values
     * @return  boolean
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function validate_donation( $valid, $gateway, $values ) {
        if ( $gateway != 'gateway_id' ) {
            return $valid;
        }

        if ( ! isset( $values[ 'gateways' ][ 'gateway_id' ] ) ) {
            return false;
        }

        /**
         * @todo Check that the donation is valid.
         */        
        
        return $valid;
    }

    /**
     * Process the donation with the gateway.
     *   
     * @param   int $donation_id
     * @param   Charitable_Donation_Processor $processor
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_donation( $donation_id, $processor ) {
        $donation = new Charitable_Donation( $donation_id );
        $donor = $donation->get_donor();

        /**
         * @todo Create donation charge through gateway.
         */
    }

    /**
     * Process an IPN request.  
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_ipn() {
        /** 
         * @todo Process IPN. 
         */
    }

    /**
     * Redirect back to the donation form, sending the donation ID back.
     *
     * @param   int $donation_id
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function redirect_to_donation_form( $donation_id ) {
        charitable_get_session()->add_notices();
        $redirect_url = esc_url( add_query_arg( array( 'donation_id' => $donation_id ), wp_get_referer() ) );
        wp_safe_redirect( $redirect_url );
        die();
    }
}

endif; // End class_exists check