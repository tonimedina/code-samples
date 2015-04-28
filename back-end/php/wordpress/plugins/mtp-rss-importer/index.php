<?php
/*
Plugin Name: MTP RSS Importer
Plugin URI: http://mt-performance.net
Description: Import RSS feed from other blogs.
Version: 1.1
Author: Toni Medina
Author URI: http://tonimedina.tumblr.com
License: GPL2
*/
$cat_ops = get_option('rss_importer_category_options');
$feed_ops = get_option('rss_importer_feed_options');
$add_cat_name = trim($_POST['add_cat_name']);
$rm_cat_name = trim($_POST['rm_cat_name']);
$add_feed_name = trim($_POST['add_feed_name']);
$add_feed_url = trim($_POST['add_feed_url']);
$add_feed_cat = trim($_POST['add_feed_cat']);
$add_feed_post = trim($_POST['add_feed_post']);
$add_feed_src = trim($_POST['add_feed_src']);
$add_feed_excerpt = trim($_POST['add_feed_excerpt']);
$add_feed_length = trim($_POST['add_feed_length']);
$add_feed_link = trim($_POST['add_feed_link']);
$rm_feed_url = trim($_POST['rm_feed_url']);
$deprecated = ' ';
$autoload = 'no';
require('rss-importer-shortcode.php');
require('rss-importer-update.php');
require('rss-importer-validate.php');
require('rss-importer-form.php');
require('rss-importer-feed.php');
class RssImporter
{
	public $cat_ops;
	public $feed_ops;
	public function __construct()
	{
		$this->cat_ops = get_option('rss_importer_category_options');
		$this->feed_ops = get_option('rss_importer_feed_options');
		$this->RssImporter_regadd_settings();
	}
	public function RssImporter_add_options_page()
	{
		add_options_page('RSS Importer Options Page','RSS Importer','edit_theme_options',__FILE__,array('RssImporter','RssImporter_add_options_page_cb'));
	}
	public function RssImporter_add_options_page_cb()
	{ ?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>RSS Importer Options Page</h2>
			<p>Put this shortcode on the page you wish to have the feed: <code>[rss category="Category Name"]</code></p>
			<?php
			settings_fields('RssImporter_option_group');
			do_settings_sections(__FILE__);
			?>
		</div>
	<?php }
	public function RssImporter_regadd_settings()
	{
		add_option('rss_importer_category_options');
		add_option('rss_importer_feed_options');
		add_settings_section('RssImporter_add_cat','Add New Category',array($this,'RssImporter_add_cat_cb'),__FILE__);
		add_settings_section('RssImporter_rm_cat','Remove Category',array($this,'RssImporter_rm_cat_cb'),__FILE__);
		add_settings_section('RssImporter_add_feed','Add New Feed',array($this,'RssImporter_add_feed_cb'),__FILE__);
		add_settings_section('RssImporter_rm_feed','Remove Feed',array($this,'RssImporter_rm_feed_cb'),__FILE__);
		add_settings_section('RssImporter_cl_cache','Clear Cache',array($this,'RssImporter_cl_cache_cb'),__FILE__);
		add_settings_section('RssImporter_ls_feed','List Feed',array($this,'RssImporter_ls_feed_cb'),__FILE__);
	}
	public function RssImporter_add_cat_cb()
	{
		RssImporter_add_cat_form();
	}
	public function RssImporter_rm_cat_cb()
	{
		RssImporter_rm_cat_form();
	}
	public function RssImporter_add_feed_cb()
	{
		RssImporter_add_feed_form();
	}
	public function RssImporter_rm_feed_cb()
	{
		RssImporter_rm_feed_form();
	}
	public function RssImporter_cl_cache_cb()
	{
		RssImporter_cl_cache_form();
	}
	public function RssImporter_ls_feed_cb()
	{
		RssImporter_ls_feed_form();
	}
}
function RssImporter_admin_menu()
{
	RssImporter::RssImporter_add_options_page();
}
add_action('admin_menu','RssImporter_admin_menu');
function RssImporter_admin_init()
{
	new RssImporter();
}
add_action('admin_init','RssImporter_admin_init');
?>