<?php
// Plugin Name:  MTP Tabs Widget
// Plugin URI:   http://mt-performance.net
// Description:  Tabs for the sidebar. "Recent Posts", "Tags", "Popular Posts"
// Version:      2.0
// Author:       Toni Medina
// Author URI:   http://tonimedina.me
// Author Email: contact@tonimedina.me
// Text Domain:  mtp_plugin
// Domain Path:  /lang/
// Network:      false
// License:      GPLv2 or later
// License URI:  http://www.gnu.org/licenses/gpl-2.0.html

// Copyright 2012 TODO (email@domain.com)

// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License, version 2, as 
// published by the Free Software Foundation.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

/**
 * MTP Tabs Widget
 */
class MTP_Tabs_Widget extends WP_Widget
{
    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

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

        // TODO:    update classname and description
        parent::__construct(
            'mtp-tabs-widget-id',
            __( 'MTP Tabs Widget', 'mtp_plugin' ),
            array(
                'classname'   => 'mtp-tabs-widget-class',
                'description' => __( 'Tabs with the most recent posts, the tags, and the most popular posts.', 'mtp_plugin' )
            )
        );

        // Register admin styles and scripts
        add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

        // Register site styles and scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
    } // end __construct

    /*--------------------------------------------------*/
    /* Widget API Functions
    /*--------------------------------------------------*/

    /**
     * Outputs the content of the widget.
     *
     * @param   array   args        The array of form elements
     * @param   array   instance    The current instance of the widget
     */
    public function widget( $args, $instance )
    {
        extract( $args, EXTR_SKIP );

        $cache = wp_cache_get( 'mtp-tabs-widget-class', 'widget' );

        if ( !is_array( $cache ) )
            $cache = array();

        if ( !isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[$args['widget_id']] ) )
        {
            echo $cache[$args['widget_id']];

            return;
        }

        ob_start();
        extract( $args );

        $title                  = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $recent_title           = apply_filters( 'widget_title', empty( $instance['recent_title'] ) ? __( 'Recent' ) : $instance['recent_title'], $instance, $this->id_base );
        if ( empty( $instance['recent_number'] ) || !$recent_number = absint( $instance['recent_number'] ) ) : $recent_number = 5; endif;
        $recent_show_thumbnail  = isset( $instance['recent_show_thumbnail'] ) ? $instance['recent_show_thumbnail'] : false;
        $recent_show_date       = isset( $instance['recent_show_date'] ) ? $instance['recent_show_date'] : false;
        $tags_title             = apply_filters( 'widget_title', empty( $instance['tags_title'] ) ? __( 'Tags' ) : $instance['tags_title'], $instance, $this->id_base );
        $current_taxonomy       = $this->get_current_taxonomy( $instance );
        $popular_title          = apply_filters( 'widget_title', empty( $instance['popular_title'] ) ? __( 'popular' ) : $instance['popular_title'], $instance, $this->id_base );
        if ( empty( $instance['popular_number'] ) || !$popular_number = absint( $instance['popular_number'] ) ) : $popular_number = 5; endif;
        $popular_show_thumbnail = isset( $instance['popular_show_thumbnail'] ) ? $instance['popular_show_thumbnail'] : false;
        $popular_show_date      = isset( $instance['popular_show_date'] ) ? $instance['popular_show_date'] : false;

        echo $before_widget;

        if ( $title ) : echo $before_title . $title . $after_title; endif;

        // TODO:    Here is where you manipulate your widget's values based on their input fields

        include( plugin_dir_path( __FILE__ ) . '/views/widget.php' );

        echo $after_widget;

        $cache[$args['widget_id']] = ob_get_flush();

        wp_cache_set( 'mtp-tabs-widget-class', $cache, 'widget' );
    } // end widget

    /**
     * Processes the widget's options to be saved.
     *
     * @param   array   new_instance    The previous instance of values before the update.
     * @param   array   old_instance    The new instance of values to be generated via the update.
     */
    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        // TODO:    Here is where you update your widget's old values with the new, incoming values

        $instance['title']                  = strip_tags( stripslashes( $new_instance['title'] ) );
        $instance['recent_title']           = strip_tags( stripslashes( $new_instance['recent_title'] ) );
        $instance['recent_number']          = (int) $new_instance['recent_number'];
        $instance['recent_show_thumbnail']  = (bool) $new_instance['recent_show_thumbnail'];
        $instance['recent_show_date']       = (bool) $new_instance['recent_show_date'];
        $instance['tags_title']             = strip_tags( stripslashes( $new_instance['tags_title'] ) );
        $instance['tags_taxonomy']          = stripslashes( $new_instance['tags_taxonomy'] );
        $instance['popular_title']          = strip_tags( stripslashes( $new_instance['popular_title'] ) );
        $instance['popular_number']         = (int) $new_instance['popular_number'];
        $instance['popular_show_thumbnail'] = (bool) $new_instance['popular_show_thumbnail'];
        $instance['popular_show_date']      = (bool) $new_instance['popular_show_date'];

        return $instance;
    } // end widget

    /**
     * Generates the administration form for the widget.
     *
     * @param   array   instance    The array of keys and values for the widget.
     */
    public function form( $instance )
    {
        // TODO:    Define default values for your variables
        $instance = wp_parse_args(
            (array) $instance
        );

        // TODO:    Store the values of the widget in their own variable
        
        $title                  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $recent_title           = isset( $instance['recent_title'] ) ? esc_attr( $instance['recent_title'] ) : __( 'Recent' );
        $recent_number          = isset( $instance['recent_number'] ) ? absint( $instance['recent_number'] ) : 5;
        $recent_show_date       = isset( $instance['recent_show_date'] ) ? (bool) $instance['recent_show_date'] : false;
        $recent_show_thumbnail  = isset( $instance['recent_show_thumbnail'] ) ? (bool) $instance['recent_show_thumbnail'] : false;
        $tags_title             = isset( $instance['tags_title'] ) ? esc_attr( $instance['tags_title'] ) : __( 'Tags' );
        $current_taxonomy       = $this->get_current_taxonomy( $instance );
        $popular_title          = isset( $instance['popular_title'] ) ? esc_attr( $instance['popular_title'] ) : __( 'Popular' );
        $popular_number         = isset( $instance['popular_number'] ) ? absint( $instance['popular_number'] ) : 5;
        $popular_show_date      = isset( $instance['popular_show_date'] ) ? (bool) $instance['popular_show_date'] : false;
        $popular_show_thumbnail = isset( $instance['popular_show_thumbnail'] ) ? (bool) $instance['popular_show_thumbnail'] : false;

        // Display the admin form
        include( plugin_dir_path(__FILE__) . '/views/admin.php' );  
    } // end form

    /*--------------------------------------------------*/
    /* Public Functions
    /*--------------------------------------------------*/

    /**
     * Loads the Widget's text domain for localization and translation.
     */
    public function widget_textdomain()
    {
        // TODO be sure to change 'mtp-tabs' to the name of *your* plugin
        load_plugin_textdomain( 'mtp_plugin', false, plugin_dir_path( __FILE__ ) . '/lang/' );
    } // end widget_textdomain

    /**
     * Fired when the plugin is activated.
     *
     * @param       boolean $network_wide   True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public function activate( $network_wide )
    {
        // TODO define activation functionality here
    } // end activate

    /**
     * Fired when the plugin is deactivated.
     *
     * @param   boolean $network_wide   True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
     */
    public function deactivate( $network_wide )
    {
        // TODO define deactivation functionality here      
    } // end deactivate

    /**
     * Registers and enqueues admin-specific styles.
     */
    public function register_admin_styles()
    {
        // TODO:    Change 'mtp-tabs' to the name of your plugin
        wp_enqueue_style( 'mtp-tabs-admin-styles', plugins_url( 'mtp-tabs/widget/css/admin.css' ) );
    } // end register_admin_styles

    /**
     * Registers and enqueues admin-specific JavaScript.
     */ 
    public function register_admin_scripts()
    {
        // TODO:    Change 'mtp-tabs' to the name of your plugin
        wp_enqueue_script( 'mtp-tabs-admin-script', plugins_url( 'mtp-tabs/widget/js/admin.js' ) );  
    } // end register_admin_scripts

    /**
     * Registers and enqueues widget-specific styles.
     */
    public function register_widget_styles()
    {
        // TODO:    Change 'mtp-tabs' to the name of your plugin
        wp_enqueue_style( 'mtp-tabs-widget-styles', plugins_url( 'mtp-tabs/widget/css/widget.css' ) );
    } // end register_widget_styles

    /**
     * Registers and enqueues widget-specific scripts.
     */
    public function register_widget_scripts()
    {
        // TODO:    Change 'mtp-tabs' to the name of your plugin
        wp_enqueue_script( 'mtp-tabs-script', plugins_url( 'mtp-tabs/widget/js/widget.js' ) );
    } // end register_widget_scripts

    /**
     * Get the current taxonomy
     * 
     * @param array
     * @return string
     */
    public function get_current_taxonomy( $instance )
    {
        if ( !empty( $instance['tags_taxonomy'] ) && taxonomy_exists( $instance['tags_taxonomy'] ) )
            return $instance['tags_taxonomy'];

        return 'post_tag';
    } // end get_current_taxonomy
} // end MTP_Tabs_Widget

add_action( 'widgets_init', create_function( '', 'register_widget("MTP_Tabs_Widget");' ) );