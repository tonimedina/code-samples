<?php
/**
 * MTP Ads Widget
 */
class MTP_Ads_Widget extends WP_Widget
{
  /* ------------------------------ *
   * Constructor
   * ------------------------------ */

  /**
   * Specifies the classname and description, instantiates the widget, 
   * loads localization files, and includes necessary stylesheets and JavaScript.
   */
  public function __construct()
  {
    // load plugin text domain
    add_action( 'init', array( $this, 'widget_textdomain' ) );

    // Hooks fired when the Widget is activated and deactivated
    register_activation_hook( __FILE__, array( $this, 'activate' ) );
    register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

    parent::__construct(
      'mtp-ads-widget-id',
      __( 'MTP Ads Widget', 'mtp_plugin' ),
      array(
        'classname'   => 'mtp-ads-widget-class',
        'description' => __( 'Short description of the widget goes here.', 'mtp_plugin' )
      )
    );
    
    // Register admin styles and scripts
    // add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) ); // NOT NEEDED FOR NOW!!!
    // add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) ); // NOT NEEDED FOR NOW!!!
  
    // Register site styles and scripts
    // add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) ); // NOT NEEDED FOR NOW!!!
    // add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) ); // NOT NEEDED FOR NOW!!!
  } // END constructor

  /* ------------------------------ *
   * Widget API Functions
   * ------------------------------ */

  /**
   * Outputs the content of the widget.
   * @param  array $args
   * @param  array $instance
   * @return string
   */
  public function widget( $args, $instance )
  {
    extract( $args, EXTR_SKIP );
    
    echo $before_widget;

    include( plugin_dir_path( __FILE__ ) . '/views/widget.php' );

    echo $after_widget;
  } // END widget

  /**
   * Processes the widget's options to be saved.
   * @param  array $new_instance
   * @param  array $old_instance
   * @return array
   */
  public function update( $new_instance, $old_instance )
  {
    $instance           = $old_instance;
    $instance['title']  = strip_tags( $new_instance['title'] );
    $instance['mtp_ad'] = stripslashes( $new_instance['mtp_ad'] );
    
    return $instance;
  } // END widget

  /**
   * Generates the administration form for the widget.
   * @param  array $instance
   * @return string
   */
  public function form( $instance )
  {
    $instance = wp_parse_args( (array) $instance, array(
      'title'  => '',
      'mtp_ad' => ''
    ) );
    
    // Display the admin form
    include( plugin_dir_path(__FILE__) . '/views/admin.php' );  
  } // END form

  /* ------------------------------ *
   * Public Functions
   * ------------------------------ */

  /**
   * Loads the Widget's text domain for localization and translation.
   * @return void
   */
  public function widget_textdomain()
  {
    load_plugin_textdomain( 'mtp_plugin', false, plugin_dir_path( __FILE__ ) . '/lang/' ); 
  } // END widget_textdomain
  
  /**
   * Fired when the plugin is activated.
   *
   * @param   boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
   */
  public function activate( $network_wide )
  {
    // TODO define activation functionality here
  } // END activate
  
  /**
   * Fired when the plugin is deactivated.
   *
   * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
   */
  public function deactivate( $network_wide )
  {
    // TODO define deactivation functionality here    
  } // END deactivate

  /**
   * Registers and enqueues admin-specific styles.
   * @return void
   */
  public function register_admin_styles()
  {
    wp_enqueue_style( 'mtp-ads-admin-styles', plugins_url( 'mtp-ads/widget/css/admin.css' ) );
  } // END register_admin_styles

  /**
   * Registers and enqueues admin-specific JavaScript.
   * @return void
   */
  public function register_admin_scripts()
  {
    wp_enqueue_script( 'mtp-ads-admin-script', plugins_url( 'mtp-ads/widget/js/admin.js' ) );
  } // END register_admin_scripts

  /**
   * Registers and enqueues widget-specific styles.
   * @return void
   */
  public function register_widget_styles()
  {
    wp_enqueue_style( 'mtp-ads-widget-styles', plugins_url( 'mtp-ads/widget/css/widget.css' ) );
  } // END register_widget_styles

  /**
   * Registers and enqueues widget-specific scripts.
   * @return void
   */
  public function register_widget_scripts()
  {
    wp_enqueue_script( 'mtp-ads-script', plugins_url( 'mtp-ads/widget/js/widget.js' ) );
  } // END register_widget_scripts
} // END class MTP_Ads_Widget

add_action( 'widgets_init', create_function( '', 'register_widget("MTP_Ads_Widget");' ) ); 