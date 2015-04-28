<?php

if ( !defined( 'ABSPATH' ) )
	exit();

class mtp_popups_admin
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init()
	{
		$this->actions();
		$this->main_options = get_option( 'mtp_popups_main_settings' );
		$this->custom_options  = get_option( 'mtp_popups_custom_settings' );
	}

	public function actions()
	{
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_init', array( $this, 'main_settings' ) );
		add_action( 'admin_init', array( $this, 'custom' ) );
	}

	public function add_options_page()
	{
		add_options_page( _x( 'MTP Pop-ups Options', 'popups' ), _x( 'MTP Pop-ups', 'popups' ), 'edit_theme_options', __FILE__, array( $this, 'add_options_page_cb' ) );
	}

	public function add_options_page_cb( $active_tab = '' )
	{
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'main_settings';  
		?>
		<div class="wrap">
			<?php
			screen_icon();
			//settings_errors();
			?>
			<h2 class="nav-tab-wrapper">
				<?php _e( 'MTP Pop-ups', 'popups' ); ?>
				<a class="nav-tab <?php echo $active_tab == 'main_settings' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-popups/admin.php&tab=main_settings"><?php _e( 'Newsletter', 'popups' ); ?></a>
				<a class="nav-tab <?php echo $active_tab == 'custom_settings' ? 'nav-tab-active' : ''; ?>" href="?page=mtp-popups/admin.php&tab=custom_settings"><?php _e( 'Custom', 'popups' ); ?></a>
			</h2>

			<form action="options.php" enctype="multipart/form-data" method="post">
				<?php
				switch ( $active_tab ) {

					case 'custom_settings':
						settings_fields( 'mtp_popups_custom_settings' );
						do_settings_sections( 'mtp_popups_custom_settings' );
						break;

					default:
						settings_fields( 'mtp_popups_main_settings' );
						do_settings_sections( 'mtp_popups_main_settings' );
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
		if ( get_option( 'mtp_popups_main_settings' ) == false )
		{
			add_option( 'mtp_popups_main_settings' );
		}

		add_settings_section( 'main_settings', _x( 'DEFAULT POP-UP OPTIONS', 'popups' ), array( $this, 'main_settings_cb' ), 'mtp_popups_main_settings' );
		add_settings_field( 'enable_newsletter', _x( 'Enable Newsletter Pop-up', 'popups' ), array( $this, 'enable_newsletter_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'enable_newsletter' ) );
		add_settings_field( 'enable_widget', _x( 'Enable Newsletter Widget', 'popups' ), array( $this, 'enable_widget_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'enable_widget' ) );
		add_settings_field( 'apikey', _x( 'Mailchimp APIkey', 'popups' ), array( $this, 'apikey_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'apikey' ) );
		add_settings_field( 'listid', _x( 'Mailchimp ListID', 'popups' ), array( $this, 'listid_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'listid' ) );
		add_settings_field( 'groupid', _x( 'Mailchimp GroupID', 'popups' ), array( $this, 'groupid_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'groupid' ) );
		add_settings_field( 'position', _x( 'Position', 'popups' ), array( $this, 'position_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'position' ) );
		add_settings_field( 'logo_src', _x( 'Logo SRC', 'popups' ), array( $this, 'logo_src_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'logo_src' ) );
		add_settings_field( 'disclaimer_txt', _x( 'Disclaimer text', 'popups' ), array( $this, 'disclaimer_txt_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'disclaimer_txt' ) );
		add_settings_field( 'thankyou_txt', _x( 'Thankyou text', 'popups' ), array( $this, 'thankyou_txt_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'thankyou_txt' ) );
		add_settings_field( 'error_txt', _x( 'Error text', 'popups' ), array( $this, 'error_txt_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'error_txt' ) );
		add_settings_field( 'placeholder_txt', _x( 'Placeholder text', 'popups' ), array( $this, 'placeholder_txt_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'placeholder_txt' ) );
		add_settings_field( 'submit_txt', _x( 'Submit text', 'popups' ), array( $this, 'submit_txt_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'submit_txt' ) );
		add_settings_field( 'cookie_expire', _x( 'Cookies Expire', 'popups' ), array( $this, 'newsletter_cookie_expire_cb' ), 'mtp_popups_main_settings', 'main_settings', array( 'label_for' => 'cookie_expire' ) );

		register_setting( 'mtp_popups_main_settings', 'mtp_popups_main_settings' );
	}

	public function main_settings_cb() {}

	public function enable_newsletter_cb()
	{
		$checked = isset($this->main_options['enable_newsletter']) ? $this->main_options['enable_newsletter'] : false;
		?>
		<input id="enable_newsletter" name="mtp_popups_main_settings[enable_newsletter]" type="checkbox" value="1" <?php checked( $checked, 1 ); ?>>
		<span class="description"><?php _e( 'On', 'popups' ); ?></span>
		<?php
	}

	public function enable_widget_cb()
	{
		$checked = isset($this->main_options['enable_widget']) ? $this->main_options['enable_widget'] : false;
		?>
		<input id="enable_widget" name="mtp_popups_main_settings[enable_widget]" type="checkbox" value="1" <?php checked( $checked, 1 ); ?>>
		<span class="description"><?php _e( 'Enable this option only if a widget newsletter form is needed (required on template 4 blogs and/or the newsletter option in Stickybar plugin is enabled).', 'popups' ); ?></span>
		<?php
	}

	public function apikey_cb()
	{
		$apikey = isset($this->main_options['apikey']) ? $this->main_options['apikey'] : false;
		?>
		<input class="code regular-text" id="apikey" name="mtp_popups_main_settings[apikey]" type="text" value="<?php echo $apikey; ?>">
		<span class="description"><?php _e( '<a href="http://admin.mailchimp.com/account/api-key-popup" target="_blank">get your API Key here</a>', 'popups' ); ?></span>
		<?php
	}

	public function listid_cb()
	{
		$listid = isset($this->main_options['listid']) ? $this->main_options['listid'] : false;
		?>
		<input class="code regular-text" id="listid" name="mtp_popups_main_settings[listid]" type="text" value="<?php echo $listid; ?>">
		<?php
	}

	public function groupid_cb()
	{
		$groupid = isset($this->main_options['groupid']) ? $this->main_options['groupid'] : false;
		?>
		<input class="code small-text" id="groupid" name="mtp_popups_main_settings[groupid]" type="number" value="<?php echo $groupid; ?>">
		<?php
	}

	public function disclaimer_txt_cb()
	{
		$text = isset($this->main_options['disclaimer_txt']) ? $this->main_options['disclaimer_txt'] : false;
		?>
		<textarea class="code large-text" id="disclaimer_txt" name="mtp_popups_main_settings[disclaimer_txt]" rows="3"><?php echo $text; ?></textarea>
		<?php
	}

	public function thankyou_txt_cb()
	{
		$text = isset($this->main_options['thankyou_txt']) ? $this->main_options['thankyou_txt'] : false;
		?>
		<textarea class="code large-text" id="thankyou_txt" name="mtp_popups_main_settings[thankyou_txt]" rows="2"><?php echo $text; ?></textarea><br />
		<span class="description"><?php _e( 'Use <code>%subscriber_email%</code> code to show the subscriber e-mail inside the message.', 'popups' ); ?></span>
		<?php
	}

	public function error_txt_cb()
	{
		$text = isset($this->main_options['error_txt']) ? $this->main_options['error_txt'] : false;
		?>
		<textarea class="code large-text" id="error_txt" name="mtp_popups_main_settings[error_txt]" rows="2"><?php echo $text; ?></textarea>
		<?php
	}

	public function logo_src_cb()
	{
		$src = isset($this->main_options['logo_src']) ? $this->main_options['logo_src'] : false;
		?>
		<input class="code large-text" id="logo_src" name="mtp_popups_main_settings[logo_src]" type="text" value="<?php echo $src; ?>">
		<?php
	}

	public function placeholder_txt_cb()
	{
		$text = isset($this->main_options['placeholder_txt']) ? $this->main_options['placeholder_txt'] : false;
		?>
		<input class="code regular-text" id="placeholder_txt" name="mtp_popups_main_settings[placeholder_txt]" type="text" value="<?php echo $text; ?>">
		<?php
	}

	public function submit_txt_cb()
	{
		$text = isset($this->main_options['submit_txt']) ? $this->main_options['submit_txt'] : false;
		?>
		<input class="code regular-text" id="submit_txt" name="mtp_popups_main_settings[submit_txt]" type="text" value="<?php echo $text; ?>">
		<?php
	}

	public function position_cb()
	{
		$options = array( 'center', 'bottom' );
		?>
		<select id="position" name="mtp_popups_main_settings[position]">
			<?php
			foreach ( $options as $option ) :
			$selected = ( $this->main_options['position'] === $option ) ? 'selected="selected"' : '';
			?>
			<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php _e( 'Pop-up Position', 'popups' ); ?></span>
		<?php
	}

	public function newsletter_cookie_expire_cb()
	{
		$expire = isset($this->main_options['cookie_expire']) ? $this->main_options['cookie_expire'] : false;
		?>
		<input class="code small-text" placeholder="1" id="cookie_expire" name="mtp_popups_main_settings[cookie_expire]" type="number" value="<?php echo $expire; ?>">
		<span class="description"><?php _e( 'Set the cookie expiration date on close event. Set 0 to disable cookies.', 'popups' ); ?></span>&nbsp;<code>default = 1 day</code>
		<?php
	}

	// CUSTOM
	
	public function custom()
	{
		if ( get_option( 'mtp_popups_custom_settings' ) == false )
		{
			add_option( 'mtp_popups_custom_settings' );
		}

		add_settings_section( 'custom_settings', _x( 'CUSTOM POP-UP OPTIONS', 'popups' ), array( $this, 'css_settings_cb' ), 'mtp_popups_custom_settings' );

		add_settings_field( 'custom_popup', _x( 'Use Custom Pop-Up', 'popups' ), array( $this, 'custom_popup_cb' ), 'mtp_popups_custom_settings', 'custom_settings', array( 'label_for' => 'custom_popup' ) );
		add_settings_field( 'use_hook_function', _x( 'Use hook function', 'popups' ), array( $this, 'use_hook_function_cb' ), 'mtp_popups_custom_settings', 'custom_settings', array( 'label_for' => 'use_hook_function' ) );
		add_settings_field( 'custom_html', _x( 'Custom HTML', 'popups' ), array( $this, 'custom_html_cb' ), 'mtp_popups_custom_settings', 'custom_settings', array( 'label_for' => 'custom_html' ) );
		add_settings_field( 'custom_css', _x( 'Custom CSS', 'popups' ), array( $this, 'custom_css_cb' ), 'mtp_popups_custom_settings', 'custom_settings', array( 'label_for' => 'custom_css' ) );
		add_settings_field( 'cookie_expire', _x( 'Cookies Expire', 'popups' ), array( $this, 'cookie_expire_cb' ), 'mtp_popups_custom_settings', 'custom_settings', array( 'label_for' => 'cookie_expire' ) );
		add_settings_field( 'fade_delay', _x( 'Fade Delay', 'popups' ), array( $this, 'fade_delay_cb' ), 'mtp_popups_custom_settings', 'custom_settings', array( 'label_for' => 'fade_delay' ) );

		register_setting( 'mtp_popups_custom_settings', 'mtp_popups_custom_settings' );
	}

	public function css_settings_cb() {}

	public function custom_popup_cb()
	{
		$checked = isset($this->custom_options['custom_popup']) ? $this->custom_options['custom_popup'] : false;
		$newsletter_enabled = isset($this->main_options['enable_newsletter']) ? $this->main_options['enable_newsletter'] : false;
		?>
		<input id="custom_popup" name="mtp_popups_custom_settings[custom_popup]" type="checkbox" value="1" <?php checked( $checked, 1 ); ?>>
		<span class="description"><?php _e( 'On', 'popups' ); ?></span>
		<?php
	}

	public function use_hook_function_cb()
	{
		$checked = isset($this->custom_options['use_hook_function']) ? $this->custom_options['use_hook_function'] : false;
		?>
		<input id="hook_function" name="mtp_popups_custom_settings[use_hook_function]" type="checkbox" value="1" <?php checked( $checked, 1 ); ?>>
		<span class="description"><?php _e( 'Use the <code>mtp_popup_hook</code> action to add custom HTML content', 'popups' ); ?></span>
		<?php
	}

	public function custom_html_cb()
	{
		$html = isset($this->custom_options['custom_html']) ? $this->custom_options['custom_html'] : false;
		?>
		<textarea class="code large-text" cols="50" id="removed" name="mtp_popups_custom_settings[custom_html]" rows="10"><?php echo $html; ?></textarea>
		<span class="description"><?php _e( 'Put your custom HTML here.', 'popups' ); ?></span>
		<?php
	}

	public function custom_css_cb()
	{
		$css = isset($this->custom_options['custom_css']) ? $this->custom_options['custom_css'] : false;
		?>
		<textarea class="code large-text" cols="50" id="removed" name="mtp_popups_custom_settings[custom_css]" rows="10"><?php echo $css; ?></textarea>
		<span class="description"><?php _e( 'Put your custom CSS here.', 'popups' ); ?></span>
		<?php
	}
	public function cookie_expire_cb()
	{
		$expire = isset($this->custom_options['cookie_expire']) ? $this->custom_options['cookie_expire'] : false;
		?>
		<input class="code small-text" placeholder="1" id="cookie_expire" name="mtp_popups_custom_settings[cookie_expire]" type="number" value="<?php echo $expire; ?>">
		<span class="description"><?php _e( 'Set the cookie expiration date on close event. Set 0 to disable cookies.', 'popups' ); ?></span>&nbsp;<code>default = 1 day</code>
		<?php
	}
	public function fade_delay_cb()
	{
		$delay = isset($this->custom_options['fade_delay']) ? $this->custom_options['fade_delay'] : false;
		?>
		<input class="code small-text" placeholder="2000" id="fade_delay" name="mtp_popups_custom_settings[fade_delay]" type="number" value="<?php echo $delay; ?>">
		<span class="description"><?php _e( 'Set the delay time on show event. Set 0 to disable delay.', 'popups' ); ?></span>&nbsp;<code>default = 2000 ms</code>
		<?php
	}
}

new mtp_popups_admin();