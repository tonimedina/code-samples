<?php
/*
Plugin Name:  MTP Ads
Plugin URI:   http://mt-performance.net/
Description:  Creates a custom post type and a widget to for the ads. Uses wordpress actions to place the ads in different positions.
Version:      3.0.0
Author:       Toni Medina
Author URI:   http://tonimedina.me/
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
 * MTP Ads
 */
final class MTP_Ads
{
  /* ------------------------------ *
   * Constructor
   * ------------------------------ */

  /**
   * Initializes the plugin by setting localization, filters, and administration functions.
   */
  public function __construct()
  {
    // Load plugin text domain
    add_action( 'init', array( $this, 'plugin_textdomain' ) );

    // Register admin styles and scripts
    // add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) ); // NOT NEEDED FOR NOW!!!
    // add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) ); // NOT NEEDED FOR NOW!!!

    // Register site styles and scripts
    // add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) ); // NOT NEEDED FOR NOW!!!
    // add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) ); // NOT NEEDED FOR NOW!!!

    // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
    register_activation_hook( __FILE__, array( $this, 'activate' ) );
    register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
    // register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );
    
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
    // add_action( 'TODO', array( $this, 'action_method_name' ) ); // NOT NEEDED FOR NOW!!!
    // add_filter( 'TODO', array( $this, 'filter_method_name' ) ); // NOT NEEDED FOR NOW!!!

    add_action( 'init', array( $this, 'register_cpt_mtp_ad' ) );
    add_action( 'add_meta_boxes', array( $this, 'add_custom_box' ) );
    add_action( 'save_post', array( $this, 'add_custom_box_save_postdata' ) );

    add_action( 'add_meta_boxes', array( $this, 'add_singular_custom_box' ) );
    add_action( 'save_post', array( $this, 'add_singular_custom_box_save_postdata' ) );

    // The hooks
    /* add_action( 'thesis_hook_before_html', array( $this, 'query_before_html_ads' ) );
    add_action( 'thesis_hook_before_title', array( $this, 'query_before_title_ads' ) );
    add_action( 'thesis_hook_after_title', array( $this, 'query_after_title_ads' ) );
    add_action( 'thesis_hook_byline_item', array( $this, 'query_byline_item_ads' ) );
    add_action( 'thesis_hook_before_content', array( $this, 'query_before_content_ads' ) );
    add_action( 'thesis_hook_before_sidebars', array( $this, 'query_before_sidebars_ads' ) );
    add_action( 'thesis_hook_after_post', array( $this, 'query_after_post_ads' ) );
    add_action( 'thesis_hook_before_footer', array( $this, 'query_before_footer_ads' ) );
    add_action( 'thesis_hook_between_paragraphs', array( $this, 'query_between_paragraphs_ads' ) );
    add_action( 'thesis_hook_after_post_box', array( $this, 'query_after_post_box_ads' ) );
    add_action( 'thesis_hook_after_teasers_box', array( $this, 'query_after_teasers_box_ads' ) ); */

    add_action( 'thesis_hook_before_html', array( $this, 'query_before_html_ads' ) );
    add_action( 'thesis_hook_container_header_container_top', array( $this, 'query_before_title_ads' ) );
    add_action( 'thesis_hook_before_container_navigation', array( $this, 'query_after_title_ads' ) );
    add_action( 'thesis_hook_container_sidebar_top', array( $this, 'query_before_sidebars_ads' ) );
    add_action( 'thesis_hook_container_footer_container_top', array( $this, 'query_before_footer_ads' ) ); 

    if ( ! is_admin() ) :
        add_action( 'thesis_hook_after_container_entry_header', array( $this, 'query_byline_item_ads' ) );
        add_action( 'thesis_hook_container_content_container_top', array( $this, 'query_before_content_ads' ) );
        add_action( 'thesis_hook_container_entry_footer_top', array( $this, 'query_after_post_ads' ) );
        add_filter( 'the_content', array( $this, 'query_between_paragraphs_ads' ) );
        add_action( 'thesis_hook_after_post_box', array( $this, 'query_after_post_box_ads' ) ); // NOT WORKING FOR NOW!!! :(
        add_action( 'thesis_hook_after_post_box_teaser_box', array( $this, 'query_after_teasers_box_ads' ) );
    endif;

    add_action( 'save_post', array( $this, 'save_ad_into_singular' ) );
    add_action( 'delete_post', array( $this, 'delete_ad_into_singular' ) );

    add_filter( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ) );
  } // END constructor

  /**
   * Fired when the plugin is activated.
   *
   * @param   boolean $network_wide   True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
   */
  public function activate( $network_wide )
  {
    // TODO:    Define activation functionality here
  } // END activate

  /**
   * Fired when the plugin is deactivated.
   *
   * @param   boolean $network_wide   True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
   */
  public function deactivate( $network_wide )
  {
    // TODO:    Define deactivation functionality here      
  } // END deactivate

  /**
   * Fired when the plugin is uninstalled.
   *
   * @param   boolean $network_wide   True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
   */
  public function uninstall( $network_wide )
  {
    // TODO:    Define uninstall functionality here     
  } // END uninstall

  /**
   * Loads the plugin text domain for translation
   */
  public function plugin_textdomain()
  {
    // TODO: replace "plugin-name-locale" with a unique value for your plugin
    $domain = 'plugin-name-locale';
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

    load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
    load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
  } // END plugin_textdomain

  /**
   * Registers and enqueues admin-specific styles.
   * @return void
   */
  public function register_admin_styles()
  {
    wp_enqueue_style( 'mtp-ads-admin-styles', plugins_url( 'mtp-ads/css/admin.css' ) );
  } // END register_admin_styles

  /**
   * Registers and enqueues admin-specific JavaScript.
   * @return void
   */
  public function register_admin_scripts()
  {
    wp_enqueue_script( 'mtp-ads-admin-script', plugins_url( 'mtp-ads/js/admin.js' ) );
  } // END register_admin_scripts

  /**
   * Registers and enqueues plugin-specific styles.
   * @return void
   */
  public function register_plugin_styles()
  {
    wp_enqueue_style( 'mtp-ads-plugin-styles', plugins_url( 'mtp-ads/css/display.css' ) );
  } // END register_plugin_styles

  /**
   * Registers and enqueues plugin-specific scripts.
   * @return void
   */
  public function register_plugin_scripts()
  {
    wp_enqueue_script( 'mtp-ads-plugin-script', plugins_url( 'mtp-ads/js/display.js' ) );
  } // END register_plugin_scripts

  /* ------------------------------ *
   * Core Functions
   * ------------------------------ */

  /**
   * NOTE:  Actions are points in the execution of a page or process
   *        lifecycle that WordPress fires.
   *
   *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
   *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
   *
   */
  function action_method_name()
  {
    // TODO:    Define your action method here
  } // END action_method_name

  /**
   * NOTE:  Filters are points of execution in which WordPress modifies data
   *        before saving it or sending it to the browser.
   *
   *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
   *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
   *
   */
  function filter_method_name()
  {
    // TODO:    Define your filter method here
  } // END filter_method_name

  /* ------------------------------ *
   * Post Type
   * ------------------------------ */

  /**
   * Registers the mtp_ads custom post type.
   * @return void
   */
  function register_cpt_mtp_ad()
  {
    register_post_type( 'mtp_ad', array(
      'labels'              => array(
        'name'               => _x( 'Ads', 'mtp_plugin' ),
        'singular_name'      => _x( 'Ad', 'mtp_plugin' ),
        'all_items'          => _x( 'All Ads', 'mtp_plugin' ),
        'add_new'            => _x( 'Add New', 'mtp_plugin' ),
        'add_new_item'       => _x( 'Add New Ad', 'mtp_plugin' ),
        'edit_item'          => _x( 'Edit Ad', 'mtp_plugin' ),
        'new_item'           => _x( 'New Ad', 'mtp_plugin' ),
        'view_item'          => _x( 'View Ad', 'mtp_plugin' ),
        'search_items'       => _x( 'Search Ads', 'mtp_plugin' ),
        'not_found'          => _x( 'No ads found', 'mtp_plugin' ),
        'not_found_in_trash' => _x( 'No ads found in Trash', 'mtp_plugin' ),
        'parent_item_colon'  => _x( 'Parent Ad:', 'mtp_plugin' ),
        'menu_name'          => _x( 'Ads', 'mtp_plugin' ),
      ),
      'hierarchical'        => false,
      'supports'            => array( 'title', 'author', 'revisions' ),
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'publicly_queryable'  => true,
      'exclude_from_search' => true,
      'has_archive'         => true,
      'query_var'           => true,
      'can_export'          => true,
      'rewrite'             => true,
      'capability_type'     => 'post'
    ) );
  } // END register_cpt_mtp_ad

  /**
   * Add the mtp_ad custom meta box to the mtp_ad custom post type.
   */
  public function add_custom_box()
  {
    add_meta_box( 'mtp_ad_meta_box', __( 'MTP Ad', 'mtp_plugin' ), array( $this, 'add_custom_box_cb' ), 'mtp_ad', 'advanced', 'default', null );
  } // END add_custom_box

  /**
   * Includes the view for the mtp_ad custom meta box in the mtp_ad custom post type.
   * @param obj $post
   */
  public function add_custom_box_cb( $post )
  {
    wp_nonce_field( plugin_basename( __FILE__ ), 'mtp_ads_noncename' );

    include( plugin_dir_path( __FILE__ ) . '/views/admin.php' );
  } // END add_custom_box_cb

  /**
   * Save the mtp_ad meta box data in the mtp_ad custom post type.
   * @param int $post_id
   */
  function add_custom_box_save_postdata( $post_id )
  {
    if ( $_POST['post_type'] == 'mtp_ad' )
    {
      if ( ! current_user_can( 'edit_page', $post_id ) )
        return;
    }
    else
    {
      if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
    }

    // CHECK THIS IN THE FUTURE!!!
    // if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    //     return $post_id;
    
    if ( ! isset( $_POST['mtp_ads_noncename'] ) || ! wp_verify_nonce( $_POST['mtp_ads_noncename'], plugin_basename( __FILE__ ) ) )
      return;

    $post_ID     = $_POST['post_ID'];
    $ad_code     = ( ! empty( $_POST['mtp_ad_code'] ) ) ? (string) addslashes( $_POST['mtp_ad_code'] ) : '';
    $ad_category = ( ! empty( $_POST['mtp_ad_category'] ) ) ? (bool) $_POST['mtp_ad_category'] : '';
    $ad_home     = ( ! empty( $_POST['mtp_ad_home'] ) ) ? (bool) $_POST['mtp_ad_home'] : '';
    $ad_page     = ( ! empty( $_POST['mtp_ad_page'] ) ) ? (bool) $_POST['mtp_ad_page'] : '';
    $ad_single   = ( ! empty( $_POST['mtp_ad_single'] ) ) ? (bool) $_POST['mtp_ad_single'] : '';
    $ad_tag      = ( ! empty( $_POST['mtp_ad_tag'] ) ) ? (bool) $_POST['mtp_ad_tag'] : '';
    $ad_position = ( ! empty( $_POST['mtp_ad_position'] ) ) ? (string) $_POST['mtp_ad_position'] : '';
    $ad_number   = ( ! empty( $_POST['mtp_ad_number'] ) ) ? (int) $_POST['mtp_ad_number'] : '';

    if ( $ad_code ) :
      add_post_meta( $post_ID, '_mtp_ad_code', $ad_code, true ) or update_post_meta( $post_ID, '_mtp_ad_code', $ad_code );
    else :
      delete_post_meta( $post_ID, '_mtp_ad_code', $ad_code );
    endif;

    if ( $ad_category ) :
      add_post_meta( $post_ID, '_mtp_ad_category', $ad_category, true ) or update_post_meta( $post_ID, '_mtp_ad_category', $ad_category );
    else :
      delete_post_meta( $post_ID, '_mtp_ad_category', $ad_category );
    endif;

    if ( $ad_home ) :
      add_post_meta( $post_ID, '_mtp_ad_home', $ad_home, true ) or update_post_meta( $post_ID, '_mtp_ad_home', $ad_home );
    else :
      delete_post_meta( $post_ID, '_mtp_ad_home', $ad_home );
    endif;        

    if ( $ad_page ) :
      add_post_meta( $post_ID, '_mtp_ad_page', $ad_page, true ) or update_post_meta( $post_ID, '_mtp_ad_page', $ad_page );
    else :
      delete_post_meta( $post_ID, '_mtp_ad_page', $ad_page );
    endif;

    if ( $ad_single ) :
      add_post_meta( $post_ID, '_mtp_ad_single', $ad_single, true ) or update_post_meta( $post_ID, '_mtp_ad_single', $ad_single );
    else :
      delete_post_meta( $post_ID, '_mtp_ad_single', $ad_single );
    endif;

    if ( $ad_tag ) :
      add_post_meta( $post_ID, '_mtp_ad_tag', $ad_tag, true ) or update_post_meta( $post_ID, '_mtp_ad_tag', $ad_tag );
    else :
      delete_post_meta( $post_ID, '_mtp_ad_tag', $ad_tag );
    endif;

    if ( $ad_position ) :
      add_post_meta( $post_ID, '_mtp_ad_position', $ad_position, true ) or update_post_meta( $post_ID, '_mtp_ad_position', $ad_position );
    else :
      delete_post_meta( $post_ID, '_mtp_ad_position', $ad_position );
    endif;

    if ( $ad_position == 'between_paragraphs' || $ad_position ==  'after_post_box' || $ad_position == 'after_teasers_box' ) :
      add_post_meta( $post_ID, '_mtp_ad_number', $ad_number, true ) or update_post_meta( $post_ID, '_mtp_ad_number', $ad_number );
    else :
      delete_post_meta( $post_ID, '_mtp_ad_number', $ad_number );
    endif;
  } // END add_custom_box_save_postdata

  /**
   * Query the ads with position of before_html
   * @return string
   */
  public function query_before_html_ads()
  {
    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'before_html',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    foreach ( $ads as $ad )
    {
      $post_ID  = $ad->ID;
      $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
      $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
      $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
      $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
      $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
      $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
      $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

      if ( $category && is_category() || $home && is_home() || $home && $visible && is_front_page() || $page && $visible && is_page() && ! is_front_page() || $single && $visible && is_single() || $tag && is_tag() )
      {
        echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
      }
    }
  } // END query_before_html_ads

  /**
   * Query the ads with position of before_title
   * @return string
   */
  public function query_before_title_ads()
  {
    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'before_title',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $post_ID  = $ad->ID;
        $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
        $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
        $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
        $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
        $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
        $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
        $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

        if ( $category && is_category() || $home && is_home() || $home && $visible && is_front_page() || $page && $visible && is_page() && ! is_front_page() || $single && $visible && is_single() || $tag && is_tag() )
        {
          echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
        }
      }
    }
  } // END query_before_title_ads

  /**
   * Query the ads with position of after_title
   * @return string
   */
  public function query_after_title_ads()
  {
    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'after_title',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $post_ID  = $ad->ID;
        $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
        $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
        $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
        $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
        $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
        $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
        $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

        if ( $category && is_category() || $home && is_home() || $home && $visible && is_front_page() || $page && $visible && is_page() && ! is_front_page() || $single && $visible && is_single() || $tag && is_tag() )
        {
          echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
        }
      }
    }
  } // END query_after_title_ads

  /**
   * Query the ads with position of byline_item
   * @return string
   */
  public function query_byline_item_ads()
  {
    if ( ! is_singular() )
      return;

    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'byline_item',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $post_ID  = $ad->ID;
        $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
        $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
        $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
        $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
        $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
        $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
        $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

        if ( $category && is_category() || $home && is_home() || $home && $visible && is_front_page() || $page && $visible && is_page() && ! is_front_page() || $single && $visible && is_single() || $tag && is_tag() )
        {
          echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
        }
      }
    }
  } // END query_byline_item_ads

  /**
   * Query the ads with position of before_content
   * @return string
   */
  public function query_before_content_ads()
  {
    if ( ! is_singular() && ! is_archive() )
      return;

    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'before_content',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    foreach ( $ads as $ad )
    {
      $post_ID  = $ad->ID;
      $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
      $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
      $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
      $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
      $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
      $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
      $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

      if ( $category && is_category() || $home && is_home() || $home && $visible && is_front_page() || $page && $visible && is_page() && ! is_front_page() || $single && $visible && is_single() || $tag && is_tag() )
      {
        echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
      }
    }
  } // END query_before_content_ads

  /**
   * Query the ads with position of before_sidebars
   * @return string
   */
  public function query_before_sidebars_ads()
  {
    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'before_sidebars',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $post_ID  = $ad->ID;
        $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
        $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
        $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
        $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
        $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
        $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
        $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

        if ( $category && is_category() || $home && is_home() || $home && $visible && is_front_page() || $page && $visible && is_page() && ! is_front_page() || $single && $visible && is_single() || $tag && is_tag() )
        {
          echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
        }
      }
    }
  } // END query_before_sidebars_ads

  /**
   * Query the ads with position of after_post
   * @return string
   */
  public function query_after_post_ads()
  {
    if ( ! is_singular() )
      return;

    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'after_post',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $post_ID  = $ad->ID;
        $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
        $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
        $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
        $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
        $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

        if ( $home && is_home() || $home && $visible && is_front_page() || $page && $visible && is_page() && ! is_front_page() || $single && $visible && is_single() )
        {
          echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
        }
      }
    }
  } // END query_after_post_ads

  /**
   * Query the ads with position of before_footer
   * @return string
   */
  public function query_before_footer_ads()
  {
    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'before_footer',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $post_ID  = $ad->ID;
        $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
        $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
        $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
        $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
        $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
        $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
        $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

        if ( $category && is_category() || $home && is_home() || $home && $visible && is_front_page() || $page && $visible && is_page() && ! is_front_page() || $single && $visible && is_single() || $tag && is_tag() )
        {
          echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
        }
      }
    }
  } // END query_before_footer_ads

  /**
   * Query the ads with position of between_paragraphs
   * @return string
   */
  public function query_between_paragraphs_ads( $content )
  {
    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'between_paragraphs',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $post_ID  = $ad->ID;
        $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
        $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
        $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
        $number   = get_post_meta( $post_ID, '_mtp_ad_number', true );
        $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

        if ( $page && $visible && is_page() && ! is_front_page() || $single && $visible && is_single() )
        {
          $content           = explode( '</p>', $content );
          $content[$number] .= '</p>' . '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>' . '<p>';
          $content           = implode( $content, '</p>' );
        }
      }
    }

    return $content;
  } // END query_between_paragraphs_ads

  /**
   * Query the ads with position of after_post_box
   * @return string
   */
  public function query_after_post_box_ads( $post_count )
  {
    if ( is_single() )
      return;

    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'after_teasers_box',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $post_ID  = $ad->ID;
        $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
        $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
        $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
        $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
        $number   = get_post_meta( $post_ID, '_mtp_ad_number', true );
        $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

        if ( $category && is_category() && $post_count == $number || $home && is_home() && $post_count == $number && ! is_front_page() || $tag && is_tag() && $post_count == $number )
        {
          echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
        }
      }
    }
  } // END query_after_post_box_ads

  /**
   * Query the ads with position of after_teasers_box
   * @return string
   */
  public function query_after_teasers_box_ads( $post_count )
  {
    if ( is_single() )
      return;

    global $post;

    $ads = get_posts( array(
      'meta_key'    => '_mtp_ad_position',
      'meta_value'  => 'after_teasers_box',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $post_ID  = $ad->ID;
        $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
        $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
        $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
        $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
        $number   = get_post_meta( $post_ID, '_mtp_ad_number', true );
        $visible  = get_post_meta( $post->ID, '_mtp_ad_' . $post_ID, true );

        if ( $category && is_category() && $post_count == $number || $home && is_home() && $post_count == $number && ! is_front_page() || $tag && is_tag() && $post_count == $number )
        {
          echo '<div class="mtp-ad" style="text-align: center">' . stripslashes( $code ) . '</div>';
        }
      }
    }
  } // END query_after_teasers_box_ads

  /* ------------------------------ *
   * Singular
   * ------------------------------ */

  /**
   * Add the mtp_ad_singular custom meta box to the post and page custom post types.
   */
  public function add_singular_custom_box()
  {
    $post_types = array( 'post', 'page' );

    foreach ( $post_types as $key )
    {
      add_meta_box( 'mtp_ad_singular_meta_box', __( 'MTP Ad', 'mtp_plugin' ), array( $this, 'add_singular_custom_box_cb' ), $key, 'side', 'default', null );
    }
  } // END add_singular_custom_box

  /**
   * Includes the view for the mtp_ad_singular custom meta box in the post and page custom post types.
   * @param obj $post
   */
  public function add_singular_custom_box_cb( $post )
  {
    wp_nonce_field( plugin_basename( __FILE__ ), 'mtp_ads_noncename' );

    include( plugin_dir_path( __FILE__ ) . '/views/admin-singular.php' );
  } // END add_singular_custom_box_cb

  /**
   * Save the mtp_ad_singular meta box data in the mtp_ad custom post type.
   * @param int $post_id
   */
  function add_singular_custom_box_save_postdata( $post_id )
  {
    if ( $_POST['post_type'] == 'post' || $_POST['post_type'] == 'page' )
    {
      if ( ! current_user_can( 'edit_page', $post_id ) )
        return;
    }
    else
    {
      if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
    }

    // CHECK THIS IN THE FUTURE!!!
    // if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    //     return $post_id;

    if ( ! isset( $_POST['mtp_ads_noncename'] ) || ! wp_verify_nonce( $_POST['mtp_ads_noncename'], plugin_basename( __FILE__ ) ) )
      return;

    $post_ID = $_POST['post_ID'];
    $ads     = get_posts( array(
      'meta_key'    => '_mtp_ad_code',
      'numberposts' => '-1',
      'post_status' => 'publish',
      'post_type'   => 'mtp_ad'
    ) );

    if ( $ads )
    {
      foreach ( $ads as $ad )
      {
        $visible = ( ! empty( $_POST['mtp_ad_' . $ad->ID] ) ) ? $_POST['mtp_ad_' . $ad->ID] : '';

        if ( $visible ) :
          add_post_meta( $post_ID, '_mtp_ad_' . $ad->ID, $visible, true ) or update_post_meta( $post_ID, '_mtp_ad_' . $ad->ID, $visible );
        else :
          delete_post_meta( $post_ID, '_mtp_ad_' . $ad->ID, $visible );
        endif;
      }
    }
  } // END add_custom_box_save_postdata

  public function save_ad_into_singular( $post_id )
  {
    $post      = get_post( $post_id );
    $post_type = ( ! empty( $post->post_type ) ) ? $post->post_type : '';

    if ( $post->post_type == 'mtp_ad' && $post->post_date == $post->post_modified && $post->post_status == 'publish' )
    {
      $existing_posts = get_posts( array(
        'post_type'      => array( 'page', 'post' ),
        'post_status'    => array( 'future', 'publish' ),
        'nopaging'       => true,
        'posts_per_page' => '-1'
      ) );

      foreach ( $existing_posts as $existing_post )
      {
        $visible = get_post_meta( $existing_post->ID, '_mtp_ad_' . $post->ID, true );
        
        if ( ! $visible )
        {
          add_post_meta( $existing_post->ID, '_mtp_ad_' . $post->ID, 'on', true );
        }
        else
        {
          delete_post_meta( $existing_post->ID, '_mtp_ad_' . $post->ID, 'on' );
        }
      }
    }
  }

  public function delete_ad_into_singular( $post_id )
  {
    $post      = get_post( $post_id );
    $post_type = ( ! empty( $post->post_type ) ) ? $post->post_type : '';

    if ( $post->post_type == 'mtp_ad' )
    {
      $existing_posts = get_posts( array(
        'post_type'      => array( 'page', 'post' ),
        'post_status'    => array( 'future', 'publish' ),
        'nopaging'       => true,
        'posts_per_page' => '-1'
      ) );

      foreach ( $existing_posts as $existing_post )
      {
        $visible = get_post_meta( $existing_post->ID, '_mtp_ad_' . $post->ID, true );
        
        if ( $visible )
        {
          delete_post_meta( $existing_post->ID, '_mtp_ad_' . $post->ID, 'on' );
        }
      }
    }
  }

  /**
   * Remove the unneeded meta boxes.
   * @return void
   */
  public function remove_meta_boxes()
  {
    if ( wp_get_theme() == 'Thesis' ) :
      remove_meta_box( 'thesis_meta_keywords', 'mtp_ad', 'normal' );
      remove_meta_box( 'thesis_meta_robots', 'mtp_ad', 'normal' );
      remove_meta_box( 'thesis_canonical_link', 'mtp_ad', 'normal' );
      remove_meta_box( 'thesis_html_body', 'mtp_ad', 'normal' );
      remove_meta_box( 'thesis_meta_description', 'mtp_ad', 'normal' );
      remove_meta_box( 'thesis_post_content', 'mtp_ad', 'normal' );
      remove_meta_box( 'thesis_post_image', 'mtp_ad', 'normal' );
      remove_meta_box( 'thesis_post_thumbnail', 'mtp_ad', 'normal' );
      remove_meta_box( 'thesis_redirect', 'mtp_ad', 'normal' );
      remove_meta_box( 'thesis_title_tag', 'mtp_ad', 'normal' );
    endif;
  }
} // END class MTP_Ads

// TODO:    Update the instantiation call of your plugin to the name given at the class definition
$plugin_name = new MTP_Ads();

include( plugin_dir_path( __FILE__ ) . '/widget/plugin.php' );

/**
 * Notice: register_uninstall_hook was called incorrectly.
 * Only a static class method or function can be used in an uninstall hook.
 * Please see Debugging in WordPress for more information.
 * (This message was added in version 3.1.) in /home/mtperformance/Documents/wordpress/wp-includes/functions.php on line 2959
 */
