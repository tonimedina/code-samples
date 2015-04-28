<?php
/*
Name:        Slider Box
Author:      Toni Medina
Description: The Slider Box *Please Use It Together With The MTP Slider Plugin
Version:     1.0
Class:       mtp_slider_box
*/

class mtp_slider_box extends thesis_box
{
	public function construct()
	{
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init()
	{
	}

	protected function translate()
	{
		$this->title = __( 'Slider Box', 'slide' );		
	}

	public function html( $depth )
	{
		global $thesis;

		$tab = str_repeat( '\t', $depth );
		$main_options                 = get_option( 'mtp_slider_main_settings' );
		$usability_features_options   = get_option( 'mtp_slider_usability_features_settings' );
		$primary_controls_options     = get_option( 'mtp_slider_primary_controls_settings' );
		$secondary_navigation_options = get_option( 'mtp_slider_secondary_navigation_settings' );
		$special_properties_options   = get_option( 'mtp_slider_special_properties_settings' );
		$carousel                     = get_option( 'mtp_slider_carousel_settings' );
		$callback_api_options         = get_option( 'mtp_slider_callback_api_settings' );
		$css_options                  = get_option( 'mtp_slider_css_settings' );

		$the_query = new WP_Query( array(
			'post_type'   => 'slide',
			'post_status' => 'publish'
		) );

		if ( $the_query->have_posts() ) : ?>
		<div class="flex-container">
			<div class="flexslider">
				<ul class="slides">
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<li>
						<?php
						$slide_link = get_post_meta( get_the_ID(), 'slide_link', true );

						if ( has_post_thumbnail() ) : the_post_thumbnail( 'full' ); endif;
						?>
						<div class="flex-caption">
							<h2>
								<?php if ( $slide_link ) : ?>
								<a href="<?php echo $link; ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'slide' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php echo the_title(); ?></a></h2>
								<?php else : the_title(); endif; ?>
							</h2>
							<?php the_excerpt(); ?>
						</div>
					</li>
					<?php endwhile; ?>
				</ul>
			</div>
		</div>
		<?php endif; wp_reset_postdata(); ?>

		<?php if ( !empty( $css_options['custom_css'] ) && $css_options['default_css'] != 1 ) : ?><style type="text/css"><?php echo $css_options['custom_css']; ?></style><?php endif; ?>

		<script type="text/javascript">
		// <![CDATA[
		;(function($) {
			$('.flexslider').flexslider({
				namespace: "<?php echo $main_options['namespace'] == '' ? 'flex-' : $main_options['namespace']; ?>",
				selector: "<?php echo $main_options['selector'] == '' ? '.slides > li' : $main_options['selector']; ?>",
				animation: "<?php echo $main_options['animation'] == '' ? 'fade' : $main_options['animation']; ?>",
				easing: "<?php echo $main_options['easing'] == '' ? 'swing' : $main_options['easing']; ?>",
				direction: "<?php echo $main_options['direction'] == '' ? 'horizontal' : $main_options['direction']; ?>",
				reverse: <?php echo $main_options['reverse'] == '' ? false : $main_options['reverse']; ?>,
				animationLoop: <?php echo $main_options['animationLoop'] == '' ? true : $main_options['animationLoop']; ?>,
				smoothHeight: <?php echo $main_options['smoothHeight'] == '' ? false : $main_options['smoothHeight']; ?>,
				startAt: <?php echo $main_options['startAt'] == '' ? 0 : $main_options['startAt']; ?>,
				slideshow: <?php echo $main_options['slideshow'] == '' ? true : $main_options['slideshow']; ?>,
				slideshowSpeed: <?php echo $main_options['slideshowSpeed'] == '' ? 7000 : $main_options['slideshowSpeed']; ?>,
				animationSpeed: <?php echo $main_options['animationSpeed'] == '' ? 600 : $main_options['animationSpeed']; ?>,
				initDelay: <?php echo $main_options['initDelay'] == '' ? 0 : $main_options['initDelay']; ?>,
				randomize: <?php echo $main_options['randomize'] == '' ? false : $main_options['randomize']; ?>,
				pauseOnAction: <?php echo $usability_features_options['pauseOnAction'] == '' ? true : $usability_features_options['pauseOnAction']; ?>,
				pauseOnHover: <?php echo $usability_features_options['pauseOnHover'] == '' ? false : $usability_features_options['pauseOnHover']; ?>,
				useCSS: <?php echo $usability_features_options['useCSS'] == '' ? true : $usability_features_options['useCSS']; ?>,
				touch: <?php echo $usability_features_options['touch'] == '' ? true : $usability_features_options['touch']; ?>,
				video: <?php echo $usability_features_options['video'] == '' ? false : $usability_features_options['video']; ?>,
				controlNav: <?php echo $primary_controls_options['controlNav'] == '' ? true : $primary_controls_options['controlNav']; ?>,
				directionNav: <?php echo $primary_controls_options['directionNav'] == '' ? true : $primary_controls_options['directionNav']; ?>,
				prevText: "<?php echo $primary_controls_options['prevText'] == '' ? 'Previous' : $primary_controls_options['prevText']; ?>",
				nextText: "<?php echo $primary_controls_options['nextText'] == '' ? 'Next' : $primary_controls_options['nextText']; ?>",
				keyboard: <?php echo $secondary_navigation_options['keyboard'] == '' ? true : $secondary_navigation_options['keyboard']; ?>,
				multipleKeyboard: <?php echo $secondary_navigation_options['multipleKeyboard'] == '' ? false : $secondary_navigation_options['multipleKeyboard']; ?>,
				mousewheel: <?php echo $secondary_navigation_options['mousewheel'] == '' ? false : $secondary_navigation_options['mousewheel']; ?>,
				pausePlay: <?php echo $secondary_navigation_options['pausePlay'] == '' ? false : $secondary_navigation_options['pausePlay']; ?>,
				pauseText: "<?php echo $secondary_navigation_options['pauseText'] == '' ? 'Pause' : $secondary_navigation_options['pauseText']; ?>",
				playText: "<?php echo $secondary_navigation_options['playText'] == '' ? 'Play' : $secondary_navigation_options['playText']; ?>",
				controlsContainer: "<?php echo $special_properties_options['controlsContainer']; ?>",
				manualControls: "<?php echo $special_properties_options['manualControls']; ?>",
				sync: "<?php echo $special_properties_options['sync']; ?>",
				asNavFor: "<?php echo $special_properties_options['asNavFor']; ?>",
				itemWidth: <?php echo $carousel['itemWidth'] == '' ? 0 : $carousel['itemWidth']; ?>,
				itemMargin: <?php echo $carousel['itemMargin'] == '' ? 0 : $carousel['itemMargin']; ?>,
				minItems: <?php echo $carousel['minItems'] == '' ? 0 : $carousel['minItems']; ?>,
				maxItems: <?php echo $carousel['maxItems'] == '' ? 0 : $carousel['maxItems']; ?>,
				move: <?php echo $carousel['move'] == '' ? 0 : $carousel['move']; ?>,
				start: function(){<?php echo $callback_api_options['start']; ?>},
				before: function(){<?php echo $callback_api_options['before']; ?>},
				after: function(){<?php echo $callback_api_options['after']; ?>},
				end: function(){<?php echo $callback_api_options['end']; ?>},
				added: function(){<?php echo $callback_api_options['added']; ?>},
				removed: function(){<?php echo $callback_api_options['removed']; ?>}
			});
		})(jQuery);
		// ]]>
		</script>
		<?php
	}
}