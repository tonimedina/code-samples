<?php
/*
Plugin Name: Test MTP RSS Importer
Plugin URI: http://mt-performance.net
Description: Creates a shortcode used to import feeds from other websites.
Version: 1.1
Author: Toni Medina
Author URI: http://tonimedina.me
License: GPL2
*/

include(ABSPATH.WPINC.'/class-simplepie.php');
define('SIMPLEPIE_NAMESPACE_MTPRSSIMPORTER','');

/*
 * MTP RSS Importer SimplePie Item
 */
class mtp_rss_importer_simplepie_item extends SimplePie_Item
{
	public function mtp_rss_importer_get_thumbnail_alt()
	{
		$data = $this->get_item_tags(SIMPLEPIE_NAMESPACE_MTPRSSIMPORTER,'thumbnail');
		return $data[0]['child']['']['img'][0]['attribs']['alt'];
	}

	public function mtp_rss_importer_get_thumbnail_src()
	{
		$data = $this->get_item_tags(SIMPLEPIE_NAMESPACE_MTPRSSIMPORTER,'thumbnail');
		return $data[0]['child']['']['img'][0]['attribs']['']['src'];
	}
}

/*
 * MTP RSS Importer
 */
class mtp_rss_importer
{
	public function __construct()
	{

	}

	/*
	 * MTP RSS Importer Add Options Page
	 */
	public function mtp_rss_importer_add_options_page()
	{
		add_options_page('MTP RSS Importer Options Page','MTP RSS Importer','edit_theme_options',__FILE__,array('mtp_rss_importer','mtp_rss_importer_add_options_page_cb'));
	}

	/*
	 * MTP RSS Importer Add Options Page Callback
	 */
	public function mtp_rss_importer_add_options_page_cb()
	{ ?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>MTP RSS Importer Options Page</h2>
			<h3>Clear Cache</h3>
			<?php
			if (isset($_POST['mtp_rss_importer_submit_clear_cache']))
			{
				$files = glob(dirname(__FILE__).'/cache/*.spc');

				foreach ($files as $key)
				{
					unlink($key);
				}

				echo '<div class="updated"><p>The plugin cache has been successfully cleared.</p></div>';
			}
			?>
			<p>If you can not see the latest post when using the feed, maybe a cache refresh could help.</p>
			<form action="" method="post">
				<p class="submit"><input class="button-primary" id="mtp_rss_importer_submit_clear_cache" name="mtp_rss_importer_submit_clear_cache" type="submit" value="Clear Cache" /></p>
			</form>
		</div>
	<?php }

	/*
	 * MTP RSS Importer Add Shortcode
	 */
	public function mtp_rss_importer_add_shortcode($atts)
	{
		extract(shortcode_atts(array(
			'length' => '100',
			'more' => 'Read more...',
			'number' => '2',
			'page' => '/mulher-net/',
			'title' => 'mulher.net',
			'url' => 'http://mulher.net/feed/'
		),$atts));

		return mtp_rss_importer::mtp_rss_importer_add_shortcode_cb($length,$more,$number,$page,$title,$url);
	}

	/*
	 * MTP RSS Importer
	 */
	public function mtp_rss_importer_add_shortcode_cb($length,$more,$number,$page,$title,$url)
	{
		$handle = @fopen($url,'r');

		if ($handle !== false)
		{
			$feed = new SimplePie();
			$feed->set_feed_url($url);
			$feed->set_item_class('mtp_rss_importer_simplepie_item');
			$feed->enable_order_by_date(true);
			$feed->enable_cache(true);
			$feed->set_cache_duration(3600);
			$feed->set_cache_location(dirname(__FILE__).'/cache');
			$success = $feed->init();
			$feed->handle_content_type();

			if ($success)
			{
				foreach ($feed->get_items(0,$number) as $item)
				{
					$output .= '
					<div class="rss-entry">
						<div class="rss-entry-header">
							<a href="'.esc_url($item->get_permalink()).'" rel="bookmark" target="_blank"><img alt="'.$item->mtp_rss_importer_get_thumbnail_alt().'" height="204" src="'.$item->mtp_rss_importer_get_thumbnail_src().'" width="270" /></a>
							<h2 class="rss-entry-title"><a href="'.esc_url($item->get_permalink()).'" rel="bookmark" target="_blank">'.esc_html($item->get_title()).'</a></h2>
						</div><!-- END .rss-entry-header -->
						<div class="rss-entry-content">
							<p>'.strip_tags(substr($item->get_description(),0,$length)).' <a href="'.esc_url($item->get_permalink()).'" rel="bookmark" target="_blank">'.$more.'</a></p>
						</div><!-- END .rss-entry-content -->
						<div class="rss-entry-footer">
							<p class="rss-more"><a href="'.$page.'" rel="bookmark">'.$title.'</a></p>
						</div>
					</div><!-- END .rss-entry -->
					';
				}

				return $output;
			}
			else
			{
				$feed->error();
			}
		}
		else
		{
			echo '<p>'.$url.' is not a valid feed URL. Please type a right one in order to get the feeds.</p>';
		}
	}
}

