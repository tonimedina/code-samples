<?php
/*
Plugin Name: MTP Scroll Gallery
Plugin URI:  http://mt-performance.net
Description: Used to create scroll galleries together with the NextGen Gallery plugin.
Version:     1.2
Author:      Toni Medina
Author URI:  http://tonimedina.me
License:     GPL2
*/

/**
* MTP ScrollGallery
*/
class mtp_scrollgallery
{
	/**
	 * Object initialization
	 */
	public function __construct()
	{
		add_shortcode( 'scrollGallery', array( $this, 'shortcode' ) );
		
		$this->actions();
	}

	/**
	 * Hooks a function on to a specific action
	 * 
	 * @return void
	 */
	public function actions()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Check the current post for the existence of a shortcode
	 * 
	 * @param string
	 * @return boolean
	 */
	public function has_shortcode( $shortcode = '' )
	{
		$post_to_check = get_post( get_the_ID() );
		$found         = false;

		if ( !$shortcode )
		{
			return $found;
		}

		if ( stripos( $post_to_check->post_content, '['. $shortcode ) !== false )
		{
			$found = true;
		}

		return $found;
	}

	/**
	 * The required scripts
	 * 
	 * @return void
	 */
	public function scripts()
	{
		if ( $this->has_shortcode( 'scrollGallery' ) )
		{
			wp_register_script( 'jquery-flexslider-min-js', plugins_url( '/js/jquery.flexslider-min.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'jquery-flexslider-min-js' );

			wp_register_style( 'default-style-css', plugins_url( '/css/default-style.css', __FILE__ ), array(), '20120807', 'all' );
			wp_enqueue_style( 'default-style-css' );
		}
	}

	/**
	 * The shortcode
	 * 
	 * @param array
	 * @return string
	 */
	function shortcode( $atts )
	{
		global $wpdb;

		extract(shortcode_atts(array(
			'id' => '',
		),$atts));
		
		$galleries = $wpdb->get_results("SELECT * FROM $wpdb->nggallery WHERE gid = $id");

		if (!empty($galleries))
		{
			$url = get_bloginfo('url');
			$images = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures WHERE galleryid = $id ORDER BY sortorder");

			foreach ($galleries as $key) { $path = $key->path; }

			$output .= '<div class="scroll-gallery-slider flexslider">';
			$output .= '<ul class="slides">';

			foreach ($images as $key)
			{
				$alt = $key->alttext;
				$filename = $key->filename;
				$description = $key->description;
				$exclude = $key->exclude;

				if ($exclude != 1)
				{
					if ($description)
					{
						$output .= '<li>';
						$output .= '<img alt="'.$alt.'" src="'.$url.'/'.$path.'/'.$filename.'" />';
						$output .= '<div class="caption"><p>'.stripslashes($description).'</p></div>';
						$output .= '</li>';
					}
					else
					{
						$output .= '<li><img alt="'.$alt.'" src="'.$url.'/'.$path.'/'.$filename.'" /></li>';
					}
				}
			}
			    
		    $output .= '</ul>';
		    $output .= '</div><!-- END .scroll-gallery-slider -->';

		    $output .= '<div class="scroll-gallery-carousel flexslider">';
		    $output .= '<ul class="slides">';

		    foreach ($images as $key)
		    {
		    	$alt = $key->alttext;
				$filename = $key->filename;
				$exclude = $key->exclude;

		    	if ($exclude != 1)
		    	{
		    		$output .= '<li><img alt="'.$alt.'" height="160" src="'.$url.'/'.$path.'/thumbs/thumbs_'.$filename.'" width="160" /></li>';
		    	}
		    }
		    $output .= '</ul>';
		    $output .= '</div><!-- END .scroll-gallery-carousel -->';

			$output .= '
			<script type="text/javascript">
			;(function($){
				$(".scroll-gallery-carousel").flexslider({
					animation: "slide",
					animationLoop: false,
					slideshow: false,
					controlNav: false,
					prevText: "&lt;",
					nextText: "&gt;",
					asNavFor: ".scroll-gallery-slider",
					itemWidth: 160,
					itemMargin: 0,
					maxItems: 3,
				});
				$(".scroll-gallery-slider").flexslider({
					animation: "slide",
					animationLoop: true,
					slideshow: false,
					controlNav: false,
					prevText: "&lt;",
					nextText: "&gt;",
					sync: ".scroll-gallery-carousel",
				});
			})(jQuery);
			</script>';

			return $output;
		}
		else
		{
			$output = '<p>The ID you entered does not match any gallery.</p>';
			return $output;
		}
	}
}

new mtp_scrollgallery();