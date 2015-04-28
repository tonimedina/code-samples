<?php
function RssImporter_add_cat_form_validate()
{
	global $add_cat_name,$cat_ops;
	switch ($add_cat_name)
	{
		case '':
			echo '<div class="error"><p>Please fill out all fields.</p></div>';
			break;
		case (strlen($add_cat_name) != strlen(strip_tags($add_cat_name))):
			echo '<div class="error"><p>Please do not put any HTML code inside the fields.</p></div>';
			break;
		case (in_array($add_cat_name,$cat_ops)):
			echo '<div class="error"><p>The category name you are trying to add is already in use. Please enter a new one.</p></div>';
			break;
		default:
			RssImporter_add_cat_update_option($add_cat_name);
			break;
	}
}
function RssImporter_rm_cat_form_validate()
{
	global $cat_ops,$rm_cat_name;
	switch ($rm_cat_name)
	{
		case '':
			echo '<div class="error"><p>Please fill out all fields.</p></div>';
			break;
		case (strlen($rm_cat_name) != strlen(strip_tags($rm_cat_name))):
			echo '<div class="error"><p>Please do not put any HTML code inside the fields.</p></div>';
			break;
		case (!in_array($rm_cat_name,$cat_ops)):
			echo '<div class="error"><p>The category name you are trying to remove does not exist.</p></div>';
			break;
		default:
			RssImporter_rm_cat_update_option($rm_cat_name);
			break;
	}
}
function RssImporter_add_feed_form_validate()
{
	global $add_feed_cat,$add_feed_excerpt,$add_feed_length,$add_feed_name,$add_feed_post,$add_feed_src,$add_feed_url,$add_feed_link,$cat_ops,$feed_ops;
	if ($add_feed_cat === '' || $add_feed_excerpt === '' || $add_feed_length === '' || $add_feed_name === '' || $add_feed_post === '' || $add_feed_url === '')
	{
		echo '<div class="error"><p>Please fill out all fields.</p></div>'; //$add_feed_src === '' ||
	}
	else if (strlen($add_feed_name) != strlen(strip_tags($add_feed_name)) || strlen($add_feed_post) != strlen(strip_tags($add_feed_post)) || strlen($add_feed_url) != strlen(strip_tags($add_feed_url)))
	{
		echo '<div class="error"><p>Please do not put any HTML code inside the fields.</p></div>';
	}
	else if (empty($cat_ops))
	{
		echo '<div class="error"><p>Please add first a category in order to classify the feeds.</p></div>';
	}
	else
	{
		RssImporter_add_feed_update_option($add_feed_cat,$add_feed_excerpt,$add_feed_length,$add_feed_name,$add_feed_post,$add_feed_src,$add_feed_url,$add_feed_link);
	}
}
function RssImporter_cl_cache_validate()
{
	return RssImporter_cl_cache_update_option();
}
function RssImporter_rm_feed_validate()
{
	global $rm_feed_url;
	switch ($rm_feed_url) {
		case '':
			echo '<div class="error"><p>Please fill out all fields.</p></div>';
			break;
		default:
			RssImporter_rm_feed_update_option($rm_feed_url);
			break;
	}
}
?>