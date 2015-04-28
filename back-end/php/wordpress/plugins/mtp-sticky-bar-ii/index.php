<?php
/*
Plugin Name: MTP Sticky Bar II
Plugin URI: http://mt-performance.net
Description: Creates a sticky bar on top of your site.
Version: 1.0
Author: Toni Medina
Author URI: http://tonimedina.me
License: GPL2
*/

// MTP Sticky Bar II
class mtp_sticky_bar_ii
{
	public $options;

	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
	}

	// Initialize
	public function init()
	{
		$this->actions();
		$this->options = get_option( 'mtp_sticky_bar_ii' );
	}

	// WordPress & Thesis Actions
	public function actions()
	{
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_init', array( $this, 'options_regadd' ) );

		if ( get_current_theme() == 'Thesis' )
		{
			add_action( 'thesis_hook_before_html', array( $this, 'sticky_bar' ), 10 );
		}
		else
		{
		 	add_action( 'wp_head', array( $this, 'sticky_bar' ), 10 );
		}

		add_action( 'thesis_hook_after_html', array( $this, 'scripts' ) );
	}

	// Add Options Page "Settings > Sticky Bar II"
	public function add_options_page()
	{
		add_options_page( 'MTP Sticky Bar II Options Page', 'Sticky Bar II', 'edit_theme_options', __FILE__, array( $this, 'add_options_page_cb' ) );
	}

	// Add Options Page Callback
	public function add_options_page_cb()
	{ ?>
		<div class="wrap">

			<?php screen_icon(); ?>

			<h2>MTP Sticky Bar II Options Page</h2>

			<form action="options.php" enctype="multipart/form-data" method="post">

				<?php
				settings_fields( 'mtp_sticky_bar_ii_group' );
				do_settings_sections( __FILE__ );
				submit_button();
				?>

			</form>

		</div><!-- END .wrap -->
	<?php }

	// Register Settings And Add Fields
	public function options_regadd()
	{
		register_setting( 'mtp_sticky_bar_ii_group', 'mtp_sticky_bar_ii' );

		// Languages
		add_settings_section( 'languages', 'Language Settings', array( $this, 'languages_cb' ), __FILE__ );
		add_settings_field( 'language', 'Choose The Language', array( $this, 'language_cb' ), __FILE__, 'languages', array( 'label_for' => 'language' ) );

		// Area One
		add_settings_section( 'area_one', 'Area One Settings', array( $this, 'area_one_cb' ), __FILE__ );
		add_settings_field( 'logo_link', 'Logo Link', array( $this, 'logo_link_cb' ), __FILE__, 'area_one', array( 'label_for' => 'logo_link' ) );
		add_settings_field( 'site_title', 'Site Title', array( $this, 'site_title_cb' ), __FILE__, 'area_one', array( 'label_for' => 'site_title' ) );
		add_settings_field( 'site_description', 'Site Description', array( $this, 'site_description_cb' ), __FILE__, 'area_one', array( 'label_for' => 'site_description' ) );

		// Area Two
		add_settings_section( 'area_two', 'Area Two Settings', array( $this, 'area_two_cb' ), __FILE__ );
		add_settings_field( 'more_link', 'More Link', array( $this, 'more_link_cb' ), __FILE__, 'area_two', array( 'label_for' => 'more_link' ) );
		add_settings_field( 'more_text', 'More Text', array( $this, 'more_text_cb' ), __FILE__, 'area_two', array( 'label_for' => 'more_text' ) );

		// Area Three
		add_settings_section( 'area_three', 'Area Three Settings', array( $this, 'area_three_cb' ), __FILE__ );
		add_settings_field( 'shop_link', 'Shop Link', array( $this, 'shop_link_cb' ), __FILE__, 'area_three', array( 'label_for' => 'shop_link' ) );
		add_settings_field( 'shop_text', 'Shop Text', array( $this, 'shop_text_cb' ), __FILE__, 'area_three', array( 'label_for' => 'shop_text' ) );
		add_settings_field( 'forum_link', 'Forum Link', array( $this, 'forum_link_cb' ), __FILE__, 'area_three', array( 'label_for' => 'forum_link' ) );
		add_settings_field( 'forum_text', 'Forum Text', array( $this, 'forum_text_cb' ), __FILE__, 'area_three', array( 'label_for' => 'forum_text' ) );
		add_settings_field( 'newsletter_text', 'Newsletter Text', array( $this, 'newsletter_text_cb' ), __FILE__, 'area_three', array( 'label_for' => 'newsletter_text' ) );

		// Area Four
		add_settings_section( 'area_four', 'Area Four Settings', array( $this, 'area_four_cb' ), __FILE__ );
		add_settings_field( 'facebook_link', 'Facebook Link', array( $this, 'facebook_link_cb' ), __FILE__, 'area_four', array( 'label_for' => 'facebook_link' ) );
		add_settings_field( 'twitter_link', 'Twitter Link', array( $this, 'twitter_link_cb' ), __FILE__, 'area_four', array( 'label_for' => 'twitter_link' ) );
		add_settings_field( 'google_link', 'Google Link', array( $this, 'google_link_cb' ), __FILE__, 'area_four', array( 'label_for' => 'google_link' ) );

		// Area Five
		add_settings_section( 'area_five', 'Area Five Settings', array( $this, 'area_five_cb' ), __FILE__ );
		add_settings_field( 'subject', 'Subject', array( $this, 'subject_cb' ), __FILE__, 'area_five', array( 'label_for' => 'subject' ) );
		add_settings_field( 'message', 'Message', array( $this, 'message_cb' ), __FILE__, 'area_five', array( 'label_for' => 'message' ) );
	}

	// Languages
	public function languages_cb(){}

	public function language_cb()
	{
		$ops = array( 'Portuguese', 'Spanish' );

		echo "<select id='language' name='mtp_sticky_bar_ii[language]'>";

			foreach ($ops as $key)
			{
				$selected = ($this->options['language'] === $key) ? "selected='selected'" : "";

				echo "<option value='$key' $selected>$key</option>";
			}

		echo "</select>";
	}

	// Area One
	public function area_one_cb(){}

	public function logo_link_cb()
	{
		echo "<input class='code regular-text' id='logo_link' name='mtp_sticky_bar_ii[logo_link]' type='url' value='{$this->options['logo_link']}'>";
	}

	public function site_title_cb()
	{
		echo "<input class='regular-text' id='site_title' name='mtp_sticky_bar_ii[site_title]' type='text' value='{$this->options['site_title']}'>";
	}

	public function site_description_cb()
	{
		echo "<textarea class='code large-text' cols='50' id='site_description' name='mtp_sticky_bar_ii[site_description]' rows='10'>{$this->options['site_description']}</textarea>";
	}

	// Area Two
	public function area_two_cb(){}

	public function more_link_cb()
	{
		echo "<input class='code regular-text' id='more_link' name='mtp_sticky_bar_ii[more_link]' type='url' value='{$this->options['more_link']}'>";
	}

	public function more_text_cb()
	{
		echo "<input class='regular-text' id='more_text' name='mtp_sticky_bar_ii[more_text]' type='text' value='{$this->options['more_text']}'>";
	}

	// Area Three
	public function area_three_cb(){}

	public function shop_link_cb()
	{
		echo "<input class='code regular-text' id='shop_link' name='mtp_sticky_bar_ii[shop_link]' type='url' value='{$this->options['shop_link']}'>";
	}

	public function shop_text_cb()
	{
		echo "<input class='regular-text' id='shop_text' name='mtp_sticky_bar_ii[shop_text]' type='text' value='{$this->options['shop_text']}'>";
	}

	public function forum_link_cb()
	{
		echo "<input class='code regular-text' id='forum_link' name='mtp_sticky_bar_ii[forum_link]' type='url' value='{$this->options['forum_link']}'>";
	}

	public function forum_text_cb()
	{
		echo "<input class='regular-text' id='forum_text' name='mtp_sticky_bar_ii[forum_text]' type='text' value='{$this->options['forum_text']}'>";
	}

	public function newsletter_text_cb()
	{
		echo "<input class='regular-text' id='newsletter_text' name='mtp_sticky_bar_ii[newsletter_text]' type='text' value='{$this->options['newsletter_text']}'>";
	}

	// Area Four
	public function area_four_cb(){}

	public function facebook_link_cb()
	{
		echo "<input class='code regular-text' id='facebook_link' name='mtp_sticky_bar_ii[facebook_link]' type='url' value='{$this->options['facebook_link']}'>";
	}

	public function twitter_link_cb()
	{
		echo "<input class='code regular-text' id='twitter_link' name='mtp_sticky_bar_ii[twitter_link]' type='url' value='{$this->options['twitter_link']}'>";
	}

	public function google_link_cb()
	{
		echo "<input class='code regular-text' id='google_link' name='mtp_sticky_bar_ii[google_link]' type='url' value='{$this->options['google_link']}'>";
	}

	// Area Five
	public function area_five_cb(){}

	public function subject_cb()
	{
		echo "<input class='regular-text' id='subject' name='mtp_sticky_bar_ii[subject]' type='text' value='{$this->options['subject']}'>";
	}

	public function message_cb()
	{
		echo "<textarea class='code large-text' cols='50' id='message' name='mtp_sticky_bar_ii[message]' rows='10'>{$this->options['message']}</textarea>";
	}

	// The HTML For The Sticky Bar
	public function sticky_bar()
	{ ?>
		<div class="mtp_sticky_bar_ii_wrapper">

			<div class="mtp_sticky_bar_ii_container">

				<ul class="mtp_sticky_bar_ii_one">

					<li><a class="ir l" href="<?php echo ($this->options['logo_link']) == '' ? '#' : $this->options['logo_link']; ?>">&nbsp;</a></li>

					<?php if ( $this->options['site_title'] ) : ?>
					<li><a href="<?php echo get_bloginfo( 'url' ); ?>"><?php echo ($this->options['site_title']) == '' ? get_bloginfo( 'name' ) : $this->options['site_title']; ?></a>

						<?php if ( !empty( $this->options['site_description'] ) ) : ?>

						<ul class="sub-menu">

							<li><p><?php echo $this->options['site_description']; ?></p></li>

						</ul><!-- END .sub-menu -->

						<?php endif; ?>

					</li>
					<?php endif; ?>

				</ul><!-- END .mtp_sticky_bar_ii_one -->

				<?php if ( $this->options['more_link'] || $this->options['more_text'] ) : ?>
				<ul class="mtp_sticky_bar_ii_two">

					<li><a href="<?php echo $this->options['more_link']; ?>"><span class="ir n">&nbsp;</span> <?php echo $this->options['more_text']; ?></a></li>

				</ul><!-- END .mtp_sticky_bar_ii_two -->
				<?php endif; ?>

				<?php if( $this->options['shop_link'] || $this->options['shop_text'] || $this->options['forum_link'] || $this->options['forum_text'] || $this->options['newsletter_text'] ) : ?>
				<ul class="mtp_sticky_bar_ii_three">

					<?php if ( $this->options['shop_link'] || $this->options['shop_text'] ) : ?>
					<li><a href="<?php echo $this->options['shop_link']; ?>" target="_blank"><?php echo $this->options['shop_text']; ?></a></li>
					<?php endif; if ( $this->options['forum_link'] || $this->options['forum_text'] ) : ?>
					<li><a href="<?php echo $this->options['forum_link']; ?>"><?php echo $this->options['forum_text']; ?></a></li>
					<?php endif; if ( $this->options['newsletter_text'] ) : ?>
					<li class="n"><a href="#"><?php echo $this->options['newsletter_text']; ?></a>

						<?php require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

						if ( is_plugin_active( 'mailchimp/mailchimp.php' ) ) { ?>

						<ul class="sub-menu">

							<li><?php the_widget( 'mailchimpSF_Widget' ); ?></li>
							
						</ul><!-- END .sub-menu -->

						<?php } ?>

					</li>
					<?php endif; ?>

				</ul><!-- END .mtp_sticky_bar_ii_three -->
				<?php endif; ?>

				<?php if ( $this->options['facebook_link'] || $this->options['twitter_link'] || $this->options['google_link'] ) : ?>
				<ul class="mtp_sticky_bar_ii_four">

					<?php if ( !empty($this->options['facebook_link'] ) ) : ?>
					<li><a class="ir f" href="<?php echo $this->options['facebook_link']; ?>">Facebook</a></li>
					<?php endif; if ( !empty($this->options['twitter_link'] ) ) : ?>
					<li><a class="ir t" href="<?php echo $this->options['twitter_link']; ?>">Twitter</a></li>
					<?php endif; if ( !empty($this->options['google_link'] ) ) : ?>
					<li><a class="ir g" href="<?php echo $this->options['google_link']; ?>">Google+</a></li>
					<?php endif;?>

				</ul><!-- END .mtp_sticky_bar_ii_four -->
				<?php endif; ?>

				<?php if ( $this->options['subject'] || $this->options['message'] ) : ?>
				<ul class="mtp_sticky_bar_ii_five">

					<li><a class="ir" href="mailto:?subject=<?php echo $this->options['subject']; ?>&body=<?php echo $this->options['message']; ?>">Invite Friends</a></li>

				</ul><!-- END .mtp_sticky_bar_ii_five -->
				<?php endif; ?>

				<div class="clear"></div><!-- END .clear -->

			</div><!-- END .mtp_sticky_bar_ii_container -->

		</div><!-- END .mtp_sticky_bar_ii_wrapper -->
	<?php }

	// CSS & JS
	public function scripts()
	{ ?>
		<style type="text/css">
		/* Wrapper */
		.mtp_sticky_bar_ii_wrapper {
			background: #4e4e4e;
			color: #fff;
			position: fixed;
			width: 100%;
			z-index: 999;

			background: -moz-linear-gradient(left, #4e4e4e 0%, #4e4e4e 50%, #e6e6e6 50%, #e6e6e6 100%);
			background: -webkit-gradient(linear, left top, right top, color-stop(0%,#4e4e4e), color-stop(50%,#4e4e4e), color-stop(50%,#e6e6e6), color-stop(100%,#e6e6e6));
			background: -webkit-linear-gradient(left, #4e4e4e 0%,#4e4e4e 50%,#e6e6e6 50%,#e6e6e6 100%);
			background: -o-linear-gradient(left, #4e4e4e 0%,#4e4e4e 50%,#e6e6e6 50%,#e6e6e6 100%);
			background: -ms-linear-gradient(left, #4e4e4e 0%,#4e4e4e 50%,#e6e6e6 50%,#e6e6e6 100%);
			background: linear-gradient(to right, #4e4e4e 0%,#4e4e4e 50%,#e6e6e6 50%,#e6e6e6 100%);
		}
		.mtp_sticky_bar_ii_wrapper * {
			border: 0;
			font: inherit;
			font-size: 100%;
			margin: 0;
			padding: 0;
			vertical-align: baseline;
		}
		.mtp_sticky_bar_ii_wrapper a {
			border: none;

			-webkit-transition: none;
			   -moz-transition: none;
			   	-ms-transition: none;
			   	 -o-transition: none;
			   	 	transition: none;
		}

		/* Container */
		.mtp_sticky_bar_ii_container { margin: 0 auto; }

		.mtp_sticky_bar_ii_container > ul {
			display: inline;
			float: left;
			font: normal 16px/1.5 'Times', Georgia, Times New Roman, serif;
			height: 35px;
			list-style: none;
			position: relative;
		}

		.mtp_sticky_bar_ii_container > ul > li {
			display: inline;
			float: left;
			list-style: none;
		}

		.mtp_sticky_bar_ii_container > ul > li > a {
			display: block;
			padding: 5px 10px 6px;
			text-decoration: none;
		}

		/* Globals */
		.mtp_sticky_bar_ii_one { background: #4e4e4e; }

		.mtp_sticky_bar_ii_two,
		.mtp_sticky_bar_ii_three,
		.mtp_sticky_bar_ii_four,
		.mtp_sticky_bar_ii_five { background: #e6e6e6; }

		.mtp_sticky_bar_ii_three > li,
		.mtp_sticky_bar_ii_four li:first-child,
		.mtp_sticky_bar_ii_five li { background-position: 0 -539px; }
		.mtp_sticky_bar_ii_three > li,
		.mtp_sticky_bar_ii_five li { padding: 0 5px; }

		/* One */
		.mtp_sticky_bar_ii_one { margin-right: 10px; }
		.mtp_sticky_bar_ii_one li:first-child a {
			background-position: 10px -366px;
			width: 23px;
		}
		.mtp_sticky_bar_ii_one li:first-child:hover a { background-position: -63px -366px; }
		.mtp_sticky_bar_ii_one > li > a {
			color: #fff;
			font-style: italic;
			font-weight: bold;
		}

		/* Two */
		.mtp_sticky_bar_ii_two { padding-right: 120px !important; }
		.mtp_sticky_bar_ii_two a {
			color: #9e7c3c;
			font-style: italic;
			font-weight: bold;
			padding-left: 82px !important;
			text-transform: lowercase;
		}
		.mtp_sticky_bar_ii_two a:hover { color: #825f27; }
		.mtp_sticky_bar_ii_two a:hover span { background-position: -111px -446px; }
		.mtp_sticky_bar_ii_two span {
			background-position: 0 -446px;
			display: block;
			height: 51px;
			left: 10px;
			position: absolute;
			top: 0;
			width: 62px;
		}

		/* Three */
		.mtp_sticky_bar_ii_three { font: normal 13px/1.5 'Helvetica', Arial, 'Helvetica Neue', sans-serif !important; }
		.mtp_sticky_bar_ii_three > li > a {
			color: #4e4e4e;
			padding: 8px 10px 8px !important;
		}
		.mtp_sticky_bar_ii_three > li > a:hover { text-decoration: underline; }
		.mtp_sticky_bar_ii_three > li.n > a:hover {
			background: #4e4e4e;
			color: #fff;
			margin-top: 5px;
			padding-top: 3px !important;
			text-decoration: none;
		}
		.mtp_sticky_bar_ii_three > li:first-child { background: none; }

		/* Four */
		.f, .t, .g {
			height: 24px;
			margin: 0;
			padding: 6px 5px 5px !important;
			width: 24px;
		}
		.mtp_sticky_bar_ii_four > li:first-child { padding-left: 5px; }
		.mtp_sticky_bar_ii_four > li:last-child { padding-right: 5px; }
		.f { background-position: 5px -147px; }
		.f:hover { background-position: -68px -147px; }
		.t { background-position: 5px -220px; }
		.t:hover { background-position: -68px -220px; }
		.g { background-position: 5px -293px; }
		.g:hover { background-position: -68px -293px; }

		/* Five */
		.mtp_sticky_bar_ii_five a {
			background-position: 10px -71px;
			height: 24px;
			width: 113px;
		}
		.mtp_sticky_bar_ii_five a:hover { background-position: -153px -71px }
				
		/* Classes */
		.mtp_sticky_bar_ii_container .sub-menu {
			background: #4e4e4e;
			display: none;
			height: 0;
			opacity: 0;
			overflow: hidden;
			padding: 10px !important;
			position: absolute;
			top: 100%;
			visibility: hidden;
			width: 0;
		}

		.ir {
			background-color: transparent;
			border: 0;
			overflow: hidden;
			text-indent: -9999px;
		}

		.ir:before {
			content: "";
			display: block;
			height: 100%;
			width: 0;
		}

		.no-js .mtp_sticky_bar_ii_container { width: 1082px; }

		.mtp_sticky_bar_ii_container a.ir,
		.mtp_sticky_bar_ii_container span.ir,
		.mtp_sticky_bar_ii_three > li,
		.mtp_sticky_bar_ii_four li:first-child,
		.mtp_sticky_bar_ii_five li {
			background-image: url('<?php echo ($this->options['language']) == 'Portuguese' ? plugins_url().'/mtp-sticky-bar-ii/sprite-pt-PT.png' : plugins_url().'/mtp-sticky-bar-ii/sprite-es-ES.png'; ?>');
			background-attachment: scroll;
			background-repeat: no-repeat;
		}

		.mtp_sticky_bar_ii_container .sub-menu > li { list-style: none; }

		.mtp_sticky_bar_ii_container li:hover .sub-menu {
			display: block;
			height: auto;
			opacity: 1;
			overflow: visible;
			visibility: visible;
			width: 280px;
		}

		.mtp_sticky_bar_ii_three > li.n:hover > a {
			background: #4e4e4e !important;
			color: #fff;
			margin-top: 5px;
			padding: 3px 10px 8px !important;
		}

		.clear {
			clear: both;
			display: block;
			height: 0;
			overflow: hidden;
			visibility: hidden;
			width: 0;
		}

		#header_area { padding-top: 35px; }
		</style>

		<script type="text/javascript">
		;(function($){
			$('.mtp_sticky_bar_ii_container').each(function(){
				var $this  = $(this),
					$width = 0;

				$this.find('> ul').each(function(){
					var $self = $(this);

					$width += $self.outerWidth();
				});

				$this.css({
					'max-width': $width+20,
					'min-width': $width+20,
					'width'    : $width+20
				});
			});
		})(jQuery);
		</script>
	<?php }
}

new mtp_sticky_bar_ii();

?>
