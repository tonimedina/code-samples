<?php
/*
Plugin Name: MTP Slider
Plugin URI:  http://mt-performance.net
Description: Creates the Slides Custom Post Type
Version:     1.0
Author:      Toni Medina
Author URI:  http://tonimedina.me
License:     GPL2
*/

class mtp_slider
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init()
	{
		$this->actions();
		//$this->filters();
	}

	public function actions()
	{
		//add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	}

	public function filters()
	{
		add_filter( 'image_size_names_choose', array( $this, 'more_thumbnails' ) );
	}

	public function after_setup_theme()
	{
		add_image_size( 'slider_thumbnail', 760, 507, true );
	}

	public function more_thumbnails( $sizes )
	{
		$custom_sizes = array(
			'slider_thumbnail' => 'Slide'
		);

		return array_merge( $sizes, $custom_sizes );
	}

	public function scripts()
	{
		$css_options = get_option( 'mtp_slider_css_settings' );

		if ( !jQuery )
		{
			wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', true, 1, false );
			wp_enqueue_script( 'jquery' );
		}

		if ( is_home() || is_front_page() )
		{
			wp_register_script( 'flexslider_js', get_bloginfo( 'url' ) .'/wp-content/plugins/mtp-slider/js/jquery.flexslider.min.js', 'jquery', 2.1, false );
			wp_enqueue_script( 'flexslider_js' );

			if ( $css_options['default_css'] == 1 )
			{
				wp_register_style( 'style_css', get_bloginfo( 'url' ) .'/wp-content/plugins/mtp-slider/css/style.css' );
				wp_enqueue_style( 'style_css' );
			}
		}
	}
}

new mtp_slider();

require_once dirname( __FILE__ ) .'/custom_post_type.php';
require_once dirname( __FILE__ ) .'/admin.php';