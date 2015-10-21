<?php
/**
 * The main Charitable Extension Boilerplate class.
 * 
 * The responsibility of this class is to load all the plugin's functionality.
 *
 * @package     Charitable Extension Boilerplate
 * @copyright   Copyright (c) 2015, Eric Daams  
 * @license     http://opensource.org/licenses/gpl-1.0.0.php GNU Public License
 * @since       0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Extension_Boilerplate' ) ) :

/**
 * Charitable_Extension_Boilerplate
 *
 * @since   0.1
 */
class Charitable_Extension_Boilerplate {

    /**
     * @var string
     */
    const VERSION = '0.1';

    /**
     * @var string  A date in the format: YYYYMMDD
     */
    const DB_VERSION = '20151021';  

    /**
     * @var string The product name. 
     */
    const NAME = 'Charitable Extension Boilerplate'; 

    /**
     * @var string The product author.
     */
    const AUTHOR = 'Studio 164a';

    /**
     * @var Charitable_Extension_Boilerplate
     */
    private static $instance = null;

    /**
     * The root file of the plugin. 
     * 
     * @var     string
     * @access  private
     */
    private $plugin_file; 

    /**
     * The root directory of the plugin.  
     *
     * @var     string
     * @access  private
     */
    private $directory_path;

    /**
     * The root directory of the plugin as a URL.  
     *
     * @var     string
     * @access  private
     */
    private $directory_url;

    /**
     * @var     array       Store of registered objects.  
     * @access  private
     */
    private $registry;

    /**
     * Create class instance. 
     * 
     * @return  void
     * @since   1.0.0
     */
    public function __construct( $plugin_file ) {
        $this->plugin_file      = $plugin_file;
        $this->directory_path   = plugin_dir_path( $plugin_file );
        $this->directory_url    = plugin_dir_url( $plugin_file );

        add_action( 'charitable_start', array( $this, 'start' ), 6 );
    }

    /**
     * Returns the original instance of this class. 
     * 
     * @return  Charitable
     * @since   1.0.0
     */
    public static function get_instance() {
        return self::$instance;
    }

    /**
     * Run the startup sequence on the charitable_start hook. 
     *
     * This is only ever executed once.  
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function start() {
        // If we've already started (i.e. run this function once before), do not pass go. 
        if ( $this->started() ) {
            return;
        }

        // Set static instance
        self::$instance = $this;

        $this->load_dependencies();

        $this->maybe_upgrade();

        $this->setup_licensing();

        // Hook in here to do something when the plugin is first loaded.
        do_action('charitable_extension_boilerplate_start', $this);
    }

    /**
     * Include necessary files.
     * 
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function load_dependencies() {      
        require_once( $this->get_path( 'includes' ) . 'charitable-extension-boilerplate-core-functions.php' );
        require_once( $this->get_path( 'includes' ) . 'class-charitable-extension-boilerplate-template.php' );        
    }

    /**
     * Set up licensing for the extension. 
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function setup_licensing() {
        charitable_get_helper( 'licenses' )->register_licensed_product( 
            Charitable_Extension_Boilerplate::NAME, 
            Charitable_Extension_Boilerplate::AUTHOR, 
            Charitable_Extension_Boilerplate::VERSION,
            $this->plugin_file 
        );
    }

    /**
     * Perform upgrade routine if necessary. 
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function maybe_upgrade() {
        $db_version = get_option( 'charitable_extension_boilerplate_version' );

        if ( $db_version !== self::VERSION ) {

            require_once( charitable()->get_path( 'includes' ) . 'class-charitable-upgrade.php' );
            require_once( $this->get_path( 'includes' ) . 'class-charitable-extension-boilerplate-upgrade.php' );

            Charitable_Extension_Boilerplate_Upgrade::upgrade_from( $db_version, self::VERSION );
        }
    }

    /**
     * Returns whether we are currently in the start phase of the plugin. 
     *
     * @return  bool
     * @access  public
     * @since   1.0.0
     */
    public function is_start() {
        return current_filter() == 'charitable_extension_boilerplate_start';
    }

    /**
     * Returns whether the plugin has already started.
     * 
     * @return  bool
     * @access  public
     * @since   1.0.0
     */
    public function started() {
        return did_action( 'charitable_extension_boilerplate_start' ) || current_filter() == 'charitable_extension_boilerplate_start';
    }

    /**
     * Returns the plugin's version number. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_version() {
        return self::VERSION;
    }

    /**
     * Returns plugin paths. 
     *
     * @param   string $path            // If empty, returns the path to the plugin.
     * @param   bool $absolute_path     // If true, returns the file system path. If false, returns it as a URL.
     * @return  string
     * @since   1.0.0
     */
    public function get_path($type = '', $absolute_path = true ) {      
        $base = $absolute_path ? $this->directory_path : $this->directory_url;

        switch( $type ) {
            case 'includes' : 
                $path = $base . 'includes/';
                break;

            case 'templates' : 
                $path = $base . 'templates/';
                break;

            case 'directory' : 
                $path = $base;
                break;

            default :
                $path = $this->plugin_file;
        }

        return $path;
    }

    /**
     * Stores an object in the plugin's registry.
     *
     * @param   mixed       $object
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function register_object( $object ) {
        if ( ! is_object( $object ) ) {
            return;
        }

        $class = get_class( $object );

        $this->registry[ $class ] = $object;
    }

    /**
     * Returns a registered object.
     * 
     * @param   string      $class  The type of class you want to retrieve.
     * @return  mixed               The object if its registered. Otherwise false.
     * @access  public
     * @since   1.0.0
     */
    public function get_object( $class ) {
        return isset( $this->registry[ $class ] ) ? $this->registry[ $class ] : false;
    }

    /**
     * Throw error on object clone. 
     *
     * This class is specifically designed to be instantiated once. You can retrieve the instance using charitable()
     *
     * @since   1.0.0
     * @access  public
     * @return  void
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable-extension-boilerplate' ), '1.0.0' );
    }

    /**
     * Disable unserializing of the class. 
     *
     * @since   1.0.0
     * @access  public
     * @return  void
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable-extension-boilerplate' ), '1.0.0' );
    }           
}

endif; // End if class_exists check