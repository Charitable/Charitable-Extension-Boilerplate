<?php
/**
 * The class responsible for adding & saving extra settings in the Charitable admin.
 *
 * @package     Charitable Extension Boilerplate/Classes/Charitable_Extension_Boilerplate_Admin
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2015, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
     * Add the raffles settings to the General settings tab.
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
                'priority'          => 50
            ),
            'extension_boilerplate_setting_text' => array(
                'title'             => __( 'Text Field Setting', 'charitable-extension-boilerplate' ), 
                'type'              => 'text',
                'priority'          => 50.2,
                'default'           => __( '', 'charitable-extension-boilerplate' )
            ),
            'extension_boilerplate_setting_checkbox' => array(
                'title'             => __( 'Checkbox Setting', 'charitable-extension-boilerplate' ),
                'type'              => 'checkbox',
                'priority'          => 50.6,
                'default'           => false,
                'help'              => __( '', 'charitable-extension-boilerplate' )
            ),             
        );

        $fields = array_merge( $fields, $custom_fields );

        return $fields;
    }   
}

endif; // End class_exists check