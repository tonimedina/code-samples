<?php
include(ABSPATH.WPINC.'/class-simplepie.php');
define('SIMPLEPIE_NAMESPACE_RSSIMPORTER','');
class RssImporter_simple_pie extends SimplePie_Item
{
	public function get_thumbnail_class()
	{
		$data = $this->get_item_tags(SIMPLEPIE_NAMESPACE_RSSIMPORTER,'thumbnail');
		return $data[0]['child']['']['img'][0]['attribs']['']['class'];
	}
	public function get_thumbnail_height()
	{
		$data = $this->get_item_tags(SIMPLEPIE_NAMESPACE_RSSIMPORTER,'thumbnail');
		return $data[0]['child']['']['img'][0]['attribs']['']['height'];
	}
	public function get_thumbnail_src()
	{
		$data = $this->get_item_tags(SIMPLEPIE_NAMESPACE_RSSIMPORTER,'thumbnail');
		return $data[0]['child']['']['img'][0]['attribs']['']['src'];
	}
	public function get_thumbnail_width()
	{
		$data = $this->get_item_tags(SIMPLEPIE_NAMESPACE_RSSIMPORTER,'thumbnail');
		return $data[0]['child']['']['img'][0]['attribs']['']['width'];
	}
}
function RssImporter_add_shortcode($atts)
{
	extract(shortcode_atts(array(
		'category' => '',
		//'order_by_date' => 'true',
	),$atts));
	return RssImporter_display_shortcode($atts);
}
add_shortcode('rss','RssImporter_add_shortcode');
function RssImporter_display_shortcode($atts)
{
	global $feed_ops;
	extract($atts);
	foreach ($feed_ops as $key => $value)
	{
		$arr_search = array_search($category,$value);
		if ($arr_search !== false)
		{
			$feed = new SimplePie();
			$feed->set_feed_url($value[6]);
			$feed->set_item_class('RssImporter_simple_pie');
			$feed->enable_order_by_date(true);
			$feed->enable_cache(true);
			$feed->set_cache_duration(3600);
			$feed->set_cache_location(dirname(__FILE__).'/cache');
			$feed->init();
			$feed->handle_content_type();
			if ($value[1] == 'Yes')
			{
				foreach ($feed->get_items(0,$value[4]) as $key)
				{ ?>
					<div class="rss-entry">
						<div class="rss-entry-header">
							<?php
							$src = $key->get_thumbnail_src();
							if (!empty($src))
							{
								echo '<a href="'.esc_url($key->get_permalink()).'" rel="bookmark" target="_blank"><img alt="'.$key->get_thumbnail_class().'" height="'.$key->get_thumbnail_height().'" src="'.$key->get_thumbnail_src().'" width="'.$key->get_thumbnail_width().'" /></a>';
							}
							?>
							<h2 class="rss-entry-title"><a href="<?php echo esc_url($key->get_permalink()); ?>" rel="bookmark" target="_blank"><?php echo esc_html($key->get_title()); ?></a></h2>
						</div>
						<div class="rss-entry-content">
							<p><?php echo strip_tags($key->get_description()); ?> <a href="<?php echo $key->get_permalink(); ?>">[+]</a></p>
							<!--<p><?php /*echo strip_tags(substr($key->get_description(),0,$value[2]));*/ ?></p>-->
							<!--<?php /*echo '<p>'.strip_tags(substr($key->get_content(),0,300)).' [...]</p>';*/ ?>-->
						</div>
						<div class="rss-entry-footer">
							<p><a href="<?php echo $value[7]; ?>" rel="bookmark" target="_self"><?php echo $value[3] ?></a></p>
						</div>
					</div>
				<?php }
			}
			else
			{
				foreach ($feed->get_items(0,$value[4]) as $key)
				{ ?>
					<div class="rss-entry">
						<div class="rss-entry-header">
							<?php
							$src = $key->get_thumbnail_src();
							if (!empty($src))
							{
								echo '<a href="'.esc_url($key->get_permalink()).'" rel="bookmark" target="_blank"><img alt="'.$key->get_thumbnail_class().'" height="'.$key->get_thumbnail_height().'" src="'.$key->get_thumbnail_src().'" width="'.$key->get_thumbnail_width().'" /></a>';
							}
							?>
							<h2 class="rss-entry-title"><a href="<?php echo esc_url($key->get_permalink()); ?>" rel="bookmark" target="_blank"><?php echo esc_html($key->get_title()); ?></a></h2>
						</div>
						<div class="rss-entry-footer">
							<p><a href="<?php echo $value[7]; ?>" rel="bookmark" target="_self"><?php echo $value[3]; ?></a>'; ?></p>
						</div>
					</div>
				<?php }
			}
		}
	}
}
?>