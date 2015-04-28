<?php
function RssImporter_add_cat_update_option($add_cat_name){
	global $autoload,$deprecated;
	$option_name = 'rss_importer_category_options';
	$cat_ops = get_option($option_name);
	$cat_ops[] = $add_cat_name;
	$new_value = $cat_ops;
	if (get_option($option_name) != $new_value)
	{
		update_option($option_name,$new_value);
	}
	else
	{
		add_option($option_name,$new_value,$deprecated,$autoload);
	}
}
function RssImporter_rm_cat_update_option($rm_cat_name)
{
	global $autoload,$deprecated;
	$option_name = 'rss_importer_category_options';
	$cat_ops = get_option($option_name);
	$arr_search = array_search($rm_cat_name,$cat_ops);
	unset($cat_ops[$arr_search]);
	$new_value = $cat_ops;
	if (get_option($option_name) != $new_value)
	{
		update_option($option_name,$new_value);
	}
	else
	{
		add_option($option_name,$new_value,$deprecated,$autoload);
	}
}
function RssImporter_add_feed_update_option($add_feed_cat,$add_feed_excerpt,$add_feed_length,$add_feed_name,$add_feed_post,$add_feed_src,$add_feed_url,$add_feed_link)
{
	global $autoload,$deprecated;
	$option_name = 'rss_importer_feed_options';
	$array_ops = array($add_feed_cat,$add_feed_excerpt,$add_feed_length,$add_feed_name,$add_feed_post,$add_feed_src,$add_feed_url,$add_feed_link);
	$feed_ops = get_option($option_name);
	$feed_ops[] = $array_ops;
	$new_value = $feed_ops;
	if (get_option($option_name) != $new_value)
	{
		update_option($option_name,$new_value);
	}
	else
	{
		add_option($option_name,$new_value,$deprecated,$autoload);
	}
}
function RssImporter_cl_cache_update_option()
{
	$files = glob(dirname(__FILE__).'/cache/*.spc');
	foreach ($files as $key)
	{
		unlink($key);
	}
	echo '<div class="updated"><p>Cache has been successfully cleared</p></div>';
}
function RssImporter_rm_feed_update_option($rm_feed_url)
{
	global $autoload,$deprecated;
	$option_name = 'rss_importer_feed_options';
	$feed_ops = get_option($option_name);
	
	foreach ($feed_ops as $key => $value)
	{
		$arr = $value;
		$arr_search = array_search($rm_feed_url,$arr);
		if ($arr_search)
		{
			unset($feed_ops[$key]);
			$new_value = $feed_ops;
			if (get_option($option_name) != $new_value)
			{
				update_option($option_name,$new_value);
			}
			else
			{
				add_option($option_name,$new_value,$deprecated,$autoload);
			}
			echo '<div class="updated"><p>Feed successfully deleted.</p></div>';
		}
	}
}
?>