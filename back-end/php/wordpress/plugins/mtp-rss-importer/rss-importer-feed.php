<?php
/*remove_all_actions('do_feed_rss2');
remove_all_actions('do_feed_rss2');
>>>>>>> 2b77fd879c00b72956f07b46a7364e72deb4482b
add_action('do_feed_rss2','RssImporter_custom_feed',10,1);
function RssImporter_custom_thumbnail()
{
	global $post;
	if (has_post_thumbnail())
	{
		$content = the_post_thumbnail($post->ID,'thumbnail');
	}
	else if (!has_post_thumbnail())
	{
		echo '';
		$content = '';
		ob_start();
		ob_end_clean();
		$get = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',$post->post_content,$m);
		$content = '<img alt="Post Thumbnail" height="204" src="'.$m[1][0].'" width="270" />';
	}
	else
	{
		$content = '';
	}
	return $content;
}
function RssImporter_custom_feed()
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
		<description><?php bloginfo_rss('description'); ?></description>
		<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'),false); ?></lastBuildDate>
		<language><?php echo get_option('rss_language'); ?></language>
		<sy:updatePeriod><?php echo apply_filters('rss_update_period','hourly'); ?></sy:updatePeriod>
		<sy:updateFrequency><?php echo apply_filters('rss_update_frequency','1'); ?></sy:updateFrequency>
		<?php
		do_action('rss2_head');
		while(have_posts()) : the_post();
		?>
		<item>
			<thumbnail><?php RssImporter_custom_thumbnail(); ?></thumbnail>
			<title><?php the_title_rss(); ?></title>
			<link><?php the_permalink_rss(); ?></link>
			<comments><?php comments_link_feed(); ?></comments>
			<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000',get_post_time('Y-m-d H:i:s',true),false); ?></pubDate>
			<?php the_category_rss('rss2'); ?>
			<guid isPermaLink="false"><?php the_guid(); ?></guid>
			<?php if (get_option('rss_use_excerpt')) : ?>
			<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
			<?php else : ?>
			<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
			<?php if (strlen($post->post_content) > 0) : ?>
			<content:encoded><![CDATA[<?php the_content_feed('rss2'); ?>]]></content:encoded>
			<?php else : ?>
			<content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
			<?php endif; endif; ?>
			<wfw:commentRss><?php echo esc_url(get_post_comments_feed_link(null,'rss2')); ?></wfw:commentRss>
			<slash:comments><?php echo get_comments_number(); ?></slash:comments>
			<?php rss_enclosure(); do_action('rss2_item'); ?>
		</item>
		<?php endwhile; ?>
	</channel>
	</rss>
<?php } */
?>