/*
 * MTP RSS Importer Add Shortcode Button
 */
function mtp_rss_importer_add_shortcode_button()
{
	if (current_user_can('edit_posts') && current_user_can('edit_pages'))
	{
		add_filter('mce_external_plugins','mtp_rss_importer_add_shortcode_plugin');
		add_filter('mce_buttons','mtp_rss_importer_register_shortcode_button');
	}
}

/*
 * MTP RSS Importer Add Shortcode Plugin
 */
function mtp_rss_importer_add_shortcode_plugin($plugin_array)
{
	$plugin_array['mtprssimporter'] = plugins_url() .'/test-mtp-rss-importer/add-button.js';
	return $plugin_array;
}

/*
 * MTP RSS Importer Register Shortcode Button
 */
function mtp_rss_importer_register_shortcode_button($buttons)
{
	array_push($buttons,'mtprssimporter');
	return $buttons;
}

add_action('init','mtp_rss_importer_add_shortcode_button');
add_shortcode('rss',array('mtp_rss_importer','mtp_rss_importer_add_shortcode'));

function mtp_rss_importer_admin_menu()
{
	mtp_rss_importer::mtp_rss_importer_add_options_page();
}

add_action('admin_menu','mtp_rss_importer_admin_menu');

/*
 * Add This Part Of The Code To The Custom Function
 */
/*function mtp_rss_importer_do_feed_rss()
{
	header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
	$more = 1;

	echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

	<rss version="2.0"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:wfw="http://wellformedweb.org/CommentAPI/"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:atom="http://www.w3.org/2005/Atom"
		xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
		<?php do_action('rss2_ns'); ?>
	>

	<channel>
		<title><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
		<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
		<link><?php bloginfo_rss('url') ?></link>
		<description><?php bloginfo_rss("description") ?></description>
		<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
		<language><?php echo get_option('rss_language'); ?></language>
		<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
		<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
		<?php do_action('rss2_head'); while( have_posts()) : the_post(); ?>
		<item>
			<?php if (has_post_thumbnail()) : ?>
			<thumbnail><?php the_post_thumbnail(); ?></thumbnail>
			<?php endif; ?>
			<title><?php the_title_rss() ?></title>
			<link><?php the_permalink_rss() ?></link>
			<comments><?php comments_link_feed(); ?></comments>
			<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
			<dc:creator><?php the_author() ?></dc:creator>
			<?php the_category_rss('rss2') ?>

			<guid isPermaLink="false"><?php the_guid(); ?></guid>
			<?php if (get_option('rss_use_excerpt')) : ?>
			<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
			<?php else : ?>
			<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
			<?php if ( strlen( $post->post_content ) > 0 ) : ?>
			<content:encoded><![CDATA[<?php the_content_feed('rss2') ?>]]></content:encoded>
			<?php else : ?>
			<content:encoded><![CDATA[<?php the_excerpt_rss() ?>]]></content:encoded>
			<?php endif; endif; ?>
			<wfw:commentRss><?php echo esc_url( get_post_comments_feed_link(null, 'rss2') ); ?></wfw:commentRss>
			<slash:comments><?php echo get_comments_number(); ?></slash:comments>
			<?php rss_enclosure(); do_action('rss2_item'); ?>
		</item>
		<?php endwhile; ?>
	</channel>
	</rss>
<?php }

remove_all_actions('do_feed_rss2');
add_action('do_feed_rss2','mtp_rss_importer_do_feed_rss',10,1);
*/ ?>