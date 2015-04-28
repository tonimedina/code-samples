<?php
/*
Plugin Name: MTP Thumbnail For Excerpts* 
Plugin URI: http://mt-performance.net
Description: *It works with Thesis, but not only.
Version: 1.0
Author: Toni Medina
Author URI: http://tonimedina.tumblr.com
License: GPL2
*/
class tfe {
	public $options;
	
	function __construct() {
		$this->options = get_option('tfe_option_name');
		$this->tfe_regadd();
	}
	/*
	 * Adding The Options Page
	 */
	function tfe_add_options_page() {
		add_options_page('Thumbnail For Excerpts Options Page','Thumbnail For Excerpts','administrator',__FILE__,array('tfe','tfe_add_options_page_cb'));
	}
	/*
	 * Options Page Callback
	 */
	function tfe_add_options_page_cb() { ?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Thumbnail For Excerpts Options Page</h2>
			<form action="options.php" method="post">
				<?php settings_fields('tfe_option_group'); ?>
				<?php do_settings_sections(__FILE__); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php }
	/*
	 * Registering and Addign Groups, Sections And Fields To The Options Page
	 */
	public function tfe_regadd() {
		/*
		 * Registering The Options Group
		 */
		register_setting('tfe_option_group','tfe_option_name');
		/*
		 * Adding Settings Section
		 */
		add_settings_section('tfe_main','Main Settings',array($this,'tfe_main_cb'),__FILE__);
		/*
		 * Adding The Fields
		 */
		add_settings_field('tfe_width','Width',array($this,'tfe_width_cb'),__FILE__,'tfe_main',array('label_for'=>'tfe_width'));
		add_settings_field('tfe_height','Height',array($this,'tfe_height_cb'),__FILE__,'tfe_main',array('label_for'=>'tfe_height'));
		add_settings_field('tfe_align','Align',array($this,'tfe_align_cb'),__FILE__,'tfe_main',array('
			label_for'=>'tfe_align'));
		add_settings_field('tfe_dimg','Default Image',array($this,'tfe_dimg_cb'),__FILE__,'tfe_main',array('label_for'=>'tfe_dimg'));
		add_settings_field('tfe_dimgurl','Default Image URL',array($this,'tfe_dimgurl_cb'),__FILE__,'tfe_main',array('label_for'=>'tfe_dimgurl'));
		add_settings_field('tfe_anchor','With Link',array($this,'tfe_anchor_cb'),__FILE__,'tfe_main',array('label_for'=>'tfe_anchor'));
		add_settings_field('tfe_home','Apply On Home',array($this,'tfe_home_cb'),__FILE__,'tfe_main',array(
			'label_for'=>'tfe_home'));
		add_settings_field('tfe_archive','Apply On Archive',array($this,'tfe_archive_cb'),__FILE__,'tfe_main',array('label_for'=>'tfe_archive'));
		add_settings_field('tfe_search','Apply On Search',array($this,'tfe_search_cb'),__FILE__,'tfe_main',array('label_for'=>'tfe_search'));
		add_settings_field('tfe_bth','Before Thesis Headline',array($this,'tfe_bth_cb'),__FILE__,'tfe_main',array('label_for'=>'tfe_bth'));
	}
	/*
	 * Options Page Fields Callbacks
	 */
	public function tfe_main_cb() {
	}
	public function tfe_width_cb() {
		echo "<input class='small-text' id='tfe_width' name='tfe_option_name[tfe_width]' type='text' value='{$this->options[tfe_width]}' />";
	}
	public function tfe_height_cb() {
		echo "<input class='small-text' id='tfe_height' name='tfe_option_name[tfe_height]' type='text' value='{$this->options[tfe_height]}' />";
	}
	public function tfe_align_cb() {
		echo "<select id='tfe_align' name='tfe_option_name[tfe_align]'>";
		$aligns = array('left','center','none','right');
		foreach ($aligns as $align) {
			$selected = ($this->options[tfe_align] === $align) ? "selected='selected'" : "";
			echo "<option value='$align' $selected>$align</option>";
		}
		echo "</select>";
	}
	public function tfe_dimg_cb() {
		echo "<select id='tfe_dimg' name='tfe_option_name[tfe_dimg]'>";
		$dimgs = array('no','yes');
		foreach ($dimgs as $dimg) {
			$selected = ($this->options[tfe_dimg] === $dimg) ? "selected='selected'" : "";
			echo "<option value='$dimg' $selected>$dimg</option>";
		}
		echo "</select>";
	}
	public function tfe_dimgurl_cb() {
		echo "<input class='regular-text code' id='tfe_dimgurl' name='tfe_option_name[tfe_dimgurl]' type='text' value='{$this->options[tfe_dimgurl]}' />";
	}
	public function tfe_anchor_cb() {
		echo "<select id='tfe_anchor' name='tfe_option_name[tfe_anchor]'>";
		$anchors = array('yes','no');
		foreach ($anchors as $anchor) {
			$selected = ($this->options[tfe_anchor] === $anchor) ? "selected='selected'" : "";
			echo "<option value='$anchor' $selected>$anchor</option>";
		}
		echo "</select>";
	}
	public function tfe_home_cb() {
		echo "<select id='tfe_home' name='tfe_option_name[tfe_home]'>";
		$homes = array('yes','no');
		foreach ($homes as $home) {
			$selected = ($this->options[tfe_home] === $home) ? "selected='selected'" : "";
			echo "<option value='$home' $selected>$home</option>";
		}
		echo "</select>";
	}
	public function tfe_archive_cb() {
		echo "<select id='tfe_archive' name='tfe_option_name[tfe_archive]'>";
		$archives = array('yes','no');
		foreach ($archives as $archive) {
			$selected = ($this->options[tfe_archive] === $archive) ? "selected='selected'" : "";
			echo "<option value='$archive' $selected>$archive</option>";
		}
		echo "</select>";
	}
	public function tfe_search_cb() {
		echo "<select id='tfe_search' name='tfe_option_name[tfe_search]'>";
		$searchs = array('yes','no');
		foreach ($searchs as $search) {
			$selected = ($this->options[tfe_search] === $search) ? "selected='selected'" : "";
			echo "<option value='$search' $selected>$search</option>";
		}
		echo "</select>";
	}
	public function tfe_bth_cb() {
		echo "<select id='tfe_bth' name='tfe_option_name[tfe_bth]'>";
		$bths = array('no','yes');
		foreach ($bths as $bth) {
			$selected = ($this->options[tfe_bth] === $bth) ? "selected='selected'" : "";
			echo "<option value='$bth' $selected>$bth</option>";
		}
		echo "</select>";
	}
}
/*
 * Calling The Options Page
 */
function tfe_admin_menu() {
	tfe::tfe_add_options_page();
}
add_action('admin_menu','tfe_admin_menu');
/*
 * Calling The Class
 */
function tfe_admin_init() {
	new tfe();
}
add_action('admin_init','tfe_admin_init');
/*
 * Post Thumbnails - Add Theme Support
 */
function tfe_after_setup_theme() {
	if (function_exists('add_theme_support')) {
		add_theme_support('post-thumbnails');
	}
}
add_action('after_setup_theme','tfe_after_setup_theme');
/*
 * Getting The Options
 */
$o       = get_option('tfe_option_name');
$width   = $o['tfe_width']   == '' ? '150'  : $o['tfe_width'];
$height  = $o['tfe_height']  == '' ? '150'  : $o['tfe_height'];
$align   = $o['tfe_align']   == '' ? 'left' : $o['tfe_align'];
$dimg    = $o['tfe_dimg']    == '' ? 'no'   : $o['tfe_dimg'];
$dimgurl = $o['tfe_dimgurl'];
$anchor  = $o['tfe_anchor']  == '' ? 'yes'  : $o['tfe_anchor'];
$home    = $o['tfe_home']    == '' ? 'yes'  : $o['tfe_home'];
$archive = $o['tfe_archive'] == '' ? 'yes'  : $o['tfe_archive'];
$search  = $o['tfe_search']  == '' ? 'yes'  : $o['tfe_search'];
$thesis  = $o['tfe_bth']     == '' ? 'no'   : $o['tfe_bth'];
/*
 * Thesis Hook?
 */
if ($thesis == 'yes') {
	add_action('thesis_hook_before_teaser_headline','tfe_thbth');
} else {
	add_filter('the_excerpt','tfe_excerpt');	
}
/*
 * Getting The Post Thumbnails
 */
function tfe_the_thumbnails() {
	global $id, $o, $width, $height, $align, $dimg, $dimgurl, $anchor;
	if (function_exists('has_post_thumbnail')) {
		if (has_post_thumbnail()) {
			switch ($anchor) {
				case 'no':
					$output = get_the_post_thumbnail($id,array($width,$height),array('class'=>'align'.$align.' tfe'));
					break;
					
				default:
					$output = '<a href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_post_thumbnail($id,array($width,$height),array('class'=>'align'.$align.' tfe')).'</a>';
					break;
			}
		} elseif ($dimg == 'yes') {
			switch ($anchor) {
				case 'no':
					$output = '<img alt="Post Thumbnail" class="align'.$align.'" height="'.$height.'" src="'.$dimgurl.'" title="Post Thumbnail" width="'.$width.'" />';
					break;
				
				default:
					$output = '<a href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark"><img alt="Post Thumbnail" class="align'.$align.'" height="'.$height.'" src="'.$dimgurl.'" title="Post Thumbnail" width="'.$width.'" /></a>';
					break;
			}
		} else {
			$stimg = tfe_first_img();
			switch ($anchor) {
				case 'no':
					$output = '<img alt="Post Thumbnail" class="align'.$align.'" height="'.$height.'" src="'.$stimg.'" title="Post Thumbnail" width="'.$width.'" />';
					break;
				
				default:
					$output = '<a href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark"><img alt="Post Thumbnail" class="align'.$align.'" height="'.$height.'" src="'.$stimg.'" title="Post Thumbnail" width="'.$width.'" /></a>';
					break;
			}
		}
	}
	echo $output;
}
/*
 * Getting The First Image Of The Post, If There Is No Thumbnail
 */
function tfe_first_img() {
	global $post,$id;
	
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches[1][0];
	return $first_img;
}
/*
 * Thesis Hook Before Teaser Headline
 */
function tfe_thbth() {
	tfe_the_thumbnails();
}
/*
 * The Default Excerpt
 */
function tfe_excerpt($excerpt) {
	tfe_the_thumbnails();
	return $excerpt;
}
?>