<?php
/*
Plugin Name: 101 MTP Sticky Bar
Plugin URI: http://mt-performance.net
Description: Creates a sticky bar on top of your website.
Version: 1.0
Author: Toni Medina
Author URI: http://tonimedina.me
License: GPL2
*/
class mtp_sticky_bar
{
	public $options;
	public function __construct()
	{
		delete_option('mtp_sticky_bar_option_group');
		$this->options = get_option('mtp_sticky_bar_option_name');
		$this->mtp_sticky_bar_reg_add();
		$this->mtp_sticky_bar_register_nav_menus();
	}
	/*
	 * Adding the options page
	 */
	public function mtp_sticky_bar_add_options_page()
	{
		add_options_page('MTP Sticky Bar','Sticky Bar','edit_theme_options',__FILE__,array('mtp_sticky_bar','mtp_sticky_bar_add_options_page_cb'));
	}
	/*
	 * Displaying the options page
	 */
	public function mtp_sticky_bar_add_options_page_cb()
	{ ?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Sticky Bar Options Page</h2>
			<form action="options.php" enctype="multipart/form-data" method="post">
				<?php settings_fields('mtp_sticky_bar_option_group'); ?>
				<?php do_settings_sections(__FILE__); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php }
	/*
	 * Registering and adding options page fields
	 */
	public function mtp_sticky_bar_reg_add()
	{
		/*
		 * Registering the options group
		 */
		register_setting('mtp_sticky_bar_option_group','mtp_sticky_bar_option_name');//,array($this,'mtp_sticky_bar_sanitize_cb'));
		/*
		 * Adding The Settings Sections
		 */
		add_settings_section('mtp_sticky_bar_css','CSS Settings',array($this,'mtp_sticky_bar_css_cb'),__FILE__);
		add_settings_section('mtp_sticky_bar_one','Area One Settings',array($this,'mtp_sticky_bar_one_cb'),__FILE__);
		add_settings_section('mtp_sticky_bar_two','Area Two Settings',array($this,'mtp_sticky_bar_two_cb'),__FILE__);
		add_settings_section('mtp_sticky_bar_three','Area Three Settings',array($this,'mtp_sticky_bar_three_cb'),__FILE__);
		add_settings_section('mtp_sticky_bar_four','Area Four Settings',array($this,'mtp_sticky_bar_four_cb'),__FILE__);
		add_settings_section('mtp_sticky_bar_five','Area Five Settings',array($this,'mtp_sticky_bar_five_cb'),__FILE__);
		add_settings_section('mtp_sticky_bar_six','Area Six Settings',array($this,'mtp_sticky_bar_six_cb'),__FILE__);
		add_settings_section('mtp_sticky_bar_seven','Area Seven Settings',array($this,'mtp_sticky_bar_seven_cb'),__FILE__);
		add_settings_section('mtp_sticky_bar_eight','Area Eight Settings',array($this,'mtp_sticky_bar_eight_cb'),__FILE__);
		/*
		 * Adding The Fields
		 */
		// Bar
		add_settings_field('mtp_sticky_bar_css_background_color','Background Color',array($this,'mtp_sticky_bar_css_background_color_cb'),__FILE__,'mtp_sticky_bar_css',array('label_for'=>'mtp_sticky_bar_css_background_color'));
		add_settings_field('mtp_sticky_bar_css_color','Text Color',array($this,'mtp_sticky_bar_css_color_cb'),__FILE__,'mtp_sticky_bar_css',array('label_for'=>'mtp_sticky_bar_css_color'));
		add_settings_field('mtp_sticky_bar_css_font_family','Font Family',array($this,'mtp_sticky_bar_css_font_family_cb'),__FILE__,'mtp_sticky_bar_css',array('label_for'=>'mtp_sticky_bar_font_family'));
		// Links
		add_settings_field('mtp_sticky_bar_css_anchor_color','Link Color',array($this,'mtp_sticky_bar_css_anchor_color_cb'),__FILE__,'mtp_sticky_bar_css',array('label_for'=>'mtp_sticky_bar_css_anchor_color'));
		add_settings_field('mtp_sticky_bar_css_anchor_text_decoration','Text Decoration',array($this,'mtp_sticky_bar_css_anchor_text_decoration_cb'),__FILE__,'mtp_sticky_bar_css',array('label_for'=>'mtp_sticky_bar_css_anchor_text_decoration'));
		// Hover
		add_settings_field('mtp_sticky_bar_css_anchor_hover_color','Link Hover Color',array($this,'mtp_sticky_bar_css_anchor_hover_color_cb'),__FILE__,'mtp_sticky_bar_css',array('label_for'=>'mtp_sticky_bar_css_anchor_hover_color'));
		add_settings_field('mtp_sticky_bar_css_anchor_hover_text_decoration','Hover Text Decoration',array($this,'mtp_sticky_bar_css_anchor_hover_text_decoration_cb'),__FILE__,'mtp_sticky_bar_css',array('label_for'=>'mtp_sticky_bar_css_anchor_hover_text_decoration'));
		// One
		add_settings_field('mtp_sticky_bar_one_area','Area',array($this,'mtp_sticky_bar_one_area_cb'),__FILE__,'mtp_sticky_bar_one',array('label_for'=>'mtp_sticky_bar_one_area'));
		add_settings_field('mtp_sticky_bar_one_logo_link','Logo Link',array($this,'mtp_sticky_bar_one_logo_link_cb'),__FILE__,'mtp_sticky_bar_one',array('label_for'=>'mtp_sticky_bar_one_logo_link'));
		add_settings_field('mtp_sticky_bar_one_padding_left','Padding Left',array($this,'mtp_sticky_bar_one_padding_left_cb'),__FILE__,'mtp_sticky_bar_one',array('label_for'=>'mtp_sticky_bar_one_padding_left'));
		add_settings_field('mtp_sticky_bar_one_padding_right','Padding Right',array($this,'mtp_sticky_bar_one_padding_right_cb'),__FILE__,'mtp_sticky_bar_one',array('label_for'=>'mtp_sticky_bar_one_padding_right'));
		// Two
		add_settings_field('mtp_sticky_bar_two_area','Area',array($this,'mtp_sticky_bar_two_area_cb'),__FILE__,'mtp_sticky_bar_two',array('label_for'=>'mtp_sticky_bar_two_area'));
		add_settings_field('mtp_sticky_bar_two_site_title','Current Site Title',array($this,'mtp_sticky_bar_two_site_title_cb'),__FILE__,'mtp_sticky_bar_two',array('label_for'=>'mtp_sticky_bar_two_site_title'));
		add_settings_field('mtp_sticky_bar_two_site_description','Site Description',array($this,'mtp_sticky_bar_two_site_description_cb'),__FILE__,'mtp_sticky_bar_two',array('label_for'=>'mtp_sticky_bar_two_site_description'));
		add_settings_field('mtp_sticky_bar_two_padding_left','Padding Left',array($this,'mtp_sticky_bar_two_padding_left_cb'),__FILE__,'mtp_sticky_bar_two',array('label_for'=>'mtp_sticky_bar_two_padding_left'));
		add_settings_field('mtp_sticky_bar_two_padding_right','Padding Right',array($this,'mtp_sticky_bar_two_padding_right_cb'),__FILE__,'mtp_sticky_bar_two',array('label_for'=>'mtp_sticky_bar_two_padding_right'));
		// Three
/*		add_settings_field('mtp_sticky_bar_three_area','Area',array($this,'mtp_sticky_bar_three_area_cb'),__FILE__,'mtp_sticky_bar_three',array('label_for'=>'mtp_sticky_bar_three_area'));
		add_settings_field('mtp_sticky_bar_three_padding_left','Padding Left',array($this,'mtp_sticky_bar_three_padding_left_cb'),__FILE__,'mtp_sticky_bar_three',array('label_for'=>'mtp_sticky_bar_three_padding_left'));
		add_settings_field('mtp_sticky_bar_three_padding_right','Padding Right',array($this,'mtp_sticky_bar_three_padding_right_cb'),__FILE__,'mtp_sticky_bar_three',array('label_for'=>'mtp_sticky_bar_three_padding_right'));
*/
		// Four
		add_settings_field('mtp_sticky_bar_four_area','Area',array($this,'mtp_sticky_bar_four_area_cb'),__FILE__,'mtp_sticky_bar_four',array('label_for'=>'mtp_sticky_bar_four_area'));
		add_settings_field('mtp_sticky_bar_four_link_text','LAMG Blog Network Text',array($this,'mtp_sticky_bar_four_link_text_cb'),__FILE__,'mtp_sticky_bar_four',array('label_for'=>'mtp_sticky_bar_four_link_text'));
		add_settings_field('mtp_sticky_bar_four_padding_left','Padding Left',array($this,'mtp_sticky_bar_four_padding_left_cb'),__FILE__,'mtp_sticky_bar_four',array('label_for'=>'mtp_sticky_bar_four_padding_left'));
		add_settings_field('mtp_sticky_bar_four_padding_right','Padding Right',array($this,'mtp_sticky_bar_four_padding_right_cb'),__FILE__,'mtp_sticky_bar_four',array('label_for'=>'mtp_sticky_bar_four_padding_right'));
		// Five
		add_settings_field('mtp_sticky_bar_five_area','Area',array($this,'mtp_sticky_bar_five_area_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_area'));
		add_settings_field('mtp_sticky_bar_five_shop','Show Divamos Shop Link',array($this,'mtp_sticky_bar_five_shop_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_shop'));
		add_settings_field('mtp_sticky_bar_five_shop_link','Divamos Shop Link',array($this,'mtp_sticky_bar_five_shop_link_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_shop_link'));
		add_settings_field('mtp_sticky_bar_five_shop_link_text','Divamos Shop Link Text',array($this,'mtp_sticky_bar_five_shop_link_text_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_shop_link_text'));
		add_settings_field('mtp_sticky_bar_five_forum','Show Forum Link',array($this,'mtp_sticky_bar_five_forum_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_forum'));
		add_settings_field('mtp_sticky_bar_five_forum_link','Forum Link',array($this,'mtp_sticky_bar_five_forum_link_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_forum_link'));
		add_settings_field('mtp_sticky_bar_five_forum_link_text','Forum Link Text',array($this,'mtp_sticky_bar_five_forum_link_text_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_forum_link_text'));
		add_settings_field('mtp_sticky_bar_five_newsletter','Show Newsletter Link',array($this,'mtp_sticky_bar_five_newsletter_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_newsletter'));
		add_settings_field('mtp_sticky_bar_five_newsletter_link_text','Newsletter Link Text',array($this,'mtp_sticky_bar_five_newsletter_link_text_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_newsletter_link_text'));
		add_settings_field('mtp_sticky_bar_five_padding_left','Padding Left',array($this,'mtp_sticky_bar_five_padding_left_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_padding_left'));
		add_settings_field('mtp_sticky_bar_five_padding_right','Padding Right',array($this,'mtp_sticky_bar_five_padding_right_cb'),__FILE__,'mtp_sticky_bar_five',array('label_for'=>'mtp_sticky_bar_five_padding_right'));
		// Six
		add_settings_field('mtp_sticky_bar_six_area','Area',array($this,'mtp_sticky_bar_six_area_cb'),__FILE__,'mtp_sticky_bar_six',array('label_for'=>'mtp_sticky_bar_six_area'));
		add_settings_field('mtp_sticky_bar_six_bookmark','Bookmark',array($this,'mtp_sticky_bar_six_bookmark_cb'),__FILE__,'mtp_sticky_bar_six',array('label_for'=>'mtp_sticky_bar_six_bookmark'));
		add_settings_field('mtp_sticky_bar_six_padding_left','Padding Left',array($this,'mtp_sticky_bar_six_padding_left_cb'),__FILE__,'mtp_sticky_bar_six',array('label_for'=>'mtp_sticky_bar_six_padding_left'));
		add_settings_field('mtp_sticky_bar_six_padding_right','Padding Right',array($this,'mtp_sticky_bar_six_padding_right_cb'),__FILE__,'mtp_sticky_bar_six',array('label_for'=>'mtp_sticky_bar_six_padding_right'));
		// Seven
		add_settings_field('mtp_sticky_bar_seven_area','Area',array($this,'mtp_sticky_bar_seven_area_cb'),__FILE__,'mtp_sticky_bar_seven',array('label_for'=>'mtp_sticky_bar_seven_area'));
		add_settings_field('mtp_sticky_bar_seven_facebook_link','Facebook Link',array($this,'mtp_sticky_bar_seven_facebook_link_cb'),__FILE__,'mtp_sticky_bar_seven',array('label_for'=>'mtp_sticky_bar_seven_facebook_link'));
		add_settings_field('mtp_sticky_bar_seven_twitter_link','Twitter Link',array($this,'mtp_sticky_bar_seven_twitter_link_cb'),__FILE__,'mtp_sticky_bar_seven',array('label_for'=>'mtp_sticky_bar_seven_twitter_link'));
		add_settings_field('mtp_sticky_bar_seven_gplus_link','Google Plus Link',array($this,'mtp_sticky_bar_seven_gplus_link_cb'),__FILE__,'mtp_sticky_bar_seven',array('label_for'=>'mtp_sticky_bar_seven_gplus_link'));
		add_settings_field('mtp_sticky_bar_seven_pinterest_link','Pinterest Link',array($this,'mtp_sticky_bar_seven_pinterest_link_cb'),__FILE__,'mtp_sticky_bar_seven',array('label_for'=>'mtp_sticky_bar_seven_pinterest_link'));
		add_settings_field('mtp_sticky_bar_seven_padding_left','Padding Left',array($this,'mtp_sticky_bar_seven_padding_left_cb'),__FILE__,'mtp_sticky_bar_seven',array('label_for'=>'mtp_sticky_bar_seven_padding_left'));
		add_settings_field('mtp_sticky_bar_seven_padding_right','Padding Right',array($this,'mtp_sticky_bar_seven_padding_right_cb'),__FILE__,'mtp_sticky_bar_seven',array('label_for'=>'mtp_sticky_bar_seven_padding_right'));
		// Eight
		add_settings_field('mtp_sticky_bar_eight_area','Area',array($this,'mtp_sticky_bar_eight_area_cb'),__FILE__,'mtp_sticky_bar_eight',array('label_for'=>'mtp_sticky_bar_eight_area'));
		add_settings_field('mtp_sticky_bar_eight_friends','Invite Friends',array($this,'mtp_sticky_bar_eight_friends_cb'),__FILE__,'mtp_sticky_bar_eight',array('label_for'=>'mtp_sticky_bar_eight_friends'));
		add_settings_field('mtp_sticky_bar_eight_mail_subject','Subject',
			array($this,'mtp_sticky_bar_eight_mail_subject_cb'),__FILE__,'mtp_sticky_bar_eight',
			array('label_for'=>'mtp_sticky_bar_eight_mail_subject'));
		add_settings_field('mtp_sticky_bar_eight_mail_body','Message',
			array($this,'mtp_sticky_bar_eight_mail_body_cb'),__FILE__,'mtp_sticky_bar_eight',
			array('label_for'=>'mtp_sticky_bar_eight_mail_body'));
		add_settings_field('mtp_sticky_bar_eight_padding_left','Padding Left',array($this,'mtp_sticky_bar_eight_padding_left_cb'),__FILE__,'mtp_sticky_bar_eight',array('label_for'=>'mtp_sticky_bar_eight_padding_left'));
		add_settings_field('mtp_sticky_bar_eight_padding_right','Padding Right',array($this,'mtp_sticky_bar_eight_padding_right_cb'),__FILE__,'mtp_sticky_bar_eight',array('label_for'=>'mtp_sticky_bar_eight_padding_right'));
	}
	/*
	 * CSS Settings Section Callback
	 */
	public function mtp_sticky_bar_css_cb()
	{ ?>
		<table class="mtp-sticky-bar-preview">
			<tr>
				<td><a href="#">Logo</a> / Area 1</td>
				<td><a href="#">Site Title</a> / Area 2</td>
				<td><a href="#">Netvibes</a> / Area 3</td>
				<td><a href="#">Sites</a> / Area 4</td>
				<td><a href="#">Shop</a> <a href="#">Forum</a> <a href="#">Newsletter</a> / Area 5</td><td><a href="#">Bookmark</a> / Area 6</td>
				<td><a href="#">Social</a> / Area 7</td>
				<td><a href="#">Invite Friends</a> / Area 8</td>
			</tr>
		</table>
		<style type="text/css">
		.mtp-sticky-bar-preview {
			background: <?php echo $this->options['mtp_sticky_bar_css_background_color']; ?>;
			color: '.$this->options["mtp_sticky_bar_css_color"].';
			font: normal 13px/1.5 '.$this->options["mtp_sticky_bar_css_font_family"].';
			margin-bottom: 10px; width: 100%;
		}
		.mtp-sticky-bar-preview a {
			color: '.$this->options["mtp_sticky_bar_css_anchor_color"].';
			font-weight: bold;
			text-decoration: '.$this->options["mtp_sticky_bar_css_anchor_text_decoration"].';
		}
		.mtp-sticky-bar-preview a:hover {
			color: '.$this->options["mtp_sticky_bar_css_anchor_hover_color"].';
			text-decoration: '.$this->options["mtp_sticky_bar_css_anchor_hover_text_decoration"].';
		}
		.mtp-sticky-bar-preview td {
			border-left: 1px solid rgba(255,255,255,.4);
			border-right: 1px solid rgba(0,0,0,.2);
			float: left;
			padding: 10px;
		}
		.mtp-sticky-bar-preview td:first-child { border-left: none; }
		.mtp-sticky-bar-preview td:last-child { border-right: none; }';
		</style>
	<?php }
	/*
	 * Area One Settings Section Callback
	 */
	public function mtp_sticky_bar_one_cb()
	{
	}
	/*
	 * Area Two Settings Section Callback
	 */
	public function mtp_sticky_bar_two_cb()
	{
	}
	/*
	 * Area Three Settings Section Callback
	 */
	public function mtp_sticky_bar_three_cb()
	{
	}
	/*
	 * Area Four Settings Section Callback
	 */
	public function mtp_sticky_bar_four_cb()
	{
	}
	/*
	 * Area Five Settings Section Callback
	 */
	public function mtp_sticky_bar_five_cb()
	{
	}
	/*
	 * Area Six Settings Section Callback
	 */
	public function mtp_sticky_bar_six_cb()
	{
	}
	/*
	 * Area Seven Settings Section Callback
	 */
	public function mtp_sticky_bar_seven_cb()
	{
	}
	/*
	 * Area Eight Settings Section Callback
	 */
	public function mtp_sticky_bar_eight_cb()
	{
	}
	/*
	 * Registering the navigations menus
	 */
	public function mtp_sticky_bar_register_nav_menus()
	{
		register_nav_menus(array(
			'sticky_bar_four' => 'Sticky Bar'
		));
	}
	/*
	 * Displaying the background color field
	 */
	public function mtp_sticky_bar_css_background_color_cb()
	{
		echo "<input id='mtp_sticky_bar_css_background_color' name='mtp_sticky_bar_option_name[mtp_sticky_bar_css_background_color]' type='text' value='{$this->options['mtp_sticky_bar_css_background_color']}'>";
		echo "<span class='description'> Default Value: <code>#000</code></span>";
	}
	/*
	 * Displaying the text color field
	 */
	public function mtp_sticky_bar_css_color_cb()
	{
		echo "<input id='mtp_sticky_bar_css_color' name='mtp_sticky_bar_option_name[mtp_sticky_bar_css_color]' type='text' value='{$this->options['mtp_sticky_bar_css_color']}'>";
		echo "<span class='description'> Default Value: <code>#bbb</code></span>";
	}
	/*
	 * Displaying The Font Family Field
	 */
	public function mtp_sticky_bar_css_font_family_cb()
	{
		echo "<input class='regular-text' id='mtp_sticky_bar_css_font_family' name='mtp_sticky_bar_option_name[mtp_sticky_bar_css_font_family]' type='text' value='{$this->options['mtp_sticky_bar_css_font_family']}'>";
		echo "<span class='description'> Default Value: <code>Helvetica Neue, Arial, Helvetica, sans-serif</code></span>";
	}
	/*
	 * Displaying The Link Text Color Field
	 */
	public function mtp_sticky_bar_css_anchor_color_cb()
	{
		echo "<input id='mtp_sticky_bar_css_anchor_color' name='mtp_sticky_bar_option_name[mtp_sticky_bar_css_anchor_color]' type='text' value='{$this->options['mtp_sticky_bar_css_anchor_color']}'>";
		echo "<span class='description'> Default Value: <code>#fff</code></span>";
	}
	/*
	 * Displaying The Link Text Decoration Field
	 */
	public function mtp_sticky_bar_css_anchor_text_decoration_cb()
	{
		echo "<select id='mtp_sticky_bar_css_anchor_text_decoration' name='mtp_sticky_bar_option_name[mtp_sticky_bar_css_anchor_text_decoration]'>";
			$ops = array('blink','line-through','none','overline','underline');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_css_anchor_text_decoration'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>none</code></span>";
	}
	/*
	 * Displaying The Link Hover Text Color Field
	 */
	public function mtp_sticky_bar_css_anchor_hover_color_cb()
	{
		echo "<input id='mtp_sticky_bar_css_anchor_hover_color' name='mtp_sticky_bar_option_name[mtp_sticky_bar_css_anchor_hover_color]' type='text' value='{$this->options['mtp_sticky_bar_css_anchor_hover_color']}'>";
		echo "<span class='description'> Default Value: <code>#bbb</code></span>";
	}
	/*
	 * Displaying The Link Hover Text Decoration Field
	 */
	public function mtp_sticky_bar_css_anchor_hover_text_decoration_cb()
	{
		echo "<select id='mtp_sticky_bar_css_anchor_hover_text_decoration' name='mtp_sticky_bar_option_name[mtp_sticky_bar_css_anchor_hover_text_decoration]'>";
			$ops = array('blink','line-through','none','overline','underline');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_css_anchor_hover_text_decoration'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>none</code></span>";
	}
	/* **********
	 * Area One *
	 ************/
	/*
	 * Displaying The Area
	 */
	public function mtp_sticky_bar_one_area_cb()
	{
		echo "<select id='mtp_sticky_bar_one_area' name='mtp_sticky_bar_option_name[mtp_sticky_bar_one_area]'>";
			$ops = array('one','two','three','four','five','six','seven','eight');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_one_area'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>one</code></span>";
	}
	/*
	 * Displaying The LAMG Link Field
	 */
	public function mtp_sticky_bar_one_logo_link_cb()
	{
		$url = get_bloginfo('url');
		$url = preg_replace('/(http:\/\/)/','',$url);
		echo "<input class='regular-text code' id='mtp_sticky_bar_one_logo_link' name='mtp_sticky_bar_option_name[mtp_sticky_bar_one_logo_link]' type='url' value='{$this->options['mtp_sticky_bar_one_logo_link']}'>";
		echo "<span class='description'> Default Value: <code>http://lamg.com.br/?utm_source=$url&utm_medium=iframe</code></span>";
	}
	/*
	 * Displaying The Padding Left
	 */
	public function mtp_sticky_bar_one_padding_left_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_one_padding_left' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_one_padding_left]' type='number' value='{$this->options['mtp_sticky_bar_one_padding_left']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/*
	 * Displaying The Padding Right
	 */
	public function mtp_sticky_bar_one_padding_right_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_one_padding_right' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_one_padding_right]' type='number' value='{$this->options['mtp_sticky_bar_one_padding_right']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/* **********
	 * Area Two *
	 ************/
	/*
	 * Displaying The Area
	 */
	public function mtp_sticky_bar_two_area_cb()
	{
		echo "<select id='mtp_sticky_bar_two_area' name='mtp_sticky_bar_option_name[mtp_sticky_bar_two_area]'>";
			$ops = array('one','two','three','four','five','six','seven','eight');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_two_area'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>two</code></span>";
	}
	/*
	 * Displaying The Current Site Text
	 */
	public function mtp_sticky_bar_two_site_title_cb()
	{
		echo "<input class='regular-text' id='mtp_sticky_bar_two_site_title' name='mtp_sticky_bar_option_name[mtp_sticky_bar_two_site_title]' type='text' value='{$this->options['mtp_sticky_bar_two_site_title']}'>";
		echo '<span class="description"> Default Value: <code>'.get_bloginfo("title").'</code></span>';
	}
	/*
	 * Displaying The Site Description Field
	 */
	public function mtp_sticky_bar_two_site_description_cb()
	{
		echo "<textarea class='large-text code' cols='50' id='mtp_sticky_bar_two_site_description' name='mtp_sticky_bar_option_name[mtp_sticky_bar_two_site_description]' rows='10'>{$this->options['mtp_sticky_bar_two_site_description']}</textarea>";
	}
	/*
	 * Displaying The Padding Left
	 */
	public function mtp_sticky_bar_two_padding_left_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_two_padding_left' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_two_padding_left]' type='number' value='{$this->options['mtp_sticky_bar_two_padding_left']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/*
	 * Displaying The Padding Right
	 */
	public function mtp_sticky_bar_two_padding_right_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_two_padding_right' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_two_padding_right]' type='number' value='{$this->options['mtp_sticky_bar_two_padding_right']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/* ************
	 * Area Three *
	 **************/
	/*
	 * Displaying The Area
	 */
/*	public function mtp_sticky_bar_three_area_cb()
	{
		echo "<select id='mtp_sticky_bar_three_area' name='mtp_sticky_bar_option_name[mtp_sticky_bar_three_area]'>";
			$ops = array('one','two','three','four','five','six','seven','eight');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_three_area'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>three</code></span>";
	}
*/
	/*
	 * Displaying The Padding Left
	 */
/*	public function mtp_sticky_bar_three_padding_left_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_three_padding_left' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_three_padding_left]' type='number' value='{$this->options['mtp_sticky_bar_three_padding_left']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
*/
	/*
	 * Displaying The Padding Right
	 */
/*	public function mtp_sticky_bar_three_padding_right_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_three_padding_right' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_three_padding_right]' type='number' value='{$this->options['mtp_sticky_bar_three_padding_right']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
*/
	/* ***********
	 * Area Four *
	 *************/
	/*
	 * Displaying The Network Link Text (responsive design < 1024px)
	 */
	public function mtp_sticky_bar_four_link_text_cb()
	{
		echo "<input class='regular-text' id='mtp_sticky_bar_four_link_text' name='mtp_sticky_bar_option_name[mtp_sticky_bar_four_link_text]' type='text' value='{$this->options['mtp_sticky_bar_four_link_text']}'>";
		echo "<span class='description'> Default Value: <code>LAMG Blog Network</code></span>";
	}
	/*
	 * Displaying The Area
	 */
	public function mtp_sticky_bar_four_area_cb()
	{
		echo "<select id='mtp_sticky_bar_four_area' name='mtp_sticky_bar_option_name[mtp_sticky_bar_four_area]'>";
			$ops = array('one','two','three','four','five','six','seven','eight');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_four_area'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>four</code></span>";
	}
	/*
	 * Displaying The Padding Left
	 */
	public function mtp_sticky_bar_four_padding_left_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_four_padding_left' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_four_padding_left]' type='number' value='{$this->options['mtp_sticky_bar_four_padding_left']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/*
	 * Displaying The Padding Right
	 */
	public function mtp_sticky_bar_four_padding_right_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_four_padding_right' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_four_padding_right]' type='number' value='{$this->options['mtp_sticky_bar_four_padding_right']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/* ***********
	 * Area Five *
	 *************/
	/*
	 * Displaying The Area
	 */
	public function mtp_sticky_bar_five_area_cb()
	{
		echo "<select id='mtp_sticky_bar_five_area' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_area]'>";
			$ops = array('one','two','three','four','five','six','seven','eight');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_five_area'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>five</code></span>";
	}
	/*
	 * Displaying The Shop
	 */
	public function mtp_sticky_bar_five_shop_cb()
	{
		echo "<select id='mtp_sticky_bar_five_shop' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_shop]'>";
			$ops = array('yes','no');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_five_shop'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>yes</code></span>";
	}
	/*
	 * Displaying The Shop Link
	 */
	public function mtp_sticky_bar_five_shop_link_cb()
	{
		$url = get_bloginfo('url');
		$url = preg_replace('/(http:\/\/)/','',$url);
		echo "<input class='regular-text code' id='mtp_sticky_bar_five_shop_link' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_shop_link]' type='text' value='{$this->options['mtp_sticky_bar_five_shop_link']}'>";
		echo "<span class='description'> Default Value: <code>http://divamos.com/?utm_source=$url&utm_medium=iframe</code></span>";
	}
	/*
	 * Displaying The Shop Link Text
	 */
	public function mtp_sticky_bar_five_shop_link_text_cb()
	{
		echo "<input class='regular-text' id='mtp_sticky_bar_five_shop_link_text' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_shop_link_text]' type='text' value='{$this->options['mtp_sticky_bar_five_shop_link_text']}'>";
		echo "<span class='description'> Default Value: <code>Divamos tienda</code></span>";
	}
	/*
	 * Displaying The Forum
	 */
	public function mtp_sticky_bar_five_forum_cb()
	{
		echo "<select id='mtp_sticky_bar_five_forum' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_forum]'>";
			$ops = array('no','yes');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_five_forum'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>no</code></span>";
	}
	/*
	 * Displaying The Forum Link
	 */
	public function mtp_sticky_bar_five_forum_link_cb()
	{
		$url = get_bloginfo('url');
		$url = preg_replace('/(http:\/\/)/','',$url);
		echo "<input class='regular-text code' id='mtp_sticky_bar_five_forum_link' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_forum_link]' type='text' value='{$this->options['mtp_sticky_bar_five_forum_link']}'>";
		echo "<span class='description'> Default Value: <code>http://forum.$url</code></span>";
	}
	/*
	 * Displaying The Forum Link Text
	 */
	public function mtp_sticky_bar_five_forum_link_text_cb()
	{
		echo "<input class='regular-text' id='mtp_sticky_bar_five_forum_link_text' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_forum_link_text]' type='text' value='{$this->options['mtp_sticky_bar_five_forum_link_text']}'>";
		echo "<span class='description'> Default Value: <code>Forum</code></span>";
	}
	/*
	 * Displaying The Newsletter
	 */
	public function mtp_sticky_bar_five_newsletter_cb()
	{
		echo "<select id='mtp_sticky_bar_five_newsletter' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_newsletter]'>";
			$ops = array('no','yes');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_five_newsletter'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>no</code></span>";
	}
	/*
	 * Displaying The Newsletter Link Text
	 */
	public function mtp_sticky_bar_five_newsletter_link_text_cb()
	{
		echo "<input class='regular-text' id='mtp_sticky_bar_five_newsletter_link_text' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_newsletter_link_text]' type='text' value='{$this->options['mtp_sticky_bar_five_newsletter_link_text']}'>";
		echo "<span class='description'> Default Value: <code>Newsletter</code></span>";
	}
	/*
	 * Displaying The Padding Left
	 */
	public function mtp_sticky_bar_five_padding_left_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_five_padding_left' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_padding_left]' type='number' value='{$this->options['mtp_sticky_bar_five_padding_left']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/*
	 * Displaying The Padding Right
	 */
	public function mtp_sticky_bar_five_padding_right_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_five_padding_right' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_five_padding_right]' type='number' value='{$this->options['mtp_sticky_bar_five_padding_right']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/* **********
	 * Area Six *
	 ************/
	/*
	 * Displaying The Area
	 */
	public function mtp_sticky_bar_six_area_cb()
	{
		echo "<select id='mtp_sticky_bar_six_area' name='mtp_sticky_bar_option_name[mtp_sticky_bar_six_area]'>";
			$ops = array('one','two','three','four','five','six','seven','eight');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_six_area'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>six</code></span>";
	}
	/*
	 * Displaying The Bookmark
	 */
	public function mtp_sticky_bar_six_bookmark_cb()
	{
		echo "<input class='regular-text' id='mtp_sticky_bar_six_bookmark' name='mtp_sticky_bar_option_name[mtp_sticky_bar_six_bookmark]' type='text' value='{$this->options['mtp_sticky_bar_six_bookmark']}'>";
		echo "<span class='description'> Default Value: <code>Bookmark</code></span>";
	}
	/*
	 * Displaying The Padding Left
	 */
	public function mtp_sticky_bar_six_padding_left_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_six_padding_left' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_six_padding_left]' type='number' value='{$this->options['mtp_sticky_bar_six_padding_left']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/*
	 * Displaying The Padding Right
	 */
	public function mtp_sticky_bar_six_padding_right_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_six_padding_right' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_six_padding_right]' type='number' value='{$this->options['mtp_sticky_bar_six_padding_right']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/* ************
	 * Area Seven *
	 **************/
	/*
	 * Displaying The Area
	 */
	public function mtp_sticky_bar_seven_area_cb()
	{
		echo "<select id='mtp_sticky_bar_seven_area' name='mtp_sticky_bar_option_name[mtp_sticky_bar_seven_area]'>";
			$ops = array('one','two','three','four','five','six','seven','eight');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_seven_area'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>seven</code></span>";
	}
	/*
	 * Displaying the Facebook Link
	 */
	public function mtp_sticky_bar_seven_facebook_link_cb()
	{
		echo "<input class='regular-text code' id='mtp_sticky_bar_seven_facebook_link' name='mtp_sticky_bar_option_name[mtp_sticky_bar_seven_facebook_link]' type='url' value='{$this->options['mtp_sticky_bar_seven_facebook_link']}'>";
		echo "<span class='description'> Default Value: <code>http://facebook.com/</code></span>";
	}
	/*
	 * Displaying the Twitter Link
	 */
	public function mtp_sticky_bar_seven_twitter_link_cb()
	{
		echo "<input class='regular-text code' id='mtp_sticky_bar_seven_twitter_link' name='mtp_sticky_bar_option_name[mtp_sticky_bar_seven_twitter_link]' type='url' value='{$this->options['mtp_sticky_bar_seven_twitter_link']}'>";
		echo "<span class='description'> Default Value: <code>http://twitter.com/</code></span>";
	}
	/*
	 * Displaying the Google+ Link
	 */
	public function mtp_sticky_bar_seven_gplus_link_cb()
	{
		echo "<input class='regular-text code' id='mtp_sticky_bar_seven_gplus_link' name='mtp_sticky_bar_option_name[mtp_sticky_bar_seven_gplus_link]' type='url' value='{$this->options['mtp_sticky_bar_seven_gplus_link']}'>";
		echo "<span class='description'> Default Value: <code>http://plus.google.com/</code></span>";
	}
	/*
	 * Displaying the Pinterest Link
	 */
	public function mtp_sticky_bar_seven_pinterest_link_cb()
	{
		echo "<input class='regular-text code' id='mtp_sticky_bar_seven_pinterest_link' name='mtp_sticky_bar_option_name[mtp_sticky_bar_seven_pinterest_link]' type='url' value='{$this->options['mtp_sticky_bar_seven_pinterest_link']}'>";
		echo "<span class='description'> Default Value: <code>http://pinterest.com/</code></span>";
	}
	/*
	 * Displaying The Padding Left
	 */
	public function mtp_sticky_bar_seven_padding_left_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_seven_padding_left' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_seven_padding_left]' type='number' value='{$this->options['mtp_sticky_bar_seven_padding_left']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/*
	 * Displaying The Padding Right
	 */
	public function mtp_sticky_bar_seven_padding_right_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_seven_padding_right' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_seven_padding_right]' type='number' value='{$this->options['mtp_sticky_bar_seven_padding_right']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/* ***********
	 * Area Eight *
	 *************/
	/*
	 * Displaying The Area
	 */
	public function mtp_sticky_bar_eight_area_cb()
	{
		echo "<select id='mtp_sticky_bar_eight_area' name='mtp_sticky_bar_option_name[mtp_sticky_bar_eight_area]'>";
			$ops = array('one','two','three','four','five','six','seven','eight');
			foreach ($ops as $key)
			{
				$selected = ($this->options['mtp_sticky_bar_eight_area'] === $key) ? "selected='selected'" : "";
				echo "<option value='$key' $selected>$key</option>";
			}
		echo "</select>";
		echo "<span class='description'> Default Value: <code>eight</code></span>";
	}
	/*
	 * Displaying Invite Friends
	 */
	public function mtp_sticky_bar_eight_friends_cb()
	{
		echo "<input class='regular-text' id='mtp_sticky_bar_eight_friends' name='mtp_sticky_bar_option_name[mtp_sticky_bar_eight_friends]' type='text' value='{$this->options['mtp_sticky_bar_eight_friends']}'>";
		echo "<span class='description'> Default Value: <code>Invite your friends</code></span>";
	}
	/*
	 * Display Subject message
	*/
	public function mtp_sticky_bar_eight_mail_subject_cb()
	{
	
		echo "<input class='regular-text code' id='mtp_sticky_bar_eight_mail_subject' name='mtp_sticky_bar_option_name[mtp_sticky_bar_eight_mail_subject]' type='text' value='{$this->options['mtp_sticky_bar_eight_mail_subject']}'>";
	}
	/*
	 * Display Message body
	*/
	public function mtp_sticky_bar_eight_mail_body_cb()
	{
	
		echo "<textarea class='large-text code mailbody' cols='50' id='mtp_sticky_bar_eight_mail_body' name='mtp_sticky_bar_option_name[mtp_sticky_bar_eight_mail_body]' rows='10'>{$this->options['mtp_sticky_bar_eight_mail_body']}</textarea>";
	}
	/*
	 * Displaying The Padding Left
	 */
	public function mtp_sticky_bar_eight_padding_left_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_eight_padding_left' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_eight_padding_left]' type='number' value='{$this->options['mtp_sticky_bar_eight_padding_left']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
	/*
	 * Displaying The Padding Right
	 */
	public function mtp_sticky_bar_eight_padding_right_cb()
	{
		echo "<input class='small-text' id='mtp_sticky_bar_eight_padding_right' min='0' name='mtp_sticky_bar_option_name[mtp_sticky_bar_eight_padding_right]' type='number' value='{$this->options['mtp_sticky_bar_eight_padding_right']}'>";
		echo "<span class='description'> Default Value: <code>0px</code></span>";
	}
}
/*
 * Displaying the options page in the WordPress Backend
 */
