<?php

class mtp_slider_admin
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init()
	{
		$this->actions();
		$this->main_options                 = get_option( 'mtp_slider_main_settings' );
		$this->usability_features_options   = get_option( 'mtp_slider_usability_features_settings' );
		$this->primary_controls_options     = get_option( 'mtp_slider_primary_controls_settings' );
		$this->secondary_navigation_options = get_option( 'mtp_slider_secondary_navigation_settings' );
		$this->special_properties_options   = get_option( 'mtp_slider_special_properties_settings' );
		$this->carousel                     = get_option( 'mtp_slider_carousel_settings' );
		$this->callback_api_options         = get_option( 'mtp_slider_callback_api_settings' );
		$this->css_options                  = get_option( 'mtp_slider_css_settings' );
	}

	public function actions()
	{
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_init', array( $this, 'main_settings' ) );
		add_action( 'admin_init', array( $this, 'usability_features' ) );
		add_action( 'admin_init', array( $this, 'primary_controls' ) );
		add_action( 'admin_init', array( $this, 'secondary_navigation' ) );
		add_action( 'admin_init', array( $this, 'special_properties' ) );
		add_action( 'admin_init', array( $this, 'carousel' ) );
		add_action( 'admin_init', array( $this, 'callback_api' ) );
		add_action( 'admin_init', array( $this, 'css' ) );
	}

	public function add_options_page()
	{
		add_options_page( _x( 'MTP Slider Options', 'slide' ), _x( 'MTP Slider', 'slide' ), 'manage_options', __FILE__, array( $this, 'add_options_page_cb' ) );
	}

	public function add_options_page_cb( $active_tab = '' )
	{
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'main_settings';  
		?>
		<div class="wrap">
			<?php
			screen_icon();
			settings_errors();
			?>
			<h2 class="nav-tab-wrapper">
				<?php _e( 'MTP Slider', 'slide' ); ?>
				<a class="nav-tab <?php echo $active_tab == 'main_settings' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-slider/admin.php&tab=main_settings"><?php _e( 'Main', 'slide' ); ?></a>
				<a class="nav-tab <?php echo $active_tab == 'usability_features' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-slider/admin.php&tab=usability_features"><?php _e( 'Usability Features', 'slide' ); ?></a>
				<a class="nav-tab <?php echo $active_tab == 'primary_controls' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-slider/admin.php&tab=primary_controls"><?php _e( 'Primary Controls', 'slide' ); ?></a>
				<a class="nav-tab <?php echo $active_tab == 'secondary_navigation' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-slider/admin.php&tab=secondary_navigation"><?php _e( 'Secondary Navigation', 'slide' ); ?></a>
				<a class="nav-tab <?php echo $active_tab == 'special_properties' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-slider/admin.php&tab=special_properties"><?php _e( 'Special Properties', 'slide' ); ?></a>
				<a class="nav-tab <?php echo $active_tab == 'carousel' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-slider/admin.php&tab=carousel"><?php _e( 'Carousel', 'slide' ); ?></a>
				<a class="nav-tab <?php echo $active_tab == 'callback_api' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-slider/admin.php&tab=callback_api"><?php _e( 'Callback API', 'slide' ); ?></a>
				<a class="nav-tab <?php echo $active_tab == 'css_settings' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-slider/admin.php&tab=css_settings"><?php _e( 'CSS Settings', 'slide' ); ?></a>
			</h2>

			<form action="options.php" enctype="multipart/form-data" method="post">
				<?php
				switch ( $active_tab ) {
					case 'usability_features':
						settings_fields( 'mtp_slider_usability_features_settings' );
						do_settings_sections( 'mtp_slider_usability_features_settings' );
						break;

					case 'primary_controls':
						settings_fields( 'mtp_slider_primary_controls_settings' );
						do_settings_sections( 'mtp_slider_primary_controls_settings' );
						break;

					case 'secondary_navigation':
						settings_fields( 'mtp_slider_secondary_navigation_settings' );
						do_settings_sections( 'mtp_slider_secondary_navigation_settings' );
						break;

					case 'special_properties':
						settings_fields( 'mtp_slider_special_properties_settings' );
						do_settings_sections( 'mtp_slider_special_properties_settings' );
						break;

					case 'carousel':
						settings_fields( 'mtp_slider_carousel_settings' );
						do_settings_sections( 'mtp_slider_carousel_settings' );
						break;

					case 'callback_api':
						settings_fields( 'mtp_slider_callback_api_settings' );
						do_settings_sections( 'mtp_slider_callback_api_settings' );
						break;

					case 'css_settings':
						settings_fields( 'mtp_slider_css_settings' );
						do_settings_sections( 'mtp_slider_css_settings' );
						break;

					default:
						settings_fields( 'mtp_slider_main_settings' );
						do_settings_sections( 'mtp_slider_main_settings' );
						break;
				}
				
				submit_button();
				?>
			</form>
		</div>
		<?php 
	}

	public function main_settings()
	{
		if ( get_option( 'mtp_slider_main_settings' ) == false )
		{
			add_option( 'mtp_slider_main_settings' );
		}

		add_settings_section( 'main_settings', _x( 'Main', 'slide' ), array( $this, 'main_settings_cb' ), 'mtp_slider_main_settings' );

		add_settings_field( 'namespace', _x( 'Namespace', 'slide' ), array( $this, 'namespace_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'namespace' ) );
		add_settings_field( 'selector', _x( 'Selector', 'slide' ), array( $this, 'selector_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'selector' ) );
		add_settings_field( 'animation', _x( 'Animation', 'slide' ), array( $this, 'animation_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'animation' ) );
		add_settings_field( 'easing', _x( 'Easing', 'slide' ), array( $this, 'easing_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'easing' ) );
		add_settings_field( 'direction', _x( 'Direction', 'slide' ), array( $this, 'direction_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'direction' ) );
		add_settings_field( 'reverse', _x( 'Reverse', 'slide' ), array( $this, 'reverse_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'reverse' ) );
		add_settings_field( 'animationLoop', _x( 'Animation Loop', 'slide' ), array( $this, 'animationLoop_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'animationLoop' ) );
		add_settings_field( 'smoothHeight', _x( 'Smooth Height', 'slide' ), array( $this, 'smoothHeight_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'smoothHeight' ) );
		add_settings_field( 'startAt', _x( 'Start At', 'slide' ), array( $this, 'startAt_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'startAt' ) );
		add_settings_field( 'slideshow', _x( 'Slideshow', 'slide' ), array( $this, 'slideshow_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'slideshow' ) );
		add_settings_field( 'slideshowSpeed', _x( 'Slideshow Speed', 'slide' ), array( $this, 'slideshowSpeed_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'slideshowSpeed' ) );
		add_settings_field( 'animationSpeed', _x( 'Animation Speed', 'slide' ), array( $this, 'animationSpeed_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'animationSpeed' ) );
		add_settings_field( 'initDelay', _x( 'Initial Delay', 'slide' ), array( $this, 'initDelay_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'initDelay' ) );
		add_settings_field( 'randomize', _x( 'Randomize', 'slide' ), array( $this, 'randomize_cb' ), 'mtp_slider_main_settings', 'main_settings', array( 'label_for' => 'randomize' ) );

		register_setting( 'mtp_slider_main_settings', 'mtp_slider_main_settings' );
	}

	public function main_settings_cb() {}

	public function namespace_cb()
	{
		?>
		<input class="code regular-text" id="namespace" name="mtp_slider_main_settings[namespace]" placeholder="flex-" type="text" value="<?php echo $this->main_options['namespace']; ?>">
		<span class="description"><?php _e( 'Prefix string attached to the class of every element generated by the plugin.', 'slide' ); ?></span>
		<?php
	}

	public function selector_cb()
	{
		?>
		<input class="code regular-text" id="selector" name="mtp_slider_main_settings[selector]" placeholder=".slides > li" type="text" value="<?php echo $this->main_options['selector']; ?>">
		<span class="description"><?php _e( 'Select your animation type.', 'slide' ); ?></span>
		<?php
	}

	public function animation_cb()
	{
		$options = array( 'fade', 'slide' );
		?>
		<select id="animation" name="mtp_slider_main_settings[animation]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->main_options['animation'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Select your animation type.', 'slide' ); ?></span>
		<?php
	}

	public function easing_cb()
	{
		$options = array( 'swing', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic', 'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint', 'easeInOutQuint', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo', 'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic', 'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce' );
		?>
		<select id="easing" name="mtp_slider_main_settings[easing]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->main_options['easing'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Determines the easing method used in jQuery transitions.', 'slide' ); ?></span>
		<?php
	}

	public function direction_cb()
	{
		$options = array( 'horizontal', 'vertical' );
		?>
		<select id="direction" name="mtp_slider_main_settings[direction]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->main_options['direction'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Select the sliding direction.', 'slide' ); ?></span>
		<?php
	}

	public function reverse_cb()
	{
		$options = array( 'false', 'true' );
		?>
		<select id="reverse" name="mtp_slider_main_settings[reverse]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->main_options['reverse'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Reverse the animation direction.', 'slide' ); ?></span>
		<?php
	}

	public function animationLoop_cb()
	{
		$options = array( 'true', 'false' );
		?>
		<select id="animationLoop" name="mtp_slider_main_settings[animationLoop]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->main_options['animationLoop'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Should the animation loop?', 'slide' ); ?></span>
		<?php
	}

	public function smoothHeight_cb()
	{
		$options = array( 'false', 'true' );
		?>
		<select id="smoothHeight" name="mtp_slider_main_settings[smoothHeight]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->main_options['smoothHeight'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Allow height of the slider to animate smoothly in horizontal mode.', 'slide' ); ?></span>
		<?php
	}

	public function startAt_cb()
	{
		?>
		<input class="small-text" id="startAt" min="0" name="mtp_slider_main_settings[startAt]" placeholder="0" step="1" type="number" value="<?php echo $this->main_options['startAt']; ?>">
		<span class="description"><?php _e( 'The slide that the slider should start on.', 'slide' ); ?></span>
		<?php
	}

	public function slideshow_cb()
	{
		$options = array( 'true', 'false' );
		?>
		<select id="slideshow" name="mtp_slider_main_settings[slideshow]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->main_options['slideshow'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Animate slider automatically.', 'slide' ); ?></span>
		<?php
	}

	public function slideshowSpeed_cb()
	{
		?>
		<input class="small-text" id="slideshowSpeed" min="0" name="mtp_slider_main_settings[slideshowSpeed]" placeholder="7000" step="1000" type="number" value="<?php echo $this->main_options['slideshowSpeed']; ?>">
		<span class="description"><?php _e( 'Set the speed of the slideshow cycling, in milliseconds.', 'slide' ); ?></span>
		<?php
	}

	public function animationSpeed_cb()
	{
		?>
		<input class="small-text" id="animationSpeed" min="0" name="mtp_slider_main_settings[animationSpeed]" placeholder="600" step="100" type="number" value="<?php echo $this->main_options['animationSpeed']; ?>">
		<span class="description"><?php _e( 'Set the speed of animations, in milliseconds.', 'slide' ); ?></span>
		<?php
	}

	public function initDelay_cb()
	{
		?>
		<input class="small-text" id="initDelay" min="0" name="mtp_slider_main_settings[initDelay]" placeholder="0" step="1" type="number" value="<?php echo $this->main_options['initDelay']; ?>">
		<span class="description"><?php _e( 'Set an initialization delay, in milliseconds.', 'slide' ); ?></span>
		<?php
	}

	public function randomize_cb()
	{
		$options = array( 'false', 'true' );
		?>
		<select id="randomize" name="mtp_slider_main_settings[randomize]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->main_options['randomize'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Randomize slide order.', 'slide' ); ?></span>
		<?php
	}

	// Usability Features

	public function usability_features()
	{
		if ( get_option( 'mtp_slider_usability_features_settings' ) == false )
		{
			add_option( 'mtp_slider_usability_features_settings' );
		}

		add_settings_section( 'usability_features', _x( 'Usability Features', 'slide' ), array( $this, 'usability_features_cb' ), 'mtp_slider_usability_features_settings' );

		add_settings_field( 'pauseOnAction', _x( 'Pause On Action', 'slide' ), array( $this, 'pauseOnAction_cb' ), 'mtp_slider_usability_features_settings', 'usability_features', array( 'label_for' => 'pauseOnAction' ) );
		add_settings_field( 'pauseOnHover', _x( 'Pause On Hover', 'slide' ), array( $this, 'pauseOnHover_cb' ), 'mtp_slider_usability_features_settings', 'usability_features', array( 'label_for' => 'pauseOnHover' ) );
		add_settings_field( 'useCSS', _x( 'Use CSS', 'slide' ), array( $this, 'useCSS_cb' ), 'mtp_slider_usability_features_settings', 'usability_features', array( 'label_for' => 'useCSS' ) );
		add_settings_field( 'touch', _x( 'Touch', 'slide' ), array( $this, 'touch_cb' ), 'mtp_slider_usability_features_settings', 'usability_features', array( 'label_for' => 'touch' ) );
		add_settings_field( 'video', _x( 'Video', 'slide' ), array( $this, 'video_cb' ), 'mtp_slider_usability_features_settings', 'usability_features', array( 'label_for' => 'video' ) );

		register_setting( 'mtp_slider_usability_features_settings', 'mtp_slider_usability_features_settings' );
	}

	public function usability_features_cb() {}

	public function pauseOnAction_cb()
	{
		$options = array( 'true', 'false' );
		?>
		<select id="pauseOnAction" name="mtp_slider_usability_features_settings[pauseOnAction]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->usability_features_options['pauseOnAction'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Pause the slideshow when interacting with control elements.', 'slide' ); ?></span>
		<?php
	}

	public function pauseOnHover_cb()
	{
		$options = array( 'false', 'true' );
		?>
		<select id="pauseOnHover" name="mtp_slider_usability_features_settings[pauseOnHover]">
			<?php
			echo $this->usability_features_options['pauseOnHover'];
			foreach ( $options as $option ) :
			$selected = ( $this->usability_features_options['pauseOnHover'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Pause the slideshow when hovering over slider, then resume when no longer hovering.', 'slide' ); ?></span>
		<?php
	}

	public function useCSS_cb()
	{
		$options = array( 'true', 'false' );
		?>
		<select id="useCSS" name="mtp_slider_usability_features_settings[useCSS]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->usability_features_options['useCSS'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Slider will use CSS3 transitions if available.', 'slide' ); ?></span>
		<?php
	}

	public function touch_cb()
	{
		$options = array( 'true', 'false' );
		?>
		<select id="touch" name="mtp_slider_usability_features_settings[touch]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->usability_features_options['touch'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Allow touch swipe navigation of the slider on touch-enabled devices.', 'slide' ); ?></span>
		<?php
	}

	public function video_cb()
	{
		$options = array( 'false', 'true' );
		?>
		<select id="video" name="mtp_slider_usability_features_settings[video]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->usability_features_options['video'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'If using video in the slider, will prevent CSS3 3D Transforms to avoid graphical glitches.', 'slide' ); ?></span>
		<?php
	}

	// Primary Controls
	
	public function primary_controls()
	{
		if ( get_option( 'mtp_slider_primary_controls_settings' ) == false )
		{
			add_option( 'mtp_slider_primary_controls_settings' );
		}

		add_settings_section( 'primary_controls', _x( 'Primary Controls', 'slide' ), array( $this, 'primary_controls_cb' ), 'mtp_slider_primary_controls_settings' );

		add_settings_field( 'controlNav', _x( 'Control Navigation', 'slide' ), array( $this, 'controlNav_cb' ), 'mtp_slider_primary_controls_settings', 'primary_controls', array( 'label_for' => 'controlNav' ) );
		add_settings_field( 'directionNav', _x( 'Direction Navigation', 'slide' ), array( $this, 'directionNav_cb' ), 'mtp_slider_primary_controls_settings', 'primary_controls', array( 'label_for' => 'directionNav' ) );
		add_settings_field( 'prevText', _x( 'Previous Text', 'slide' ), array( $this, 'prevText_cb' ), 'mtp_slider_primary_controls_settings', 'primary_controls', array( 'label_for' => 'prevText' ) );
		add_settings_field( 'nextText', _x( 'Next Text', 'slide' ), array( $this, 'nextText_cb' ), 'mtp_slider_primary_controls_settings', 'primary_controls', array( 'label_for' => 'nextText' ) );

		register_setting( 'mtp_slider_primary_controls_settings', 'mtp_slider_primary_controls_settings' );
	}

	public function primary_controls_cb() {}

	public function controlNav_cb()
	{
		$options = array( 'true', 'false' );
		?>
		<select id="controlNav" name="mtp_slider_primary_controls_settings[controlNav]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->primary_controls_options['controlNav'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Create navigation for paging control of each clide?', 'slide' ); ?></span>
		<?php
	}

	public function directionNav_cb()
	{
		$options = array( 'true', 'false' );
		?>
		<select id="directionNav" name="mtp_slider_primary_controls_settings[directionNav]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->primary_controls_options['directionNav'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Create navigation for previous/next navigation?', 'slide' ); ?></span>
		<?php
	}

	public function prevText_cb()
	{
		?>
		<input class="regular-text" id="prevText" name="mtp_slider_primary_controls_settings[prevText]" placeholder="Previous" type="text" value="<?php echo $this->primary_controls_options['prevText']; ?>">
		<span class="description"><?php _e( 'Set the text for the "previous" directionNav item.', 'slide' ); ?></span>
		<?php
	}

	public function nextText_cb()
	{
		?>
		<input class="regular-text" id="nextText" name="mtp_slider_primary_controls_settings[nextText]" placeholder="Next" type="text" value="<?php echo $this->primary_controls_options['nextText']; ?>">
		<span class="description"><?php _e( 'Set the text for the "next" directionNav item.', 'slide' ); ?></span>
		<?php
	}

	// Secondary Navigation

	public function secondary_navigation()
	{
		if ( get_option( 'mtp_slider_secondary_navigation_settings' ) == false )
		{
			add_option( 'mtp_slider_secondary_navigation_settings' );
		}

		add_settings_section( 'secondary_navigation', _x( 'Secondaty Navigation', 'slide' ), array( $this, 'secondary_navigation_cb' ), 'mtp_slider_secondary_navigation_settings' );

		add_settings_field( 'keyboard', _x( 'Keyboard', 'slide' ), array( $this, 'keyboard_cb' ), 'mtp_slider_secondary_navigation_settings', 'secondary_navigation', array( 'label_for' => 'keyboard' ) );
		add_settings_field( 'multipleKeyboard', _x( 'Multiple Keyboard', 'slide' ), array( $this, 'multipleKeyboard_cb' ), 'mtp_slider_secondary_navigation_settings', 'secondary_navigation', array( 'label_for' => 'multipleKeyboard' ) );
		add_settings_field( 'mousewheel', _x( 'Mousewheel', 'slide' ), array( $this, 'mousewheel_cb' ), 'mtp_slider_secondary_navigation_settings', 'secondary_navigation', array( 'label_for' => 'mousewheel' ) );
		add_settings_field( 'pausePlay', _x( 'Pause Play', 'slide' ), array( $this, 'pausePlay_cb' ), 'mtp_slider_secondary_navigation_settings', 'secondary_navigation', array( 'label_for' => 'pausePlay' ) );
		add_settings_field( 'pauseText', _x( 'Pause Text', 'slide' ), array( $this, 'pauseText_cb' ), 'mtp_slider_secondary_navigation_settings', 'secondary_navigation', array( 'label_for' => 'pauseText' ) );
		add_settings_field( 'playText', _x( 'Play Text', 'slide' ), array( $this, 'playText_cb' ), 'mtp_slider_secondary_navigation_settings', 'secondary_navigation', array( 'label_for' => 'playText' ) );

		register_setting( 'mtp_slider_secondary_navigation_settings', 'mtp_slider_secondary_navigation_settings' );
	}

	public function secondary_navigation_cb() {}

	public function keyboard_cb()
	{
		$options = array( 'true', 'false' );
		?>
		<select id="keyboard" name="mtp_slider_secondary_navigation_settings[keyboard]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->secondary_navigation_options['keyboard'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Allow slider navigating via keyboard left/right keys.', 'slide' ); ?></span>
		<?php
	}

	public function multipleKeyboard_cb()
	{
		$options = array( 'false', 'true' );
		?>
		<select id="multipleKeyboard" name="mtp_slider_secondary_navigation_settings[multipleKeyboard]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->secondary_navigation_options['multipleKeyboard'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Allow keyboard navigation to affect multiple sliders. Default behavior cuts out keyboard navigation with more than one slider present.', 'slide' ); ?></span>
		<?php
	}

	public function mousewheel_cb()
	{
		$options = array( 'false', 'true' );
		?>
		<select id="mousewheel" name="mtp_slider_secondary_navigation_settings[mousewheel]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->secondary_navigation_options['mousewheel'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Allows slider navigating via mousewheel.', 'slide' ); ?></span>
		<?php
	}

	public function pausePlay_cb()
	{
		$options = array( 'false', 'true' );
		?>
		<select id="pausePlay" name="mtp_slider_secondary_navigation_settings[pausePlay]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->secondary_navigation_options['pausePlay'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Create pause/play dynamic element.', 'slide' ); ?></span>
		<?php
	}

	public function pauseText_cb()
	{
		?>
		<input class="regular-text" id="pauseText" name="mtp_slider_secondary_navigation_settings[pauseText]" placeholder="Pause" type="text" value="<?php echo $this->secondary_navigation_options['pauseText']; ?>">
		<span class="description"><?php _e( 'Set the text for the "pause" pausePlay item.', 'slide' ); ?></span>
		<?php
	}

	public function playText_cb()
	{
		?>
		<input class="regular-text" id="playText" name="mtp_slider_secondary_navigation_settings[playText]" placeholder="Play" type="text" value="<?php echo $this->secondary_navigation_options['playText']; ?>">
		<span class="description"><?php _e( 'Set the text for the "play" pausePlay item.', 'slide' ); ?></span>
		<?php
	}

	// Special Properties
	
	public function special_properties()
	{
		if ( get_option( 'mtp_slider_special_properties_settings' ) == false )
		{
			add_option( 'mtp_slider_special_properties_settings' );
		}

		add_settings_section( 'special_properties', _x( 'Special Properties', 'slide' ), array( $this, 'special_properties_cb' ), 'mtp_slider_special_properties_settings' );

		add_settings_field( 'controlsContainer', _x( 'Controls Container', 'slide' ), array( $this, 'controlsContainer_cb' ), 'mtp_slider_special_properties_settings', 'special_properties', array( 'label_for' => 'controlsContainer' ) );
		add_settings_field( 'manualControls', _x( 'Manual Controls', 'slide' ), array( $this, 'manualControls_cb' ), 'mtp_slider_special_properties_settings', 'special_properties', array( 'label_for' => 'manualControls' ) );
		add_settings_field( 'sync', _x( 'Sync', 'slide' ), array( $this, 'sync_cb' ), 'mtp_slider_special_properties_settings', 'special_properties', array( 'label_for' => 'sync' ) );
		add_settings_field( 'asNavFor', _x( 'As Navigation For', 'slide' ), array( $this, 'asNavFor_cb' ), 'mtp_slider_special_properties_settings', 'special_properties', array( 'label_for' => 'asNavFor' ) );

		register_setting( 'mtp_slider_special_properties_settings', 'mtp_slider_special_properties_settings' );
	}

	public function special_properties_cb() {}

	public function controlsContainer_cb()
	{
		?>
		<input class="code regular-text" id="controlsContainer" name="mtp_slider_special_properties_settings[controlsContainer]" type="text" value="<?php echo $this->special_properties_options['controlsContainer']; ?>">
		<span class="description"><?php _e( 'Declare which container the navigation elements should be appended too.', 'slide' ); ?></span>
		<?php
	}

	public function manualControls_cb()
	{
		?>
		<input class="code regular-text" id="manualControls" name="mtp_slider_special_properties_settings[manualControls]" type="text" value="<?php echo $this->special_properties_options['manualControls']; ?>">
		<span class="description"><?php _e( 'Declare custom control navigation.', 'slide' ); ?></span>
		<?php
	}

	public function sync_cb()
	{
		?>
		<input class="code regular-text" id="sync" name="mtp_slider_special_properties_settings[sync]" type="text" value="<?php echo $this->special_properties_options['sync']; ?>">
		<span class="description"><?php _e( 'Mirror the actions performed on this slider with another slider.', 'slide' ); ?></span>
		<?php
	}

	public function asNavFor_cb()
	{
		?>
		<input class="code regular-text" id="asNavFor" name="mtp_slider_special_properties_settings[asNavFor]" type="text" value="<?php echo $this->special_properties_options['asNavFor']; ?>">
		<span class="description"><?php _e( 'Internal property exposed for turning the slider into a thumbnail navigation for another slider.', 'slide' ); ?></span>
		<?php
	}

	// Carousel Options
	
	public function carousel()
	{
		if ( get_option( 'mtp_slider_carousel_settings' ) == false )
		{
			add_option( 'mtp_slider_carousel_settings' );
		}

		add_settings_section( 'carousel', _x( 'Carousel', 'slide' ), array( $this, 'carousel_cb' ), 'mtp_slider_carousel_settings' );

		add_settings_field( 'itemWidth', _x( 'Item Width', 'slide' ), array( $this, 'itemWidth_cb' ), 'mtp_slider_carousel_settings', 'carousel', array( 'label_for' => 'itemWidth' ) );
		add_settings_field( 'itemMargin', _x( 'Item Margin', 'slide' ), array( $this, 'itemMargin_cb' ), 'mtp_slider_carousel_settings', 'carousel', array( 'label_for' => 'itemMargin' ) );
		add_settings_field( 'minItems', _x( 'Minimum Items', 'slide' ), array( $this, 'minItems_cb' ), 'mtp_slider_carousel_settings', 'carousel', array( 'label_for' => 'minItems' ) );
		add_settings_field( 'maxItems', _x( 'Maximum Items', 'slide' ), array( $this, 'maxItems_cb' ), 'mtp_slider_carousel_settings', 'carousel', array( 'label_for' => 'maxItems' ) );
		add_settings_field( 'move', _x( 'Move', 'slide' ), array( $this, 'move_cb' ), 'mtp_slider_carousel_settings', 'carousel', array( 'label_for' => 'move' ) );

		register_setting( 'mtp_slider_carousel_settings', 'mtp_slider_carousel_settings' );
	}

	public function carousel_cb() {}

	public function itemWidth_cb()
	{
		?>
		<input class="small-text" id="itemWidth" min="0" name="mtp_slider_carousel_settings[itemWidth]" placeholder="0" step="1" type="number" value="<?php echo $this->carousel['itemWidth']; ?>">
		<span class="description"><?php _e( 'Box-model width of individual carousel items, including horizontal borders and padding.', 'slide' ); ?></span>
		<?php
	}

	public function itemMargin_cb()
	{
		?>
		<input class="small-text" id="itemMargin" min="0" name="mtp_slider_carousel_settings[itemMargin]" placeholder="0" step="1" type="number" value="<?php echo $this->carousel['itemMargin']; ?>">
		<span class="description"><?php _e( 'Margin between carousel items.', 'slide' ); ?></span>
		<?php
	}

	public function minItems_cb()
	{
		?>
		<input class="small-text" id="minItems" min="0" name="mtp_slider_carousel_settings[minItems]" placeholder="0" step="1" type="number" value="<?php echo $this->carousel['minItems']; ?>">
		<span class="description"><?php _e( 'Minimum number of carousel items that should be visible.', 'slide' ); ?></span>
		<?php
	}

	public function maxItems_cb()
	{
		?>
		<input class="small-text" id="maxItems" min="0" name="mtp_slider_carousel_settings[maxItems]" placeholder="0" step="1" type="number" value="<?php echo $this->carousel['maxItems']; ?>">
		<span class="description"><?php _e( 'Maxmimum number of carousel items that should be visible.', 'slide' ); ?></span>
		<?php
	}

	public function move_cb()
	{
		?>
		<input class="small-text" id="move" min="0" name="mtp_slider_carousel_settings[move]" placeholder="0" step="1" type="number" value="<?php echo $this->carousel['move']; ?>">
		<span class="description"><?php _e( 'Number of carousel items that should move on animation.', 'slide' ); ?></span>
		<?php
	}

	// Callback API
	
	public function callback_api()
	{
		if ( get_option( 'mtp_slider_callback_api_settings' ) == false )
		{
			add_option( 'mtp_slider_callback_api_settings' );
		}

		add_settings_section( 'callback_api', _x( 'Callback API', 'slide' ), array( $this, 'callback_api_cb' ), 'mtp_slider_callback_api_settings' );

		add_settings_field( 'start', _x( 'Start', 'slide' ), array( $this, 'start_cb' ), 'mtp_slider_callback_api_settings', 'callback_api', array( 'label_for' => 'start' ) );
		add_settings_field( 'before', _x( 'Before', 'slide' ), array( $this, 'before_cb' ), 'mtp_slider_callback_api_settings', 'callback_api', array( 'label_for' => 'before' ) );
		add_settings_field( 'after', _x( 'After', 'slide' ), array( $this, 'after_cb' ), 'mtp_slider_callback_api_settings', 'callback_api', array( 'label_for' => 'after' ) );
		add_settings_field( 'end', _x( 'End', 'slide' ), array( $this, 'end_cb' ), 'mtp_slider_callback_api_settings', 'callback_api', array( 'label_for' => 'end' ) );
		add_settings_field( 'added', _x( 'Added', 'slide' ), array( $this, 'added_cb' ), 'mtp_slider_callback_api_settings', 'callback_api', array( 'label_for' => 'added' ) );
		add_settings_field( 'removed', _x( 'Removed', 'slide' ), array( $this, 'removed_cb' ), 'mtp_slider_callback_api_settings', 'callback_api', array( 'label_for' => 'removed' ) );

		register_setting( 'mtp_slider_callback_api_settings', 'mtp_slider_callback_api_settings' );
	}

	public function callback_api_cb() {}

	public function start_cb()
	{
		?>
		<textarea class="code large-text" cols="50" id="start" name="mtp_slider_callback_api_settings[start]" rows="5"><?php echo $this->callback_api_options['start']; ?></textarea>
		<span class="description"><?php _e( 'Fires when the slider loads the first slide.', 'slide' ); ?></span>
		<?php
	}

	public function before_cb()
	{
		?>
		<textarea class="code large-text" cols="50" id="before" name="mtp_slider_callback_api_settings[before]" rows="5"><?php echo $this->callback_api_options['before']; ?></textarea>
		<span class="description"><?php _e( 'Fires asynchronously with each slider animation.', 'slide' ); ?></span>
		<?php
	}

	public function after_cb()
	{
		?>
		<textarea class="code large-text" cols="50" id="after" name="mtp_slider_callback_api_settings[after]" rows="5"><?php echo $this->callback_api_options['after']; ?></textarea>
		<span class="description"><?php _e( 'Fires after each slider animation completes.', 'slide' ); ?></span>
		<?php
	}

	public function end_cb()
	{
		?>
		<textarea class="code large-text" cols="50" id="end" name="mtp_slider_callback_api_settings[end]" rows="5"><?php echo $this->callback_api_options['end']; ?></textarea>
		<span class="description"><?php _e( 'Fires when the slider reaches the last slide (asynchronous).', 'slide' ); ?></span>
		<?php
	}

	public function added_cb()
	{
		?>
		<textarea class="code large-text" cols="50" id="added" name="mtp_slider_callback_api_settings[added]" rows="5"><?php echo $this->callback_api_options['added']; ?></textarea>
		<span class="description"><?php _e( 'Fires after a slide is added.', 'slide' ); ?></span>
		<?php
	}

	public function removed_cb()
	{
		?>
		<textarea class="code large-text" cols="50" id="removed" name="mtp_slider_callback_api_settings[removed]" rows="5"><?php echo $this->callback_api_options['removed']; ?></textarea>
		<span class="description"><?php _e( 'Fires after a slide is removed.', 'slide' ); ?></span>
		<?php
	}

	// CSS
	
	public function css()
	{
		if ( get_option( 'mtp_slider_css_settings' ) == false )
		{
			add_option( 'mtp_slider_css_settings' );
		}

		add_settings_section( 'css_settings', _x( 'CSS', 'slide' ), array( $this, 'css_settings_cb' ), 'mtp_slider_css_settings' );

		add_settings_field( 'default_css', _x( 'Default CSS', 'slide' ), array( $this, 'default_css_cb' ), 'mtp_slider_css_settings', 'css_settings', array( 'label_for' => 'default_css' ) );
		add_settings_field( 'custom_css', _x( 'Custom CSS', 'slide' ), array( $this, 'custom_css_cb' ), 'mtp_slider_css_settings', 'css_settings', array( 'label_for' => 'custom_css' ) );

		register_setting( 'mtp_slider_css_settings', 'mtp_slider_css_settings' );
	}

	public function css_settings_cb() {}

	public function default_css_cb()
	{
		?>
		<input id="default_css" name="mtp_slider_css_settings[default_css]" type="checkbox" value="1" <?php checked( $this->css_options['default_css'], 1 ); ?>>
		<?php _e( 'On', 'slide' );
	}

	public function custom_css_cb()
	{
		?>
		<textarea class="code large-text" cols="50" id="removed" name="mtp_slider_css_settings[custom_css]" rows="30"><?php echo $this->css_options['custom_css']; ?></textarea>
		<span class="description"><?php _e( 'Put your custom CSS here.', 'slide' ); ?></span>
		<?php
	}
}

new mtp_slider_admin();