<?php
/*
Plugin Name: MTP Pop-ups
Plugin URI:  http://mt-performance.net
Description: Creates newsletter pop-ups and widget 
Version:     1.2
Author:      Toni Medina
Author URI:  http://mt-performance.net
License:     GPL2
*/

$newsletter_options = get_option( 'mtp_popups_main_settings' );
require_once dirname( __FILE__ ).'/admin.php';
require_once dirname( __FILE__ ).'/widget.php';

class mtp_popups
{
	public function __construct()
	{
		if(is_admin())
			return;

		$this->main_options       = get_option( 'mtp_popups_main_settings' );
		$this->custom_options     = get_option( 'mtp_popups_custom_settings' );

		$this->custom_enabled     = isset($this->custom_options['custom_popup'])    ? $this->custom_options['custom_popup'] : false;
		$this->newsletter_enabled = isset($this->main_options['enable_newsletter']) ? $this->main_options['enable_newsletter'] : false;
		$this->widget_enabled     = isset($this->main_options['enable_widget'])     ? $this->main_options['enable_widget'] : false;

		if(!$this->custom_enabled && !$this->newsletter_enabled && !$this->widget_enabled )
			return;

		if($this->custom_enabled || $this->newsletter_enabled){
			
			$this->type = $this->newsletter_enabled ? 'newsletter' : 'custom'; // priority for newsletter

			add_action( 'wp_enqueue_scripts', array( $this, $this->type.'_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, $this->type.'_styles' ) );
			add_action( 'wp_footer',          array( $this, $this->type.'_html'));
		}

		// add widget scripts/styles only if newsletter pop-up is disabled
		if($this->widget_enabled && !$this->newsletter_enabled){

			add_action( 'wp_enqueue_scripts', array( $this, 'widget_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'widget_styles' ) );
		}

		// the main function
		add_action( 'init',  array( $this, 'newsletter_form_handler' ) );
	}

	/*** CUSTOM POP-UP ***/
	public function custom_scripts()
	{
		wp_enqueue_script( 'jquery' );

		$cookie_expire = isset($this->custom_options['cookie_expire']) ? $this->custom_options['cookie_expire'] : 1;

		if( $cookie_expire != "0"){ //add cookie plugin only if cookies are enabled
			wp_register_script( 'jquery_cookies', WP_PLUGIN_URL.'/mtp-popups/js/jquery.cookies.js', array('jquery') );
			wp_enqueue_script( 'jquery_cookies' );
		}

		wp_register_script( 'mtp_popup_script', WP_PLUGIN_URL.'/mtp-popups/js/mtp_popup.js', array('jquery') );
		wp_enqueue_script( 'mtp_popup_script' );

		$fade_delay = isset($this->custom_options['fade_delay']) ? $this->custom_options['fade_delay'] : 2000;
		
		$params = array(
		  'fade_delay' => $fade_delay,
		  'cookie_expire' => $cookie_expire,
		);
		wp_localize_script( 'mtp_popup_script', 'PopupOptCustom', $params );
	}

	public function custom_styles()
	{ 
		echo '<style type="text/css">'.$this->custom_options['custom_css'].'</style>';
	}

	public function custom_html()
	{ 
		?>
		<div class="popup_overlayer">
			<div class="popup_content">
				<?php 
					if(isset($this->custom_options['use_hook_function'])){
						do_action('mtp_popup_hook');
					} else {
						echo $this->custom_options['custom_html']; 
					}
				?>
				<a href="#" class="popup_overlayer_close">&#215;</a>
			</div>
		</div>
		<?php
	}

	/*** NEWSLETTER POP-UP ***/
	public function newsletter_scripts() {

		global $mtp_newsletter_external;

		wp_register_script( 'is_email_script', WP_PLUGIN_URL.'/mtp-popups/js/is_email.js', array('jquery') );

		$cookie_expire = isset($this->main_options['cookie_expire']) ? $this->main_options['cookie_expire'] : 1;

		if( $cookie_expire != "0" ) { //add cookie plugin only if cookies are enabled
			wp_register_script( 'jquery_cookies', WP_PLUGIN_URL.'/mtp-popups/js/jquery.cookies.js', array('jquery') );
		}

		$this->newsletter_position = $this->main_options['position'] ? $this->main_options['position'] : 'center';
		wp_register_script( 'newsletter_'.$this->newsletter_position.'_popup_script', WP_PLUGIN_URL.'/mtp-popups/js/newsletter_'.$this->newsletter_position.'.js', array('jquery-effects-shake') );

		wp_enqueue_script( 'jquery-effects-shake' );
		wp_enqueue_script( 'is_email_script' );
		wp_enqueue_script( 'jquery_cookies' );
		wp_enqueue_script( 'newsletter_'.$this->newsletter_position.'_popup_script' );

		$params = array(
		  'error_txt'     => $this->main_options['error_txt'],
		  'cookie_expire' => $cookie_expire,
		  'external_call' => $mtp_newsletter_external
		);
		wp_localize_script( 'newsletter_'.$this->newsletter_position.'_popup_script', 'PopupOpt', $params );
	}

	public function newsletter_styles() {
		$this->newsletter_position = $this->main_options['position'] ? $this->main_options['position'] : 'center';
		wp_register_style( 'newsletter_'.$this->newsletter_position.'_popup_style', WP_PLUGIN_URL.'/mtp-popups/css/newsletter_'.$this->newsletter_position.'.css' );     
		wp_enqueue_style ( 'newsletter_'.$this->newsletter_position.'_popup_style' );
	}

	public function newsletter_html()
	{
		global $mtp_newsletter_sent, $mtp_newsletter_error, $mtp_newsletter_external, $mtp_newsletter_email;

		//text & traslations
		$thankyou_txt    = $this->main_options['thankyou_txt']    ? $this->main_options['thankyou_txt']    : 'Your subscription to our list has been confirmed for the email address %subscriber_email%.<br />Thank you for subscribing!';
		$thankyou_txt    = str_replace("%subscriber_email%", $mtp_newsletter_email, $thankyou_txt);
		$disclaimer_txt  = $this->main_options['disclaimer_txt']  ? $this->main_options['disclaimer_txt']  : 'Subscribe to our list!';
		$placeholder_txt = $this->main_options['placeholder_txt'] ? $this->main_options['placeholder_txt'] : 'Your email';
		$submit_txt      = $this->main_options['submit_txt']      ? $this->main_options['submit_txt']      : 'Submit';
		//logo
		$logo_src        = $this->main_options['logo_src'];

		?>
		<div class="newsletter_overlayer">
			<div class="clickable_background"></div>
			<div class="newsletter_widget">
				<div class="newsletter_close">&#10006;</div>
				<div class="newsletter_container">
					<?php if ($mtp_newsletter_sent): ?>
						<span class="thankyou"><?php echo $thankyou_txt; ?></span>
					<?php else: ?>
						<div class="newsletter_info">
							<img src="<?php echo $logo_src; ?>">
						</div>
						<div class="newsletter_message">
							<?php if ($mtp_newsletter_error) { ?>
								<span class="error"><?php echo $mtp_newsletter_error ?></span>
							<?php } else { 
								echo $disclaimer_txt;
							} ?>
						</div>
						<form action="" class="newsletter_form" method="post">
							<input class="newsletter_email" name="mtp_newsletter_email" placeholder="<?php echo $placeholder_txt; ?>" type="text" value="">
							<input class="submitNewsletter" name="newsletter_submit" type="submit" value="<?php echo $submit_txt; ?>">
							<?php wp_nonce_field('mtp_newsletter_check_nonce','mtp_newsletter_nonce'); ?>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/*** WIDGET ***/
	public function widget_scripts() {

		global $mtp_newsletter_external;

		wp_register_script( 'is_email_script',          WP_PLUGIN_URL.'/mtp-popups/js/is_email.js', array('jquery') );
		wp_register_script( 'newsletter_widget_script', WP_PLUGIN_URL.'/mtp-popups/js/newsletter_widget.js', array('jquery-effects-shake') );

		wp_enqueue_script( 'jquery-effects-shake' );
		wp_enqueue_script( 'is_email_script' );
		wp_enqueue_script( 'newsletter_widget_script' );

		$params = array(
		  'error_txt' => $this->main_options['error_txt'],
		  'external_call' => $mtp_newsletter_external
		);
		wp_localize_script( 'newsletter_widget_script', 'PopupOpt', $params );
	}

	public function widget_styles() {
		wp_register_style( 'newsletter_widget', WP_PLUGIN_URL.'/mtp-popups/css/newsletter_widget.css' );     
		wp_enqueue_style ( 'newsletter_widget' );
	}

	/*** MAIN HANDLER ***/
	public function newsletter_form_handler()
	{
		global $mtp_newsletter_sent, $mtp_newsletter_error, $mtp_newsletter_external, $mtp_newsletter_email;

		if ( !isset($_POST['mtp_newsletter_email']) )
			return;

		if( !isset($_POST['mtp_newsletter_nonce']) || !wp_verify_nonce($_POST['mtp_newsletter_nonce'],'mtp_newsletter_check_nonce') )
			wp_die('Cheatin&rsquo; huh?');

		$mtp_newsletter_error    = false;
		$mtp_newsletter_sent     = false;
		$mtp_newsletter_external = isset($_POST['newsletter_external_call']) ? true : false;

		$error_txt = $this->main_options['error_txt'];

		if ( (trim($_POST['mtp_newsletter_email']) === '') || (!eregi('^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$',trim($_POST['mtp_newsletter_email']))) )
		{
			$mtp_newsletter_error = $error_txt;
			$has_error = true;

		} else {
			$email = trim($_POST['mtp_newsletter_email']);
		}

		if (!isset($has_error))
		{
			if (!class_exists('mailchimpSF_MCAPI'))
				require_once('miniMCAPI.class.php');

			$api     = $this->main_options['apikey'];
			$listId  = $this->main_options['listid'];
			$groupId = $this->main_options['groupid'];
			$email_type = 'html';
			$merge = array(
				'website' => get_bloginfo('url'),
				'GROUPINGS'=>array(
					array('id'=>$groupId, 'groups' => 'inscrito')
				)
			);

			$api = new mailchimpSF_MCAPI($api);
			$retval = $api->listSubscribe( $listId, $email, $merge, $email_type, $double_optin=false, $update_existing=true, $replace_interests=true, $send_welcome=true);
			if (!$retval) {
				switch($api->errorCode) {
					default:
						$mtp_newsletter_error = $api->errorCode.": Subscription failed. Please try again.";
						break;
				}
			} else {
				$mtp_newsletter_sent = true;
				$mtp_newsletter_email = $email;
				
				//fire newsletter pop-up message if disabled
				if( $this->widget_enabled && !$this->newsletter_enabled )
					add_action( 'wp_footer', array( $this, 'newsletter_html'));
			}
		}
	}
}
new mtp_popups();

// create a newsletter form for plugins and widgets
// need to activate the widget option on setting page
function mtp_newsletter_form($disclaimer=true){

	global $newsletter_options;

	$disclaimer_txt  = $newsletter_options['disclaimer_txt']  ? $newsletter_options['disclaimer_txt']  : 'Subscribe to our list!';
	$placeholder_txt = $newsletter_options['placeholder_txt'] ? $newsletter_options['placeholder_txt'] : 'Your email';
	$submit_txt      = $newsletter_options['submit_txt']      ? $newsletter_options['submit_txt']      : 'Submit';

	if( !isset($newsletter_options['enable_widget']) || !$newsletter_options['enable_widget'] ) { 

		//display error message only for authorized users
		if(current_user_can('menage_options'))
			echo '<div class="widget_error"><span class="error">widget disabled</span></div>';

	} else { 
		
		if($disclaimer) { ?>
			<p class="newsletter_message_widget"><?php echo $disclaimer_txt; ?></p>
		<?php } ?>
		<form action="" class="newsletter_form" method="post">
			<input class="newsletter_email" name="mtp_newsletter_email" placeholder="<?php echo $placeholder_txt; ?>" type="text" value="">
			<input class="submitNewsletter" name="newsletter_submit" type="submit" value="<?php echo $submit_txt; ?>">
			<input name="newsletter_external_call" type="hidden" value="true">
			<?php wp_nonce_field('mtp_newsletter_check_nonce','mtp_newsletter_nonce'); ?>
		</form>
		<div class="widget_error"></div>
		<?php
	}
}