function mtp_sticky_bar_admin_menu()
{
	mtp_sticky_bar::mtp_sticky_bar_add_options_page();
}
add_action('admin_menu','mtp_sticky_bar_admin_menu');
/*
 * Creating a MTP Sticky Bar
 */
function mtp_sticky_bar_admin_init()
{
	new mtp_sticky_bar();
}
add_action('admin_init','mtp_sticky_bar_admin_init');
/*
 * Displaying a MTP Sticky Bar
 */
function mtp_sticky_bar_thbh()
{
	$o   = get_option('mtp_sticky_bar_option_name');
	$url = get_bloginfo('url');
	$url = preg_replace('/(http:\/\/)/','',$url);
	$background_color             = $o['mtp_sticky_bar_css_background_color'] == '' ? '#000' : $o['mtp_sticky_bar_css_background_color'];
	$color                        = $o['mtp_sticky_bar_css_color'] == '' ? '#bbb' : $o['mtp_sticky_bar_css_color'];
	$font_family                  = $o['mtp_sticky_bar_css_font_family'] == '' ? 'Helvetica Neue, Arial, Helvetica, sans-serif' : $o['mtp_sticky_bar_css_font_family'];
	$anchor_color                 = $o['mtp_sticky_bar_css_anchor_color'] == '' ? '#fff' : $o['mtp_sticky_bar_css_anchor_color'];
	$anchor_text_decoration       = $o['mtp_sticky_bar_css_anchor_text_decoration'] == '' ? 'none' : $o['mtp_sticky_bar_css_anchor_text_decoration'];
	$anchor_hover_color           = $o['mtp_sticky_bar_css_anchor_hover_color'] == '' ? '#bbb' : $o['mtp_sticky_bar_css_anchor_hover_color'];
	$anchor_hover_text_decoration = $o['mtp_sticky_bar_css_anchor_hover_text_decoration'] == '' ? 'none' : $o['mtp_sticky_bar_css_anchor_hover_text_decoration'];
	$one_area                     = $o['mtp_sticky_bar_one_area'] == '' ? 'one' : $o['mtp_sticky_bar_one_area'];
	$one_logo_link                = $o['mtp_sticky_bar_one_logo_link'] == '' ? 'http://lamg.com.br/?utm_source='.$url.'&utm_medium=iframe' : $o['mtp_sticky_bar_one_logo_link'];
	$one_padding_left             = $o['mtp_sticky_bar_one_padding_left'];
	$one_padding_right            = $o['mtp_sticky_bar_one_padding_right'];
	$two_area                     = $o['mtp_sticky_bar_two_area'] == '' ? 'two' : $o['mtp_sticky_bar_two_area'];
	$two_site_title               = $o['mtp_sticky_bar_two_site_title'];
	$two_site_description         = $o['mtp_sticky_bar_two_site_description'];
	$two_padding_left             = $o['mtp_sticky_bar_two_padding_left'];
	$two_padding_right            = $o['mtp_sticky_bar_two_padding_right'];
	/*$three_area                   = $o['mtp_sticky_bar_three_area'] == '' ? 'three' : $o['mtp_sticky_bar_three_area'];
	$three_padding_left           = $o['mtp_sticky_bar_three_padding_left'];
	$three_padding_right          = $o['mtp_sticky_bar_three_padding_right'];*/
	$three_show = 'no';
	$four_area                    = $o['mtp_sticky_bar_four_area'] == '' ? 'four' : $o['mtp_sticky_bar_four_area'];
	$four_link_text               = $o['mtp_sticky_bar_four_link_text'];
	$four_padding_left            = $o['mtp_sticky_bar_four_padding_left'];
	$four_padding_right           = $o['mtp_sticky_bar_four_padding_right'];
	$five_area                    = $o['mtp_sticky_bar_five_area'] == '' ? 'five' : $o['mtp_sticky_bar_five_area'];
	$five_shop                    = $o['mtp_sticky_bar_five_shop'] == '' ? 'yes' : $o['mtp_sticky_bar_five_shop'];
	$five_shop_link               = $o['mtp_sticky_bar_five_shop_link'] == '' ? 'http://divamos.com/?utm_source='.$url.'&utm_medium=iframe' : $o['mtp_sticky_bar_five_shop_link'];
	$five_shop_link_text          = $o['mtp_sticky_bar_five_shop_link_text'] == '' ? 'Divamos' : $o['mtp_sticky_bar_five_shop_link_text'];
	$five_forum                   = $o['mtp_sticky_bar_five_forum'] == '' ? 'no' : $o['mtp_sticky_bar_five_forum'];
	$five_forum_link              = $o['mtp_sticky_bar_five_forum_link'] == '' ? 'http://forum.'.$url.'' : $o['mtp_sticky_bar_five_forum_link'];
	$five_forum_link_text         = $o['mtp_sticky_bar_five_forum_link_text'] == '' ? 'Forum' : $o['mtp_sticky_bar_five_forum_link_text'];
	$newsletter                   = $o['mtp_sticky_bar_five_newsletter'] == '' ? 'no' : $o['mtp_sticky_bar_five_newsletter'];
	$newsletter_link_text         = $o['mtp_sticky_bar_five_newsletter_link_text'] == '' ? 'Newsletter' : $o['mtp_sticky_bar_five_newsletter_link_text'];
	$five_padding_left            = $o['mtp_sticky_bar_five_padding_left'];
	$five_padding_right           = $o['mtp_sticky_bar_five_padding_right'];
	$six_area                     = $o['mtp_sticky_bar_six_area'] == '' ? 'six' : $o['mtp_sticky_bar_six_area'];
	$six_bookmark                 = $o['mtp_sticky_bar_six_bookmark'] == '' ? 'Bookmark' : $o['mtp_sticky_bar_six_bookmark'];
	$six_padding_left             = $o['mtp_sticky_bar_six_padding_left'];
	$six_padding_right            = $o['mtp_sticky_bar_six_padding_right'];
	$seven_area                   = $o['mtp_sticky_bar_seven_area'] == '' ? 'seven' : $o['mtp_sticky_bar_seven_area'];
	$seven_facebook_link          = $o['mtp_sticky_bar_seven_facebook_link'] == '' ? 'http://facebook.com' : $o['mtp_sticky_bar_seven_facebook_link'];
	$seven_twitter_link           = $o['mtp_sticky_bar_seven_twitter_link'] == '' ? 'http://twitter.com' : $o['mtp_sticky_bar_seven_twitter_link'];
	$seven_gplus_link             = $o['mtp_sticky_bar_seven_gplus_link'] == '' ? 'http://plus.google.com' : $o['mtp_sticky_bar_seven_gplus_link'];
	$seven_pinterest_link         = $o['mtp_sticky_bar_seven_pinterest_link'] == '' ? 'http://pinterest.com' : $o['mtp_sticky_bar_seven_pinterest_link'];
	$seven_rss_link               = get_bloginfo('url').'/rss';
	$seven_padding_left           = $o['mtp_sticky_bar_seven_padding_left'];
	$seven_padding_right          = $o['mtp_sticky_bar_seven_padding_right'];
	$eight_area                   = $o['mtp_sticky_bar_eight_area'] == '' ? 'eight' : $o['mtp_sticky_bar_eight_area'];
	$eight_friends                = $o['mtp_sticky_bar_eight_friends'] == '' ? 'Invite your friends' : $o['mtp_sticky_bar_eight_friends'];
	$eight_mail_subject           = $o['mtp_sticky_bar_eight_mail_subject'] == '' ? : $o['mtp_sticky_bar_eight_mail_subject'];
	$eight_mail_body              = $o['mtp_sticky_bar_eight_mail_body'] == '' ? : $o['mtp_sticky_bar_eight_mail_body'];
	$eight_padding_left           = $o['mtp_sticky_bar_eight_padding_left'];
	$eight_padding_right          = $o['mtp_sticky_bar_eight_padding_right'];
	echo '<div id="mtp-sticky-bar">';
	echo '<div id="flexible">';
	/*
	 * One
	 */
	switch ($one_area) {
		case 'two':
			echo '<ul id="mtp-sticky-bar-two" class="mtp-sticky-bar-menu">';
			$cb_title = $two_site_title ? $two_site_title : get_bloginfo('title');
			echo '<li><a href="#">'.$cb_title.'</a>';
			if ($two_site_description)
			{
				echo '<ul class="sub-menu"><li>';
				echo $two_site_description;
				echo '</li></ul>';
			}
			echo '</li>';
			echo '</ul>';
			break;
		case 'three':
			if ($three_show == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-three" class="mtp-sticky-bar-menu">';
				echo '<li><a href="#">Netvibes</a></li>';
				echo '</ul>';
			}
			break;
		case 'four':
			$lamgtext = $four_link_text ? $four_link_text : 'LAMG Blog Network';
			if (has_nav_menu('sticky_bar_four')) { wp_nav_menu(array('container'=>'','menu_class'=>'mtp-sticky-bar-menu','menu_id'=>'mtp-sticky-bar-four','theme_location'=>'sticky_bar_four', 'items_wrap' => '<ul id="%1$s" class="%2$s"><li class="lamg-network"><a href="#">'.$lamgtext.'</a></li>%3$s</ul>')); }
			break;
		case 'five':
			if ($five_shop == 'yes' || $five_forum == 'yes' || $newsletter == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-five" class="mtp-sticky-bar-menu">';
				if ($five_shop == 'yes') { echo '<li><a href="'.$five_shop_link.'" target="_blank">'.$five_shop_link_text.'</a></li>'; }
				if ($five_forum == 'yes') { echo '<li><a href="'.$five_forum_link.'">'.$five_forum_link_text.'</a></li>'; }
				if ($newsletter == 'yes') { echo '<li><a href="#">'.$newsletter_link_text.'</a><ul class="sub-menu"><li>'; if(function_exists('mtp_newsletter_form')) mtp_newsletter_form(); echo '</li></ul></li>'; }
				echo '</ul>';
			}
			break;
		case 'six':
			echo '<ul id="mtp-sticky-bar-six" class="mtp-sticky-bar-menu">';
			echo '<li><a href="#" id="add-bookmark" rel="sidebar">'.$six_bookmark.'</a></li>';
			echo '</ul>';
			break;
		case 'seven':
			echo '<ul id="mtp-sticky-bar-seven" class="mtp-sticky-bar-menu">';
			echo '<li><a class="ir" href="'.$seven_facebook_link.'" id="f" rel="me" title="Facebook" target="_blank">Facebook</a></li>';
			echo '<li><a class="ir" href="'.$seven_twitter_link.'" id="t" rel="me" title="Twitter" target="_blank">Twitter</a></li>';
			echo '<li><a class="ir" href="'.$seven_gplus_link.'" id="g" rel="me" title="Google+" target="_blank">Google+</a></li>';
			echo '<li><a class="ir" href="'.$seven_pinterest_link.'" id="p" rel="me" title="Pinterest" target="_blank">Pinterest</a></li>';
			echo '<li><a class="ir" href="'.$seven_rss_link.'" id="r" rel="me" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			echo '</ul>';
			break;
		case 'eight':
			echo '<ul id="mtp-sticky-bar-eight" class="mtp-sticky-bar-menu">';
			echo '<li><a class="mail" href="mailto:?subject='.$eight_mail_subject.'&body='.$eight_mail_body.'">'.$eight_friends.'</a></li>';
			echo '</ul>';
			break;
		
		default:
			echo '<ul id="mtp-sticky-bar-one" class="mtp-sticky-bar-menu">';
			echo '<li id="mtp-sticky-bar-lamg"><a class="ir" href="'.$one_logo_link.'" rel="me" title="Latinamerican Media Group">LAMG</a></li>';
			echo '</ul>';
			break;
	}
	/*
	 * Two
	 */
	switch ($two_area) {
		case 'one':
			echo '<ul id="mtp-sticky-bar-one" class="mtp-sticky-bar-menu">';
			echo '<li id="mtp-sticky-bar-lamg"><a class="ir" href="'.$one_logo_link.'" rel="me" title="Headline comes here">LAMG</a></li>';
			echo '</ul>';
			break;
		case 'three':
			if ($three_show == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-three" class="mtp-sticky-bar-menu">';
				echo '<li><a href="#">Netvibes</a></li>';
				echo '</ul>';
			}
			break;
		case 'four':
			$lamgtext = $four_link_text ? $four_link_text : 'LAMG Blog Network';
			if (has_nav_menu('sticky_bar_four')) { wp_nav_menu(array('container'=>'','menu_class'=>'mtp-sticky-bar-menu','menu_id'=>'mtp-sticky-bar-four','theme_location'=>'sticky_bar_four', 'items_wrap' => '<ul id="%1$s" class="%2$s"><li class="lamg-network"><a href="#">'.$lamgtext.'</a></li>%3$s</ul>')); }
			break;
		case 'five':
			if ($five_shop == 'yes' || $five_forum == 'yes' || $newsletter == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-five" class="mtp-sticky-bar-menu">';
				if ($five_shop == 'yes') { echo '<li><a href="'.$five_shop_link.'" target="_blank">'.$five_shop_link_text.'</a></li>'; }
				if ($five_forum == 'yes') { echo '<li><a href="'.$five_forum_link.'">'.$five_forum_link_text.'</a></li>'; }
				if ($newsletter == 'yes') { echo '<li><a href="#">'.$newsletter_link_text.'</a><ul class="sub-menu"><li>'; if(function_exists('mtp_newsletter_form')) mtp_newsletter_form(); echo '</li></ul></li>'; }
				echo '</ul>';
			}
			break;
		case 'six':
			echo '<ul id="mtp-sticky-bar-six" class="mtp-sticky-bar-menu">';
			echo '<li><a href="#" id="add-bookmark" rel="sidebar">'.$six_bookmark.'</a></li>';
			echo '</ul>';
			break;
		case 'seven':
			echo '<ul id="mtp-sticky-bar-seven" class="mtp-sticky-bar-menu">';
			echo '<li><a class="ir" href="'.$seven_facebook_link.'" id="f" rel="me" title="Facebook" target="_blank">Facebook</a></li>';
			echo '<li><a class="ir" href="'.$seven_twitter_link.'" id="t" rel="me" title="Twitter" target="_blank">Twitter</a></li>';
			echo '<li><a class="ir" href="'.$seven_gplus_link.'" id="g" rel="me" title="Google+" target="_blank">Google+</a></li>';
			echo '<li><a class="ir" href="'.$seven_pinterest_link.'" id="p" rel="me" title="Pinterest" target="_blank">Pinterest</a></li>';
			echo '<li><a class="ir" href="'.$seven_rss_link.'" id="r" rel="me" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			echo '</ul>';
			break;
		case 'eight':
			echo '<ul id="mtp-sticky-bar-eight" class="mtp-sticky-bar-menu">';
			echo '<li><a class="mail" href="mailto:?subject='.$eight_mail_subject.'&body='.$eight_mail_body.'">'.$eight_friends.'</a></li>';
			echo '</ul>';
			break;
		
		default:
			echo '<ul id="mtp-sticky-bar-two" class="mtp-sticky-bar-menu">';
			$cb_title = $two_site_title ? $two_site_title : get_bloginfo('title');
			echo '<li><a href="#">'.$cb_title.'</a>';
			if ($two_site_description)
			{
				echo '<ul class="sub-menu"><li>';
				echo $two_site_description;
				echo '</li></ul>';
			}
			echo '</li>';
			echo '</ul>';
			break;
	}
	/*
	 * Three
	 */
/*	switch ($three_area) {
		case 'one':
			echo '<ul id="mtp-sticky-bar-one" class="mtp-sticky-bar-menu">';
			echo '<li id="mtp-sticky-bar-lamg"><a class="ir" href="'.$one_logo_link.'" rel="me" title="Headline comes here">LAMG</a></li>';
			echo '</ul>';
			break;
		case 'two':
			echo '<ul id="mtp-sticky-bar-two" class="mtp-sticky-bar-menu">';
			$cb_title = $two_site_title ? $two_site_title : get_bloginfo('title');
			echo '<li><a href="#">'.$cb_title.'</a>';
			if ($two_site_description)
			{
				echo '<ul class="sub-menu"><li>';
				echo $two_site_description;
				echo '</li></ul>';
			}
			echo '</li>';
			echo '</ul>';
			break;
		case 'four':
			$lamgtext = $four_link_text ? $four_link_text : 'LAMG Blog Network';
			if (has_nav_menu('sticky_bar_four')) { wp_nav_menu(array('container'=>'','menu_class'=>'mtp-sticky-bar-menu','menu_id'=>'mtp-sticky-bar-four','theme_location'=>'sticky_bar_four', 'items_wrap' => '<ul id="%1$s" class="%2$s"><li class="lamg-network"><a href="#">'.$lamgtext.'</a></li>%3$s</ul>')); }
			break;
		case 'five':
			if ($five_shop == 'yes' || $five_forum == 'yes' || $newsletter == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-five" class="mtp-sticky-bar-menu">';
				if ($five_shop == 'yes') { echo '<li><a href="'.$five_shop_link.'" target="_blank">'.$five_shop_link_text.'</a></li>'; }
				if ($five_forum == 'yes') { echo '<li><a href="'.$five_forum_link.'">'.$five_forum_link_text.'</a></li>'; }
				if ($newsletter == 'yes') { echo '<li><a href="#">'.$newsletter_link_text.'</a><ul class="sub-menu"><li>'; if(function_exists('mtp_newsletter_form')) mtp_newsletter_form(); echo '</li></ul></li>'; }
				echo '</ul>';
			}
			break;
		case 'six':
			echo '<ul id="mtp-sticky-bar-six" class="mtp-sticky-bar-menu">';
			echo '<li><a href="#" id="add-bookmark" rel="sidebar">'.$six_bookmark.'</a></li>';
			echo '</ul>';
			break;
		case 'seven':
			echo '<ul id="mtp-sticky-bar-seven" class="mtp-sticky-bar-menu">';
			echo '<li><a class="ir" href="'.$seven_facebook_link.'" id="f" rel="me" title="Facebook" target="_blank">Facebook</a></li>';
			echo '<li><a class="ir" href="'.$seven_twitter_link.'" id="t" rel="me" title="Twitter" target="_blank">Twitter</a></li>';
			echo '<li><a class="ir" href="'.$seven_gplus_link.'" id="g" rel="me" title="Google+" target="_blank">Google+</a></li>';
			echo '<li><a class="ir" href="'.$seven_pinterest_link.'" id="p" rel="me" title="Pinterest" target="_blank">Pinterest</a></li>';
			echo '<li><a class="ir" href="'.$seven_rss_link.'" id="r" rel="me" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			echo '</ul>';
			break;
		case 'eight':
			echo '<ul id="mtp-sticky-bar-eight" class="mtp-sticky-bar-menu">';
			echo '<li><a class="mail" href="mailto:?subject='.$eight_mail_subject.'&body='.$eight_mail_body.'">'.$eight_friends.'</a></li>';
			echo '</ul>';
			break;
		
		default:
			if ($three_show == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-three" class="mtp-sticky-bar-menu">';
				echo '<li><a href="#">Netvibes</a></li>';
				echo '</ul>';
			}
			break;
	}
*/
	/*
	 * Four
	 */
	switch ($four_area) {
		case 'one':
			echo '<ul id="mtp-sticky-bar-one" class="mtp-sticky-bar-menu">';
			echo '<li id="mtp-sticky-bar-lamg"><a class="ir" href="'.$one_logo_link.'" rel="me" title="Headline comes here">LAMG</a></li>';
			echo '</ul>';
			break;
		case 'two':
			echo '<ul id="mtp-sticky-bar-two" class="mtp-sticky-bar-menu">';
			$cb_title = $two_site_title ? $two_site_title : get_bloginfo('title');
			echo '<li><a href="#">'.$cb_title.'</a>';
			if ($two_site_description)
			{
				echo '<ul class="sub-menu"><li>';
				echo $two_site_description;
				echo '</li></ul>';
			}
			echo '</li>';
			echo '</ul>';
			break;
		case 'three':
			if ($three_show == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-three" class="mtp-sticky-bar-menu">';
				echo '<li><a href="#">Netvibes</a></li>';
				echo '</ul>';
			}
			break;
		case 'five':
			if ($five_shop == 'yes' || $five_forum == 'yes' || $newsletter == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-five" class="mtp-sticky-bar-menu">';
				if ($five_shop == 'yes') { echo '<li><a href="'.$five_shop_link.'" target="_blank">'.$five_shop_link_text.'</a></li>'; }
				if ($five_forum == 'yes') { echo '<li><a href="'.$five_forum_link.'">'.$five_forum_link_text.'</a></li>'; }
				if ($newsletter == 'yes') { echo '<li><a href="#">'.$newsletter_link_text.'</a><ul class="sub-menu"><li>'; if(function_exists('mtp_newsletter_form')) mtp_newsletter_form(); echo '</li></ul></li>'; }
				echo '</ul>';
			}
			break;
		case 'six':
			echo '<ul id="mtp-sticky-bar-six" class="mtp-sticky-bar-menu">';
			echo '<li><a href="#" id="add-bookmark" rel="sidebar">'.$six_bookmark.'</a></li>';
			echo '</ul>';
			break;
		case 'seven':
			echo '<ul id="mtp-sticky-bar-seven" class="mtp-sticky-bar-menu">';
			echo '<li><a class="ir" href="'.$seven_facebook_link.'" id="f" rel="me" title="Facebook" target="_blank">Facebook</a></li>';
			echo '<li><a class="ir" href="'.$seven_twitter_link.'" id="t" rel="me" title="Twitter" target="_blank">Twitter</a></li>';
			echo '<li><a class="ir" href="'.$seven_gplus_link.'" id="g" rel="me" title="Google+" target="_blank">Google+</a></li>';
			echo '<li><a class="ir" href="'.$seven_pinterest_link.'" id="p" rel="me" title="Pinterest" target="_blank">Pinterest</a></li>';
			echo '<li><a class="ir" href="'.$seven_rss_link.'" id="r" rel="me" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			echo '</ul>';
			break;
		case 'eight':
			echo '<ul id="mtp-sticky-bar-eight" class="mtp-sticky-bar-menu">';
			echo '<li><a class="mail" href="mailto:?subject='.$eight_mail_subject.'&body='.$eight_mail_body.'">'.$eight_friends.'</a></li>';
			echo '</ul>';
			break;
		
		default:
			$lamgtext = $four_link_text ? $four_link_text : 'LAMG Blog Network';
			if (has_nav_menu('sticky_bar_four')) { wp_nav_menu(array('container'=>'','menu_class'=>'mtp-sticky-bar-menu','menu_id'=>'mtp-sticky-bar-four','theme_location'=>'sticky_bar_four', 'items_wrap' => '<ul id="%1$s" class="%2$s"><li class="lamg-network"><a href="#">'.$lamgtext.'</a></li>%3$s</ul>')); }
			break;
	}
	/*
	 * Five
	 */
	switch ($five_area) {
		case 'one':
			echo '<ul id="mtp-sticky-bar-one" class="mtp-sticky-bar-menu">';
			echo '<li id="mtp-sticky-bar-lamg"><a class="ir" href="'.$one_logo_link.'" rel="me" title="Headline comes here">LAMG</a></li>';
			echo '</ul>';
			break;
		case 'two':
			echo '<ul id="mtp-sticky-bar-two" class="mtp-sticky-bar-menu">';
			$cb_title = $two_site_title ? $two_site_title : get_bloginfo('title');
			echo '<li><a href="#">'.$cb_title.'</a>';
			if ($two_site_description)
			{
				echo '<ul class="sub-menu"><li>';
				echo $two_site_description;
				echo '</li></ul>';
			}
			echo '</li>';
			echo '</ul>';
			break;
		case 'three':
			if ($three_show == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-three" class="mtp-sticky-bar-menu">';
				echo '<li><a href="#">Netvibes</a></li>';
				echo '</ul>';
			}
			break;
		case 'four':
			$lamgtext = $four_link_text ? $four_link_text : 'LAMG Blog Network';
			if (has_nav_menu('sticky_bar_four')) { wp_nav_menu(array('container'=>'','menu_class'=>'mtp-sticky-bar-menu','menu_id'=>'mtp-sticky-bar-four','theme_location'=>'sticky_bar_four', 'items_wrap' => '<ul id="%1$s" class="%2$s"><li class="lamg-network"><a href="#">'.$lamgtext.'</a></li>%3$s</ul>')); }
			break;
		case 'six':
			echo '<ul id="mtp-sticky-bar-six" class="mtp-sticky-bar-menu">';
			echo '<li><a href="#" id="add-bookmark" rel="sidebar">'.$six_bookmark.'</a></li>';
			echo '</ul>';
			break;
		case 'seven':
			echo '<ul id="mtp-sticky-bar-seven" class="mtp-sticky-bar-menu">';
			echo '<li><a class="ir" href="'.$seven_facebook_link.'" id="f" rel="me" title="Facebook" target="_blank">Facebook</a></li>';
			echo '<li><a class="ir" href="'.$seven_twitter_link.'" id="t" rel="me" title="Twitter" target="_blank">Twitter</a></li>';
			echo '<li><a class="ir" href="'.$seven_gplus_link.'" id="g" rel="me" title="Google+" target="_blank">Google+</a></li>';
			echo '<li><a class="ir" href="'.$seven_pinterest_link.'" id="p" rel="me" title="Pinterest" target="_blank">Pinterest</a></li>';
			echo '<li><a class="ir" href="'.$seven_rss_link.'" id="r" rel="me" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			echo '</ul>';
			break;
		case 'eight':
			echo '<ul id="mtp-sticky-bar-eight" class="mtp-sticky-bar-menu">';
			echo '<li><a class="mail" href="mailto:?subject='.$eight_mail_subject.'&body='.$eight_mail_body.'">'.$eight_friends.'</a></li>';
			echo '</ul>';
			break;
		
		default:
			if ($five_shop == 'yes' || $five_forum == 'yes' || $newsletter == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-five" class="mtp-sticky-bar-menu">';
				if ($five_shop == 'yes') { echo '<li><a style="padding-left: 25px" href="'.$five_shop_link.'" target="_blank">'.$five_shop_link_text.'</a></li>'; }
				if ($five_forum == 'yes') { echo '<li><a href="'.$five_forum_link.'">'.$five_forum_link_text.'</a></li>'; }
				if ($newsletter == 'yes') { echo '<li><a href="#">'.$newsletter_link_text.'</a><ul class="sub-menu"><li>'; if(function_exists('mtp_newsletter_form')) mtp_newsletter_form(); echo '</li></ul></li>'; }
				echo '</ul>';
			}
			break;
	}
	/*
	 * Six
	 */
	switch ($six_area) {
		case 'one':
			echo '<ul id="mtp-sticky-bar-one" class="mtp-sticky-bar-menu">';
			echo '<li id="mtp-sticky-bar-lamg"><a class="ir" href="'.$one_logo_link.'" rel="me" title="Headline comes here">LAMG</a></li>';
			echo '</ul>';
			break;
		case 'two':
			echo '<ul id="mtp-sticky-bar-two" class="mtp-sticky-bar-menu">';
			$cb_title = $two_site_title ? $two_site_title : get_bloginfo('title');
			echo '<li><a href="#">'.$cb_title.'</a>';
			if ($two_site_description)
			{
				echo '<ul class="sub-menu"><li>';
				echo $two_site_description;
				echo '</li></ul>';
			}
			echo '</li>';
			echo '</ul>';
			break;
		case 'three':
			if ($three_show == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-three" class="mtp-sticky-bar-menu">';
				echo '<li><a href="#">Netvibes</a></li>';
				echo '</ul>';
			}
			break;
		case 'four':
			$lamgtext = $four_link_text ? $four_link_text : 'LAMG Blog Network';
			if (has_nav_menu('sticky_bar_four')) { wp_nav_menu(array('container'=>'','menu_class'=>'mtp-sticky-bar-menu','menu_id'=>'mtp-sticky-bar-four','theme_location'=>'sticky_bar_four', 'items_wrap' => '<ul id="%1$s" class="%2$s"><li class="lamg-network"><a href="#">'.$lamgtext.'</a></li>%3$s</ul>')); }
			break;
		case 'five':
			if ($five_shop == 'yes' || $five_forum == 'yes' || $newsletter == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-five" class="mtp-sticky-bar-menu">';
				if ($five_shop == 'yes') { echo '<li><a href="'.$five_shop_link.'" target="_blank">'.$five_shop_link_text.'</a></li>'; }
				if ($five_forum == 'yes') { echo '<li><a href="'.$five_forum_link.'">'.$five_forum_link_text.'</a></li>'; }
				if ($newsletter == 'yes') { echo '<li><a href="#">'.$newsletter_link_text.'</a><ul class="sub-menu"><li>'; if(function_exists('mtp_newsletter_form')) mtp_newsletter_form(); echo '</li></ul></li>'; }
				echo '</ul>';
			}
			break;
		case 'seven':
			echo '<ul id="mtp-sticky-bar-seven" class="mtp-sticky-bar-menu">';
			echo '<li><a class="ir" href="'.$seven_facebook_link.'" id="f" rel="me" title="Facebook" target="_blank">Facebook</a></li>';
			echo '<li><a class="ir" href="'.$seven_twitter_link.'" id="t" rel="me" title="Twitter" target="_blank">Twitter</a></li>';
			echo '<li><a class="ir" href="'.$seven_gplus_link.'" id="g" rel="me" title="Google+" target="_blank">Google+</a></li>';
			echo '<li><a class="ir" href="'.$seven_pinterest_link.'" id="p" rel="me" title="Pinterest" target="_blank">Pinterest</a></li>';
			echo '<li><a class="ir" href="'.$seven_rss_link.'" id="r" rel="me" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			echo '</ul>';
			break;
		case 'eight':
			echo '<ul id="mtp-sticky-bar-eight" class="mtp-sticky-bar-menu">';
			echo '<li><a class="mail" href="mailto:?subject='.$eight_mail_subject.'&body='.$eight_mail_body.'">'.$eight_friends.'</a></li>';
			echo '</ul>';
			break;
		
		default:
			echo '<ul id="mtp-sticky-bar-six" class="mtp-sticky-bar-menu">';
			echo '<li><a href="#" id="add-bookmark" rel="sidebar">'.$six_bookmark.'</a></li>';
			echo '</ul>';
			break;
	}
	/*
	 * Seven
	 */
	switch ($seven_area) {
		case 'one':
			echo '<ul id="mtp-sticky-bar-one" class="mtp-sticky-bar-menu">';
			echo '<li id="mtp-sticky-bar-lamg"><a class="ir" href="'.$one_logo_link.'" rel="me" title="Headline comes here">LAMG</a></li>';
			echo '</ul>';
			break;
		case 'two':
			echo '<ul id="mtp-sticky-bar-two" class="mtp-sticky-bar-menu">';
			$cb_title = $two_site_title ? $two_site_title : get_bloginfo('title');
			echo '<li><a href="#">'.$cb_title.'</a>';
			if ($two_site_description)
			{
				echo '<ul class="sub-menu"><li>';
				echo $two_site_description;
				echo '</li></ul>';
			}
			echo '</li>';
			echo '</ul>';
			break;
		case 'three':
			if ($three_show == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-three" class="mtp-sticky-bar-menu">';
				echo '<li><a href="#">Netvibes</a></li>';
				echo '</ul>';
			}
			break;
		case 'four':
			$lamgtext = $four_link_text ? $four_link_text : 'LAMG Blog Network';
			if (has_nav_menu('sticky_bar_four')) { wp_nav_menu(array('container'=>'','menu_class'=>'mtp-sticky-bar-menu','menu_id'=>'mtp-sticky-bar-four','theme_location'=>'sticky_bar_four', 'items_wrap' => '<ul id="%1$s" class="%2$s"><li class="lamg-network"><a href="#">'.$lamgtext.'</a></li>%3$s</ul>')); }
			break;
		case 'five':
			if ($five_shop == 'yes' || $five_forum == 'yes' || $newsletter == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-five" class="mtp-sticky-bar-menu">';
				if ($five_shop == 'yes') { echo '<li><a href="'.$five_shop_link.'" target="_blank">'.$five_shop_link_text.'</a></li>'; }
				if ($five_forum == 'yes') { echo '<li><a href="'.$five_forum_link.'">'.$five_forum_link_text.'</a></li>'; }
				if ($newsletter == 'yes') { echo '<li><a href="#">'.$newsletter_link_text.'</a><ul class="sub-menu"><li>'; if(function_exists('mtp_newsletter_form')) mtp_newsletter_form(); echo '</li></ul></li>'; }
				echo '</ul>';
			}
			break;
		case 'six':
			echo '<ul id="mtp-sticky-bar-six" class="mtp-sticky-bar-menu">';
			echo '<li><a href="#" id="add-bookmark" rel="sidebar">'.$six_bookmark.'</a></li>';
			echo '</ul>';
			break;
		case 'eight':
			echo '<ul id="mtp-sticky-bar-eight" class="mtp-sticky-bar-menu">';
			echo '<li><a class="mail" href="mailto:?subject='.$eight_mail_subject.'&body='.$eight_mail_body.'">'.$eight_friends.'</a></li>';
			echo '</ul>';
			break;
		
		default:
			echo '<ul id="mtp-sticky-bar-seven" class="mtp-sticky-bar-menu">';
			echo '<li><a class="ir" href="'.$seven_facebook_link.'" id="f" rel="me" title="Facebook" target="_blank">Facebook</a></li>';
			echo '<li><a class="ir" href="'.$seven_twitter_link.'" id="t" rel="me" title="Twitter" target="_blank">Twitter</a></li>';
			echo '<li><a class="ir" href="'.$seven_gplus_link.'" id="g" rel="me" title="Google+" target="_blank">Google+</a></li>';
			echo '<li><a class="ir" href="'.$seven_pinterest_link.'" id="p" rel="me" title="Pinterest" target="_blank">Pinterest</a></li>';
			echo '<li><a class="ir" href="'.$seven_rss_link.'" id="r" rel="me" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			echo '</ul>';
			break;
	}
	/*
	 * Eight
	 */
	switch ($eight_area) {
		case 'one':
			echo '<ul id="mtp-sticky-bar-one" class="mtp-sticky-bar-menu">';
			echo '<li id="mtp-sticky-bar-lamg"><a class="ir" href="'.$one_logo_link.'" rel="me" title="Headline comes here">LAMG</a></li>';
			echo '</ul>';
			break;
		case 'two':
			echo '<ul id="mtp-sticky-bar-two" class="mtp-sticky-bar-menu">';
			$cb_title = $two_site_title ? $two_site_title : get_bloginfo('title');
			echo '<li><a href="#">'.$cb_title.'</a>';
			if ($two_site_description)
			{
				echo '<ul class="sub-menu"><li>';
				echo $two_site_description;
				echo '</li></ul>';
			}
			echo '</li>';
			echo '</ul>';
			break;
		case 'three':
			if ($three_show == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-three" class="mtp-sticky-bar-menu">';
				echo '<li><a href="#">Netvibes</a></li>';
				echo '</ul>';
			}
			break;
		case 'four':
			$lamgtext = $four_link_text ? $four_link_text : 'LAMG Blog Network';
			if (has_nav_menu('sticky_bar_four')) { wp_nav_menu(array('container'=>'','menu_class'=>'mtp-sticky-bar-menu','menu_id'=>'mtp-sticky-bar-four','theme_location'=>'sticky_bar_four', 'items_wrap' => '<ul id="%1$s" class="%2$s"><li class="lamg-network"><a href="#">'.$lamgtext.'</a></li>%3$s</ul>')); }
			break;
		case 'five':
			if ($five_shop == 'yes' || $five_forum == 'yes' || $newsletter == 'yes')
			{
				echo '<ul id="mtp-sticky-bar-five" class="mtp-sticky-bar-menu">';
				if ($five_shop == 'yes') { echo '<li><a href="'.$five_shop_link.'" target="_blank">'.$five_shop_link_text.'</a></li>'; }
				if ($five_forum == 'yes') { echo '<li><a href="'.$five_forum_link.'">'.$five_forum_link_text.'</a></li>'; }
				if ($newsletter == 'yes') { echo '<li><a href="#">'.$newsletter_link_text.'</a><ul class="sub-menu"><li>'; if(function_exists('mtp_newsletter_form')) mtp_newsletter_form(); echo '</li></ul></li>'; }
				echo '</ul>';
			}
			break;
		case 'six':
			echo '<ul id="mtp-sticky-bar-six" class="mtp-sticky-bar-menu">';
			echo '<li><a href="#" id="add-bookmark" rel="sidebar">'.$six_bookmark.'</a></li>';
			echo '</ul>';
			break;
		case 'seven':
			echo '<ul id="mtp-sticky-bar-seven" class="mtp-sticky-bar-menu">';
			echo '<li><a class="ir" href="'.$seven_facebook_link.'" id="f" rel="me" title="Facebook" target="_blank">Facebook</a></li>';
			echo '<li><a class="ir" href="'.$seven_twitter_link.'" id="t" rel="me" title="Twitter" target="_blank">Twitter</a></li>';
			echo '<li><a class="ir" href="'.$seven_gplus_link.'" id="g" rel="me" title="Google+" target="_blank">Google+</a></li>';
			echo '<li><a class="ir" href="'.$seven_pinterest_link.'" id="p" rel="me" title="Pinterest" target="_blank">Pinterest</a></li>';
			echo '<li><a class="ir" href="'.$seven_rss_link.'" id="r" rel="me" title="RSS Feed" target="_blank">RSS Feed</a></li>';
			echo '</ul>';
			break;
		
		default:
			echo '<ul id="mtp-sticky-bar-eight" class="mtp-sticky-bar-menu">';
			echo '<li><a class="mail" href="mailto:?subject='.$eight_mail_subject.'&body='.$eight_mail_body.'">'.$eight_friends.'</a></li>';
			echo '</ul>';
			break;
	}
	?>
		<!--ul id="mtp-sticky-bar-nine" class="mtp-sticky-bar-menu">
			<li><a href="#" id="mtp-sticky-bar-close">X</a></li>
		</ul-->
	</div><!-- END Flexible -->
	</div><!-- END mtp-sticky-bar -->
	<span id="mtp-sticky-bar-ten"><a href="#">&darr;</a></span>
	<style type="text/css">
	#mtp-sticky-bar * { border: 0; font: inherit; font-size: 100%; list-style: none; margin: 0; padding: 0; quotes: none; vertical-align: 0; }
	#mtp-sticky-bar {
		background: <?php echo $background_color; ?>;
		clear: both;
		color: <?php echo $color; ?>;
		font: normal 13px/1.5 <?php echo $font_family; ?>;
		height: 40px;
		position: fixed;
		text-shadow: 1px 1px 0 rgba(0,0,0,.2);
		width: 100%;
		z-index: 99999;
	}
	#mtp-sticky-bar input,
	#mtp-sticky-bar textarea { font: normal 13px/1.5 <?php echo $font_family; ?>; }
	#mtp-sticky-bar checkbox { height: 13px; width: 13px; }
	#mtp-sticky-bar a {
		color: <?php echo $anchor_color; ?>;
		text-decoration: <?php echo $anchor_text_decoration; ?>;
	}
	#mtp-sticky-bar a:hover {
		color: <?php echo $anchor_hover_color; ?>;
		text-decoration: <?php echo $anchor_hover_text_decoration; ?>;
	}
	#mtp-sticky-bar ul a { font-weight: normal; }
	#mtp-sticky-bar-one,
	#mtp-sticky-bar-two,
	#mtp-sticky-bar-three,
	#mtp-sticky-bar-four,
	#mtp-sticky-bar-five,
	#mtp-sticky-bar-six,
	#mtp-sticky-bar-seven,
	#mtp-sticky-bar-eight {
		float: left;
		position: relative;
	}
	#flexible  {
		margin: 0 auto;
	}
	#mtp-sticky-bar-nine {
		float: right;
		position: relative;
	}
	#mtp-sticky-bar-ten {
		display: none;
		position: fixed;
		right: 0;
		text-shadow: 1px 1px 0 rgba(0,0,0,.2);
		top: 0;
	}
	#mtp-sticky-bar-ten a {
		background: #f92672;
		color: #fff;
		display: block;
		font-weight: bold;
		height: 19px;
		padding: 10px;
	}
	#mtp-sticky-bar .mtp-sticky-bar-menu {
		border-left: 1px solid rgba(0,0,0,.4);
		border-right: 1px solid rgba(255,255,255,.2);
	}
	#mtp-sticky-bar .mtp-sticky-bar-menu:first-child { border-left: none; }
	#mtp-sticky-bar .mtp-sticky-bar-menu:last-child { border: none; }
	#mtp-sticky-bar .mtp-sticky-bar-menu:nth-child(8) { border-right: none; }
	#mtp-sticky-bar .mtp-sticky-bar-menu > li { display: inline-block; }
	#mtp-sticky-bar .mtp-sticky-bar-menu > li:hover > .sub-menu {
		display: block;
		opacity: 1;
	}
	#mtp-sticky-bar .mtp-sticky-bar-menu > li:hover > a { color: <?php echo $anchor_hover_color; ?>; }
	#mtp-sticky-bar .mtp-sticky-bar-menu > li > a {
		display: block;
		padding: 10px;
	}
	#mtp-sticky-bar .mtp-sticky-bar-menu > li > a:hover {
		
	}
	#mtp-sticky-bar .sub-menu {
		background: <?php echo $background_color; ?>;
		display: none;
		opacity: 0;
		padding: 10px;
		position: absolute;
		top: 100%;
		width: 220px;
		z-index: 99999;
	}
	#mtp-sticky-bar h1, #mtp-sticky-bar h2,  #mtp-sticky-bar h3,  #mtp-sticky-bar h4,  #mtp-sticky-bar h5,  #mtp-sticky-bar h6,  #mtp-sticky-bar p { margin: 10px 0; }
	#mtp-sticky-bar h1, #mtp-sticky-bar h2,  #mtp-sticky-bar h3,  #mtp-sticky-bar h4,  #mtp-sticky-bar h5,  #mtp-sticky-bar h6 { color: <?php echo $anchor_hover_color; ?>; }
	#mtp-sticky-bar h1 { font-size: 1.923em; }
	#mtp-sticky-bar h2 { font-size: 1.769em; }
	#mtp-sticky-bar h3 { font-size: 1.615em; }
	#mtp-sticky-bar h4 { font-size: 1.462em; }
	#mtp-sticky-bar h5 { font-size: 1.308em; }
	#mtp-sticky-bar h6 { font-size: 1.154em; }
	#mtp-sticky-bar .alignnone { margin: 0 0 20px; }
	#mtp-sticky-bar .alignleft {
		float: left;
		margin: 0 20px 20px 0;
	}
	#mtp-sticky-bar .aligncenter { margin: 0 auto 20px; }
	#mtp-sticky-bar .alignright {
		float: right;
		margin: 0 0 20px 20px;
	}
	#mtp-sticky-bar .ir {
		background-color: transparent;
		border: 0;
		color: transparent;
		font: 0/0 a;
		text-shadow: none;
	}
	#mtp-sticky-bar-lamg a {
		background: url(/wp-content/plugins/mtp-sticky-bar/images/lamg-logo.png) center center no-repeat scroll;
		height: 19px;
		width: 30px;
	}
	#mtp-sticky-bar-two .sub-menu {
		padding: 0 10px;
		width: 460px;
	}
	.no-js #mtp-sticky-bar-six { display: none; }
	#mtp-sticky-bar-seven { padding: 0px 5px;}
	#f, #t, #g, #p, #r {
		height: 15px;
		width: 5px;
	}
	#f { background: url(/wp-content/plugins/mtp-sticky-bar/images/facebook-icon.png) center 6px no-repeat scroll; }
	#t { background: url(/wp-content/plugins/mtp-sticky-bar/images/twitter-icon.png) center 6px no-repeat scroll; }
	#g { background: url(/wp-content/plugins/mtp-sticky-bar/images/google-icon.png) center 6px no-repeat scroll; }
	#p { background: url(/wp-content/plugins/mtp-sticky-bar/images/pinterest-icon.png) center 6px no-repeat scroll; }
	#r { background: url(/wp-content/plugins/mtp-sticky-bar/images/rss-icon.png) center 6px no-repeat scroll; }
	#mtp-sticky-bar-one   { <?php if ($one_padding_left) { echo 'padding-left:'.$one_padding_left.'px;'; } if ($one_padding_right) { echo 'padding-right:'.$one_padding_right.'px;'; } ?> }
	#mtp-sticky-bar-two   { <?php if ($two_padding_left) { echo 'padding-left:'.$two_padding_left.'px;'; } if ($two_padding_right) { echo 'padding-right:'.$two_padding_right.'px;'; } ?> }
	#mtp-sticky-bar-three { <?php if ($three_padding_left) { echo 'padding-left:'.$three_padding_left.'px;'; } if ($three_padding_right) { echo 'padding-right:'.$three_padding_right.'px;'; } ?> }
	#mtp-sticky-bar-four  { <?php if ($four_padding_left) { echo 'padding-left:'.$four_padding_left.'px;'; } if ($four_padding_right) { echo 'padding-right:'.$four_padding_right.'px;'; } ?> }
	#mtp-sticky-bar-five  { 
		background: url(/wp-content/plugins/mtp-sticky-bar/images/shopping_car.png) 5px no-repeat scroll;
		<?php if ($five_padding_left) { echo 'padding-left:'.$five_padding_left.'px;'; } if ($five_padding_right) { echo 'padding-right:'.$five_padding_right.'px;'; } ?>
		 }
	#mtp-sticky-bar-six   { <?php if ($six_padding_left) { echo 'padding-left:'.$six_padding_left.'px;'; } if ($six_padding_right) { echo 'padding-right:'.$six_padding_right.'px;'; } ?> }
	#mtp-sticky-bar-seven { <?php if ($seven_padding_left) { echo 'padding-left:'.$seven_padding_left.'px;'; } if ($seven_padding_right) { echo 'padding-right:'.$seven_padding_right.'px;'; } ?> }
	#mtp-sticky-bar-eight { <?php if ($eight_padding_left) { echo 'padding-left:'.$eight_padding_left.'px;'; } if ($eight_padding_right) { echo 'padding-right:'.$eight_padding_right.'px;'; } ?> }
	//#mtp-sticky-bar-nine  { <?php if ($eight_padding_left) { echo 'padding-left:'.$eight_padding_left.'px;'; } if ($eight_padding_right) { echo 'padding-right:'.$eight_padding_right.'px;'; } ?> }
	#mtp-sticky-bar .widget_mailchimpsf_widget { margin-top: -10px; }
	#mtp-sticky-bar .widget_mailchimpsf_widget .mc_interests_header,
	#mtp-sticky-bar .widget_mailchimpsf_widget .mergeRow,
	#mtp-sticky-bar .widget_mailchimpsf_widget label { display: none; }
	#mtp-sticky-bar .widget_mailchimpsf_widget .close {
		font-size: 9px;
		margin-bottom: 0;
	}
	#mtp-sticky-bar .newsletter_form #newsletter_email,
	#mtp-sticky-bar .newsletter_form #submitNewsletter {
		background: <?php echo $color; ?>;
		color: <?php echo $background_color; ?>;
		padding: 5px;
	}
	#mtp-sticky-bar .newsletter_form #newsletter_email{
		max-width: 150px;
		float: left;
	}
	#mtp-sticky-bar .newsletter_form #submitNewsletter{
		margin: 10px auto;
	}
	#mtp-sticky-bar .newsletter_form #newsletter_email:focus,
	#mtp-sticky-bar .newsletter_form #newsletter_email:hover { background: <?php echo $anchor_hover_color; ?> }
	#mtp-sticky-bar .newsletter_form #submitNewsletter:hover {
		background: #222;
		color: <?php echo $anchor_hover_color; ?>;
		cursor: pointer;
	}
	#mtp-sticky-bar .clear {
		clear: both;
		display: block;
		height: 0;
		overflow: hidden;
		visibility: hidden;
		width: 0;
	}
	iframe {
		border: none;
		/*display: block;
		width: 100%;*/
	}
	html, body {
		height: 100%;
		margin: 0;
		padding: 0;
		width: 100%;
	}
	#header_area,
	#container { padding-top: 39px; }

	#mtp-sticky-bar #mtp-sticky-bar-four > li.lamg-network{
		display: none;
	}

	/* responsive */
	@media only screen and (max-width: 1024px) {
		#mtp-sticky-bar-six,
		#mtp-sticky-bar-eight{
			display:none;
		}
		#mtp-sticky-bar #mtp-sticky-bar-four.mtp-sticky-bar-menu{
			background-color: #000;
		}
		#mtp-sticky-bar #mtp-sticky-bar-four.mtp-sticky-bar-menu > li{
			display: none;
		}
		#mtp-sticky-bar #mtp-sticky-bar-four > li.lamg-network{
			display: block;
		}
		#mtp-sticky-bar #mtp-sticky-bar-seven{
			border-right: none;
		}
		#mtp-sticky-bar #mtp-sticky-bar-four.mtp-sticky-bar-menu:hover > li{
			display: block;
		}
		#mtp-sticky-bar-two .sub-menu{
			width: 230px;
		}
	}
	</style> 
	<script type="text/javascript">
	(function($){
		var $one = $('#mtp-sticky-bar-one').outerWidth(),
			$two = $('#mtp-sticky-bar-two').outerWidth(),
			$four = $('#mtp-sticky-bar-four').outerWidth(),
			$five = $('#mtp-sticky-bar-five').outerWidth(),
			$six = $('#mtp-sticky-bar-six').outerWidth(),
			$seven = $('#mtp-sticky-bar-seven').outerWidth(),
			$eight = $('#mtp-sticky-bar-eight').outerWidth(),
			$viewport = $(window).width();
		$all = $one + $two + $four + $five + $six + $seven + $eight;
		if( $viewport <= 1024) //responsive check
			$all = $one + $two + $four + $five + $seven;
		$('#flexible').css('width',$all);
	})(jQuery);
	(function($){
		$('#add-bookmark').click(function(){
			if(window.sidebar){
				window.sidebar.addPanel(location.href,document.title,'');
			} else if(window.external && ('AddFavorite' in window.external)){
				window.external.AddFavorite(location.href,document.title);
			} else if(window.opera && window.print){
				this.title = document.title;
				return true;
			} else {
				alert('Your browser does not support this functionality at the moment!\n\nPlease press ctrl+D (Command+D for mac) to bookmark us.');
			}
		});
	})(jQuery);
	(function($){
		$('.lamg-network a, #mtp-sticky-bar-two a, #mtp-sticky-bar-five a').click(function(){
			return false;
		});
	})(jQuery);
	(function($){
		$('#mtp-sticky-bar-four li a[rel="contact"]').click(function(e){
			container = $('#container');
			src = $(this).prop('href');
			e.preventDefault();
			if (container.is('*')){
				container.remove();
				$(document.body).append('<iframe id="mtp-sticky-bar-site" frameborder="0" height="100%" marginheight="0" marginwidth="0" scrolling="yes" src='+src+' style="padding-top: 40px" width="100%" />');
			} else {
				$(document.body).find('iframe').remove();
				$(document.body).append('<iframe id="mtp-sticky-bar-site" frameborder="0" height="100%" marginheight="0" marginwidth="0" scrolling="yes" src='+src+' style="padding-top: 40px" width="100%" />');
			}
		});
	})(jQuery);
	/*(function($){
		$('#mtp-sticky-bar-nine a').click(function(e){
			e.preventDefault();
			$('#mtp-sticky-bar').slideUp();
			$('#mtp-sticky-bar-ten').show();
			$('#mtp-sticky-bar-site').css('padding-top','0');
		});
	})(jQuery);
	(function($){
		$('#mtp-sticky-bar-ten').click(function(e){
			e.preventDefault();
			$(this).hide();
			$('#mtp-sticky-bar').slideDown();
			$('#mtp-sticky-bar-site').css('padding-top','40px');
		});
	})(jQuery);*/
	(function($){
	})(jQuery);
	</script>
	<!--[if IE 9]><script type="text/javascript">
	(function($){
		var $one = $('#mtp-sticky-bar-one').outerWidth(),
			$two = $('#mtp-sticky-bar-two').outerWidth(),
			$four = $('#mtp-sticky-bar-four').outerWidth(),
			$five = $('#mtp-sticky-bar-five').outerWidth(),
			$six = $('#mtp-sticky-bar-six').outerWidth(),
			$seven = $('#mtp-sticky-bar-seven').outerWidth(),
			$eight = $('#mtp-sticky-bar-eight').outerWidth(),
			$all = $one + $two + $four + $five + $six + $seven + $eight;
			$all = $all + 10;
			$('#flexible').css('width',$all);
	})(jQuery);
	</script><![endif]-->
<?php }
// if (get_current_theme() == 'Thesis'){
	add_action('thesis_hook_before_html','mtp_sticky_bar_thbh', 1);
// } else {
// 	add_action('wp_head','mtp_sticky_bar_thbh', 1);
// }
?>
