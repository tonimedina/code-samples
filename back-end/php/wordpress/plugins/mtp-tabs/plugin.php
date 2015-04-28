<?php
/*
Plugin Name:  MTP Tabs
Plugin URI:   http://mt-performance.net
Description:  Tabs for the sidebar. "Recent Posts", "Tags", "Popular Posts"
Version:      2.0.0
Author:       Toni Medina
Author URI:   http://tonimedina.me
Author Email: contact@tonimedina.me
License:

  Copyright 2013 TODO (email@domain.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * MTP Tabs
 */
class MTP_Tabs
{
    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    function __construct()
    {
        // Load plugin text domain
        add_action( 'init', array( $this, 'plugin_textdomain' ) );

        // Register admin styles and scripts
        add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

        // Register site styles and scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

        // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
        register_uninstall_hook( __FILE__, 'mtp_tabs_uninstall' );

        /*
         * TODO:
         * Define the custom functionality for your plugin. The first parameter of the
         * add_action/add_filter calls are the hooks into which your code should fire.
         *
         * The second parameter is the function name located within this class. See the stubs
         * later in the file.
         *
         * For more information: 
         * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
         */
        add_action( 'init', array( $this, 'actions' ) );
        add_filter( 'init', array( $this, 'filters' ) );
        add_action( 'init', array( $this, 'widgets_init' ), 1 );
    } // end __construct

    /**
     * Fired when the plugin is activated.
     *
     * @param   boolean $network_wide   True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
     */
    public function activate( $network_wide )
    {
        // TODO:    Define activation functionality here
    } // end activate

    /**
     * Fired when the plugin is deactivated.
     *
     * @param   boolean $network_wide   True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
     */
    public function deactivate( $network_wide )
    {
        // TODO:    Define deactivation functionality here
    } // end deactivate

    /**
     * Loads the plugin text domain for translation
     */
    public function plugin_textdomain()
    {
        // TODO: replace "mtp-tabs-locale" with a unique value for your plugin
        $domain = 'mtp-tabs-locale';
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR .'/'. $domain .'/'. $domain .'-'. $locale .'.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
    } // end plugin_textdomain

    /**
     * Registers and enqueues admin-specific styles.
     */
    public function register_admin_styles()
    {
        wp_enqueue_style( 'mtp-tabs-admin-styles', plugins_url( 'mtp-tabs/css/admin.css' ) );
    } // end register_admin_styles

    /**
     * Registers and enqueues admin-specific JavaScript.
     */
    public function register_admin_scripts()
    {
        wp_enqueue_script( 'mtp-tabs-admin-script', plugins_url( 'mtp-tabs/js/admin.js' ) );
    } // end register_admin_scripts

    /**
     * Registers and enqueues plugin-specific styles.
     */
    public function register_plugin_styles()
    {
        wp_enqueue_style( 'mtp-tabs-plugin-styles', plugins_url( 'mtp-tabs/css/display.css' ) );
    } // end register_plugin_styles

    /**
     * Registers and enqueues plugin-specific scripts.
     */
    public function register_plugin_scripts()
    {
        if ( !is_admin() )
        {
            wp_deregister_script( 'jquery' );
            wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', array(), false, false );
            wp_enqueue_script( 'jquery' );
        }

        wp_register_script( 'jquery-ui', get_bloginfo( 'url' ) . '/wp-content/plugins/mtp-tabs/js/vendor/jquery-ui.min.js', array( 'jquery' ), false, false );
        wp_enqueue_script( 'jquery-ui' );

        wp_enqueue_script( 'mtp-tabs-plugin-script', plugins_url( 'mtp-tabs/js/display.js' ) );
    } // end register_plugin_scripts

    /*--------------------------------------------*
     * Core Functions
     *---------------------------------------------*/

    /**
     * NOTE:  Actions are points in the execution of a page or process
     *        lifecycle that WordPress fires.
     *
     *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
     *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
     *
     */
    function actions()
    {
        // TODO:    Define your action method here

        // Action for adding new columns
        add_action( 'manage_posts_custom_column', array( $this, 'posts_custom_columns' ), 1, 2 );
        add_action( 'wp_head', array( $this, 'set_post_stats' ), 1000 );
    } // end actions

    /**
     * NOTE:  Filters are points of execution in which WordPress modifies data
     *        before saving it or sending it to the browser.
     *
     *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
     *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
     *
     */
    function filters()
    {
        // TODO:    Define your filter method here
        
        // Filter defining new columns
        add_filter( 'manage_posts_columns', array( $this, 'posts_columns' ) );
    } // end filters

    /**
     * Populate the new column with the Visits
     * 
     * @param string
     * @param int
     * @return int
     */
    public function posts_custom_columns( $column_name, $id )
    {
        if ( $column_name === 'post_stats' )
        {
            // Get visit count from database
            $current_stats = get_post_meta( $id, 'post_stats', true );

            echo (int) $current_stats;
        }
    } // end posts_custom_columns

    /**
     * Set the post view stats
     */
    public function set_post_stats()
    {
        // Get current post id
        $post_id = get_the_ID();

        // Is it post? Otherwise, we do not need to track visits
        if ( is_single( $post_id ) )
        {
            // Get current visit stats for the post
            $current_stats = get_post_meta( $post_id, 'post_stats', true );

            // This is first visit to this post
            if ( !isset( $current_stats ) )
            {
                // Add first visit to database
                add_post_meta( $post_id, 'post_stats', 1, true );
            }
            else
            {
                // Increment number of visits
                update_post_meta( $post_id, 'post_stats', $current_stats + 1 );
            }
        }
    } // end set_post_stats

    /**
     * Create a new column called 'Visits'
     * 
     * @param array
     * @return array
     */
    public function posts_columns( $defaults )
    {
        $defaults['post_stats'] = __( 'Visits', 'mtp-tabs-locale' );

        return $defaults;
    } // end posts_columns

    /**
     * Widgets initialization
     * 
     * @return void
     */
    public function widgets_init()
    {
        if ( !is_blog_installed() )
            return;

        include( plugin_dir_path( __FILE__ ) . '/widget/plugin.php' );

        do_action( 'widgets_init' );
    } // end widgets_init
}

/**
 * Fired when the plugin is uninstalled.
 *
 * @param   boolean $network_wide   True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
 */
function mtp_tabs_uninstall( $network_wide )
{
    // TODO:    Define uninstall functionality here
} // end uninstall

$mtp_tabs = new MTP_Tabs();