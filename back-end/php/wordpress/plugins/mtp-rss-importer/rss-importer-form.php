<?php
function RssImporter_add_cat_form()
{
	if (isset($_POST['submit_add_cat']))
	{
		RssImporter_add_cat_form_validate();
	} ?>
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="add_cat_name">Name</label></th>
					<td><input class="regular-text" id="add_cat_name" name="add_cat_name" placeholder="CMS" type="text" value="" /></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input class="button-primary" id="submit_add_cat" name="submit_add_cat" type="submit" value="Add New Category" /></p>
	</form>
<?php }
function RssImporter_rm_cat_form()
{
	if (isset($_POST['submit_rm_cat']))
	{
		RssImporter_rm_cat_form_validate();
	} ?>
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="rm_cat_name">Name</label></th>
					<td><input class="regular-text" id="rm_cat_name" name="rm_cat_name" placeholder="CMS" type="text" value="" /></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input class="button-primary" id="submit_rm_cat" name="submit_rm_cat" type="submit" value="Remove Category" /></p>
	</form>
<?php }
function RssImporter_add_feed_form()
{
	global $cat_ops;
	if (isset($_POST['submit_add_feed'])) {
		RssImporter_add_feed_form_validate();
	} ?>
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="add_feed_name">Name</label></th>
					<td><input class="regular-text" id="add_feed_name" name="add_feed_name" placeholder="WordPress" type="text" value="" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="add_feed_url">URL</label></th>
					<td><input class="regular-text" id="add_feed_url" name="add_feed_url" placeholder="http://wordpress.org/feed/" type="url" value="" /> <span class="description">Do not forget the <code>http://</code>.</span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="add_feed_cat">Category</label></th>
					<td><select id="add_feed_cat" name="add_feed_cat">
						<?php
						$cat_ops = get_option('rss_importer_category_options');
						if (empty($cat_ops))
						{
							echo '<option value="none">None</option>';
						}
						else
						{
							foreach ($cat_ops as $key => $value)
							{
								echo '<option value="'.$value.'">'.$value.'</option>';
							}
						}
						?>
					</select></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="add_feed_post">Post(s)</label></th>
					<td><input class="small-text" id="add_feed_post" max="10" min="1" name="add_feed_post" placeholder="1" type="number" value="" /></td>
				</tr>
				<!--<tr valign="top">
					<th scope="row"><label for="add_feed_src">Source</label></th>
					<td><select id="add_feed_src" name="add_feed_src">
						<?php /*
						$src = array(
							0 => 'Source',
							1 => 'Sponsor',
							2 => 'Website',
						);
						foreach ($src as $key => $value)
						{
							echo '<option value="'.$value.'">'.$value.'</option>';
						}
						*/ ?>
					</select></td>
				</tr>-->
				<tr valign="top">
					<th scope="row"><label for="add_feed_excerpt">Excerpt</label></th>
					<td><select id="add_feed_excerpt" name="add_feed_excerpt">
						<?php
						$src = array(
							0 => 'Yes',
							1 => 'No',
						);
						foreach ($src as $key => $value)
						{
							echo '<option value="'.$value.'">'.$value.'</option>';
						}
						?>
					</select></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="add_feed_length">Length</label></th>
					<td><select id="add_feed_length" name="add_feed_length">
						<?php
						$src = array(
							0 => '50',
							1 => '100',
							2 => '200',
							3 => '400',
						);
						foreach ($src as $key => $value)
						{
							echo '<option value="'.$value.'">'.$value.'</option>';
						}
						?>
					</select></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="add_feed_link">Link</label></th>
					<td><input class="regular-text" id="add_feed_link" name="add_feed_link" placeholder="http://my-website-url.com/feed-page/" type="url" value="" /> <span class="description">Do not forget the <code>http://</code>.</span></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input class="button-primary" id="submit_add_feed" name="submit_add_feed" type="submit" value="Add New Feed" /></p>
	</form>
<?php }
function RssImporter_rm_feed_form()
{
	if (isset($_POST['submit_rm_feed']))
	{
		RssImporter_rm_feed_validate();
	} ?>
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="rm_feed_url">URL</label></th>
					<td><input class="regular-text" id="rm_feed_url" name="rm_feed_url" placeholder="http://wordpress.org/feed/" type="url" value="" /> <span class="description">Do not forget the <code>http://</code>.</span></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input class="button-primary" id="submit_rm_feed" name="submit_rm_feed" type="submit" value="Remove Feed" /></p>
	</form>
<?php }
function RssImporter_cl_cache_form()
{
	if (isset($_POST['submit_cl_cache']))
	{
		RssImporter_cl_cache_validate();
	} ?>
	<form action="" method="post">
		<p class="submit"><input class="button-primary" id="submit_cl_cache" name="submit_cl_cache" type="submit" value="Clear Cache" /></p>
	</form>
<?php }
function RssImporter_ls_feed_form()
{
	$cat_ops = get_option('rss_importer_category_options');
	$feed_ops = get_option('rss_importer_feed_options');
	if (!empty($cat_ops))
	{
		echo '<ul id="mtp-tabs">';
		foreach ($cat_ops as $key => $value)
		{
			echo '<li><a href="#" data-filter="'.$value.'">'.$value.'</a></li>';
		}
		echo '</ul>';
		echo '<style>#mtp-tabs li {display:inline;} #mtp-tabs li:after {content:" / "} #mtp-tabs li:last-child:after {content:"";content:none;}</style>';
	}
	else
	{
		echo '<p>No categories have been added yet.</p>';
	}
	if (!empty($feed_ops))
	{
		echo '<ul id="mtp-list">';
		foreach ($feed_ops as $key => $value)
		{
			echo '<li data-filter="'.$value[0].'">
				<b>Feed Name:</b> '.$value[3].'<br>
				<b>Feed URL:</b> '.$value[6].'<br>
				<b>Feed Category:</b> '.$value[0].'<br>
				<b>Feed Post(s):</b> '.$value[4].'<br>
				
				<b>Feed Excerpt:</b> '.$value[1].'<br>
				<b>Feed Length:</b> '.$value[2].'<br>
				<b>Feed Link:</b> '.$value[7].'
			</li>'; //<b>Feed Source:</b> '.$value[5].'<br>
		}
		echo '</ul>';
		echo '<style>#mtp-list li {display:inline;float:left;margin-bottom:20px;padding-right:20px;}</style>';
		echo '<script>(function($){var a = $("#mtp-tabs a"),ol = $("#mtp-list");a.on("click",function(e){e.preventDefault();var filter = $(this).attr("data-filter");ol.find("li:not(:contains("+filter+"))").fadeOut();ol.find("li:contains("+filter+")").fadeIn();})})(jQuery);</script>';
	}
	else
	{
		echo '<p>No feeds have been added yet.</p>';
	}
}
?>