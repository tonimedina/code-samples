<?php
/*
Plugin Name: MTP Ads*
Plugin URI: http://mt-performance.net
Description: Creates a new sidebar and a new widget to place your ads on different positions inside your website. *Works only with Thesis!
Version: 1.0
Author: Toni Medina
Author URI: http://tonimedina.me
License: GPL2
*/

class mtp_ad extends WP_Widget
{
	public $instance;

	// Constructor Method
	public function __construct()
	{
		$widget_ops = array(
			'classname'   => 'widget_mtp_ad',
			'description' => __('Use this widget together with the Ads Sidebar to place your ads on different positions inside your website.','mtp_ad'),
		);
		parent::__construct('mpt_ad', __('MTP Ad','mtp_ad'),$widget_ops);

		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$pbhtml_true = $value['mtp_ad_position'] == 'before_html';
			$pbt_true    = $value['mtp_ad_position'] == 'before_title';
			$pat_true    = $value['mtp_ad_position'] == 'after_title';
			$pbi_true    = $value['mtp_ad_position'] == 'byline_item';
			$pbc_true    = $value['mtp_ad_position'] == 'before_content';
			$pbs_true    = $value['mtp_ad_position'] == 'before_sidebars';
			$pap_true    = $value['mtp_ad_position'] == 'after_post';
			$pbf_true    = $value['mtp_ad_position'] == 'before_footer';
			$pbp_true    = $value['mtp_ad_position'] == 'between_paragraphs';
			$papb_true   = $value['mtp_ad_position'] == 'after_post_box';
			$patb_true   = $value['mtp_ad_position'] == 'after_teasers_box';
			
			$pbhtml_arr_search = array_search($pbhtml_true,$instance);
			$pbt_arr_search    = array_search($pbt_true,$instance);
			$pat_arr_search    = array_search($pat_true,$instance);
			$pbi_arr_search    = array_search($pbi_true,$instance);
			$pbs_arr_search    = array_search($pbs_true,$instance);
			$pbc_arr_search    = array_search($pbc_true,$instance);
			$pap_arr_search    = array_search($pap_true,$instance);
			$pbf_arr_search    = array_search($pbf_true,$instance);
			$pbp_arr_search    = array_search($pbp_true,$instance);
			$papb_arr_search   = array_search($papb_true,$instance);
			$patb_arr_search   = array_search($patb_true,$instance);

			if ($pbhtml_arr_search == true)
			{
				add_action('thesis_hook_before_html',array($this,'mtp_ad_thbhtml'));
			}
			else if ($pbt_arr_search == true)
			{
				add_action('thesis_hook_before_title',array($this,'mtp_ad_thbt'));
			}
			else if ($pat_arr_search == true)
			{
				add_action('thesis_hook_after_title',array($this,'mtp_ad_that'));
			}
			else if ($pbi_arr_search == true)
			{
				add_action('thesis_hook_byline_item',array($this,'mtp_ad_thbi'));
			}
			else if ($pbc_arr_search == true)
			{
				add_action('thesis_hook_before_content',array($this,'mtp_ad_thbc'));
			}
			else if ($pbs_arr_search == true)
			{
				add_action('thesis_hook_before_sidebars',array($this,'mtp_ad_thbs'));
			}
			else if ($pap_arr_search == true)
			{
				add_action('thesis_hook_after_post',array($this,'mtp_ad_thap'),1);
			}
			else if ($pbf_arr_search == true)
			{
				add_action('thesis_hook_before_footer',array($this,'mtp_ad_thbf'));
			}
			else if ($pbp_arr_search == true)
			{
				add_filter('the_content',array($this,'mtp_ad_cthbp'));
			}
			else if ($papb_arr_search == true)
			{
				add_action('thesis_hook_after_post_box',array($this,'mtp_ad_thapb'));
			}
			else if ($patb_arr_search == true)
			{
				add_action('thesis_hook_after_teasers_box',array($this,'mtp_ad_thatb'));
			}
		}
	}

	// Widget Form
	public function form($instance)
	{
		$instance = wp_parse_args((array)$instance,array(
			'mtp_ad' => '',
			'mtp_ad_category' => '',
			'mtp_ad_home' => '',
			'mtp_ad_page' => '',
			'mtp_ad_post' => '',
			'mtp_ad_tag' => '',
			'mtp_ad_position' => '',
			'mtp_ad_number' => '',
		));
		$mtp_ad = esc_textarea($instance['mtp_ad']);
		$mtp_ad_position = isset( $instance['mtp_ad_position'] ) ? $instance['mtp_ad_position'] : '';
		$mtp_ad_number = isset($instance['mtp_ad_number']) ? absint($instance['mtp_ad_number']) : 1; ?>
		<p>
			<label for="<?php echo $this->get_field_id('mtp_ad'); ?>"><?php _e('Place your ad here:'); ?></label>
		</p>
		<textarea class="widefat" rows="3" cols="20" id="<?php echo $this->get_field_id('mtp_ad'); ?>" name="<?php echo $this->get_field_name('mtp_ad'); ?>"><?php echo $mtp_ad; ?></textarea>
		<p>Display this ad on:</p>
		<p>
			<input id="<?php echo $this->get_field_id('mtp_ad_category'); ?>" name="<?php echo $this->get_field_name('mtp_ad_category'); ?>" type="checkbox" <?php checked(isset($instance['mtp_ad_category']) ? $instance['mtp_ad_category'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('mtp_ad_category'); ?>"><?php _e('Category'); ?></label><br>
			<input id="<?php echo $this->get_field_id('mtp_ad_home'); ?>" name="<?php echo $this->get_field_name('mtp_ad_home'); ?>" type="checkbox" <?php checked(isset($instance['mtp_ad_home']) ? $instance['mtp_ad_home'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('mtp_ad_home'); ?>"><?php _e('Home'); ?></label><br>
			<input id="<?php echo $this->get_field_id('mtp_ad_page'); ?>" name="<?php echo $this->get_field_name('mtp_ad_page'); ?>" type="checkbox" <?php checked(isset($instance['mtp_ad_page']) ? $instance['mtp_ad_page'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('mtp_ad_page'); ?>"><?php _e('Page'); ?></label><br>
			<input id="<?php echo $this->get_field_id('mtp_ad_post'); ?>" name="<?php echo $this->get_field_name('mtp_ad_post'); ?>" type="checkbox" <?php checked(isset($instance['mtp_ad_post']) ? $instance['mtp_ad_post'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('mtp_ad_post'); ?>"><?php _e('Post'); ?></label><br>
			<input id="<?php echo $this->get_field_id('mtp_ad_tag'); ?>" name="<?php echo $this->get_field_name('mtp_ad_tag'); ?>" type="checkbox" <?php checked(isset($instance['mtp_ad_tag']) ? $instance['mtp_ad_tag'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('mtp_ad_tag'); ?>"><?php _e('Tag'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('mtp_ad_position'); ?>"><?php _e('Position:') ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('mtp_ad_position'); ?>" name="<?php echo $this->get_field_name('mtp_ad_position'); ?>">
				<?php
				$position = array(
					'before_html'        => 'Background',
					'before_title'       => 'Before Site Title',
					'after_title'        => 'After Site Title',
					'byline_item'        => 'After Post Headline',
					'before_content'     => 'Before Content',
					'before_sidebars'    => 'Before Sidebars',
					'after_post'         => 'After Post',
					'before_footer'      => 'Before Footer',
					'between_paragraphs' => 'Between Paragraphs',
					'after_post_box'     => 'Between Posts',
					'after_teasers_box'  => 'Between Teasers',
					'sidebar'            => 'Sidebar'
				);
				foreach ($position as $key => $value) {
					$selected = $mtp_ad_position == $key ? 'selected="selected"' :'';
					echo '<option value="'.esc_attr($key).'" '.$selected.'>'.$value.'</option>';
				}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('mtp_ad_number'); ?>"><?php _e('After Number:') ?></label>
			<input class="small-text" id="<?php echo $this->get_field_id('mtp_ad_number'); ?>" max="10" min="1" name="<?php echo $this->get_field_name('mtp_ad_number'); ?>" type="number" value="<?php echo $mtp_ad_number; ?>" />
		</p>
	<?php }

	// Widget Update
	public function update($new_instance,$old_instance)
	{
		$instance = $old_instance;

		if (current_user_can('unfiltered_html'))
		{
			$instance['mtp_ad'] = $new_instance['mtp_ad'];
		}
		else
		{
			$instance['mtp_ad'] = stripslashes(wp_filter_post_kses(addslashes($new_instance['mtp_ad'])));
		}

		$instance['mtp_ad_category'] = isset($new_instance['mtp_ad_category']);
		$instance['mtp_ad_home'] = isset($new_instance['mtp_ad_home']);
		$instance['mtp_ad_page'] = isset($new_instance['mtp_ad_page']);
		$instance['mtp_ad_post'] = isset($new_instance['mtp_ad_post']);
		$instance['mtp_ad_tag'] = isset($new_instance['mtp_ad_tag']);
		$instance['mtp_ad_position'] = stripslashes($new_instance['mtp_ad_position']);
		$instance['mtp_ad_position'] = stripslashes($new_instance['mtp_ad_position']);
		$instance['mtp_ad_number'] = (int) $new_instance['mtp_ad_number'];

		return $instance;
	}

	// Displaying The Widget
	public function widget($args,$instance)
	{
		extract($args);

		echo $before_widget.'<div class="mtp-ad">'.$instance['mtp_ad'].'</div>'.$after_widget;
	}

	// Thesis Hook Before HTML
	public function mtp_ad_thbhtml()
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$pbhtml_true       = $value['mtp_ad_position'] == 'before_html';
			$pbhtml_arr_search = array_search($pbhtml_true,$instance);

			$cat_true  = $value['mtp_ad_category'] == true;
			$home_true = $value['mtp_ad_home'] == true;
			$page_true = $value['mtp_ad_page'] == true;
			$post_true = $value['mtp_ad_post'] == true;
			$tag_true  = $value['mtp_ad_tag'] == true;

			$cat_arr_search  = array_search($cat_true,$instance);
			$home_arr_search = array_search($home_true,$instance);
			$page_arr_search = array_search($page_true,$instance);
			$post_arr_search = array_search($post_true,$instance);
			$tag_arr_search  = array_search($tag_true,$instance);

			if ($pbhtml_arr_search == true && $cat_arr_search  == true && is_category() ||
				$pbhtml_arr_search == true && $home_arr_search == true && is_home() ||
				$pbhtml_arr_search == true && $home_arr_search == true && is_front_page() ||
				$pbhtml_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
				$pbhtml_arr_search == true && $post_arr_search == true && is_single() ||
				$pbhtml_arr_search == true && $tag_arr_search  == true && is_tag())
			{
				echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
			}
		}
	}

	// Thesis Hook Before Title
	public function mtp_ad_thbt()
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$pbt_true = $value['mtp_ad_position'] == 'before_title';
			$pbt_arr_search = array_search($pbt_true,$instance);

			$cat_true  = $value['mtp_ad_category'] == true;
			$home_true = $value['mtp_ad_home'] == true;
			$page_true = $value['mtp_ad_page'] == true;
			$post_true = $value['mtp_ad_post'] == true;
			$tag_true  = $value['mtp_ad_tag'] == true;

			$cat_arr_search  = array_search($cat_true,$instance);
			$home_arr_search = array_search($home_true,$instance);
			$page_arr_search = array_search($page_true,$instance);
			$post_arr_search = array_search($post_true,$instance);
			$tag_arr_search  = array_search($tag_true,$instance);

			if ($pbt_arr_search == true && $cat_arr_search  == true && is_category() ||
				$pbt_arr_search == true && $home_arr_search == true && is_home() ||
				$pbt_arr_search == true && $home_arr_search == true && is_front_page() ||
				$pbt_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
				$pbt_arr_search == true && $post_arr_search == true && is_single() ||
				$pbt_arr_search == true && $tag_arr_search  == true && is_tag())
			{
				echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
			}
		}
	}

	// Thesis Hook After Title
	public function mtp_ad_that()
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$pat_true = $value['mtp_ad_position'] == 'after_title';
			$pat_arr_search = array_search($pat_true,$instance);

			$cat_true  = $value['mtp_ad_category'] == true;
			$home_true = $value['mtp_ad_home'] == true;
			$page_true = $value['mtp_ad_page'] == true;
			$post_true = $value['mtp_ad_post'] == true;
			$tag_true  = $value['mtp_ad_tag'] == true;

			$cat_arr_search  = array_search($cat_true,$instance);
			$home_arr_search = array_search($home_true,$instance);
			$page_arr_search = array_search($page_true,$instance);
			$post_arr_search = array_search($post_true,$instance);
			$tag_arr_search  = array_search($tag_true,$instance);

			if ($pat_arr_search == true && $cat_arr_search  == true && is_category() ||
				$pat_arr_search == true && $home_arr_search == true && is_home() ||
				$pat_arr_search == true && $home_arr_search == true && is_front_page() ||
				$pat_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
				$pat_arr_search == true && $post_arr_search == true && is_single() ||
				$pat_arr_search == true && $tag_arr_search  == true && is_tag())
			{
				echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
			}
		}
	}

	// Thesis Hook Before Content
	public function mtp_ad_thbc()
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$pbc_true = $value['mtp_ad_position'] == 'before_content';
			$pbc_arr_search = array_search($pbc_true,$instance);

			$cat_true  = $value['mtp_ad_category'] == true;
			$home_true = $value['mtp_ad_home'] == true;
			$page_true = $value['mtp_ad_page'] == true;
			$post_true = $value['mtp_ad_post'] == true;
			$tag_true  = $value['mtp_ad_tag'] == true;

			$cat_arr_search  = array_search($cat_true,$instance);
			$home_arr_search = array_search($home_true,$instance);
			$page_arr_search = array_search($page_true,$instance);
			$post_arr_search = array_search($post_true,$instance);
			$tag_arr_search  = array_search($tag_true,$instance);

			if ($pbc_arr_search == true && $cat_arr_search  == true && is_category() ||
				$pbc_arr_search == true && $home_arr_search == true && is_home() ||
				$pbc_arr_search == true && $home_arr_search == true && is_front_page() ||
				$pbc_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
				$pbc_arr_search == true && $post_arr_search == true && is_single() ||
				$pbc_arr_search == true && $tag_arr_search  == true && is_tag())
			{
				echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
			}
		}
	}

	// Thesis Hook Byline Item
	public function mtp_ad_thbi()
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$pbi_true = $value['mtp_ad_position'] == 'byline_item';
			$pbi_arr_search = array_search($pbi_true,$instance);

			$cat_true  = $value['mtp_ad_category'] == true;
			$home_true = $value['mtp_ad_home'] == true;
			$page_true = $value['mtp_ad_page'] == true;
			$post_true = $value['mtp_ad_post'] == true;
			$tag_true  = $value['mtp_ad_tag'] == true;

			$cat_arr_search  = array_search($cat_true,$instance);
			$home_arr_search = array_search($home_true,$instance);
			$page_arr_search = array_search($page_true,$instance);
			$post_arr_search = array_search($post_true,$instance);
			$tag_arr_search  = array_search($tag_true,$instance);

			if ($pbi_arr_search == true && $cat_arr_search  == true && is_category() ||
				$pbi_arr_search == true && $home_arr_search == true && is_home() ||
				$pbi_arr_search == true && $home_arr_search == true && is_front_page() ||
				$pbi_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
				$pbi_arr_search == true && $post_arr_search == true && is_single() ||
				$pbi_arr_search == true && $tag_arr_search  == true && is_tag())
			{
				echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
			}
		}
	}

	// Thesis Hook Before Sidebars
	public function mtp_ad_thbs()
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$pbs_true = $value['mtp_ad_position'] == 'before_sidebars';
			$pbs_arr_search = array_search($pbs_true,$instance);

			$cat_true  = $value['mtp_ad_category'] == true;
			$home_true = $value['mtp_ad_home'] == true;
			$page_true = $value['mtp_ad_page'] == true;
			$post_true = $value['mtp_ad_post'] == true;
			$tag_true  = $value['mtp_ad_tag'] == true;

			$cat_arr_search  = array_search($cat_true,$instance);
			$home_arr_search = array_search($home_true,$instance);
			$page_arr_search = array_search($page_true,$instance);
			$post_arr_search = array_search($post_true,$instance);
			$tag_arr_search  = array_search($tag_true,$instance);

			if ($pbs_arr_search == true && $cat_arr_search  == true && is_category() ||
				$pbs_arr_search == true && $home_arr_search == true && is_home() ||
				$pbs_arr_search == true && $home_arr_search == true && is_front_page() ||
				$pbs_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
				$pbs_arr_search == true && $post_arr_search == true && is_single() ||
				$pbs_arr_search == true && $tag_arr_search  == true && is_tag())
			{
				echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
			}
		}
	}

	// Thesis Hook After Post
	public function mtp_ad_thap()
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$pap_true = $value['mtp_ad_position'] == 'after_post';
			$pap_arr_search = array_search($pap_true,$instance);

			$cat_true  = $value['mtp_ad_category'] == true;
			$home_true = $value['mtp_ad_home'] == true;
			$page_true = $value['mtp_ad_page'] == true;
			$post_true = $value['mtp_ad_post'] == true;
			$tag_true  = $value['mtp_ad_tag'] == true;

			$cat_arr_search  = array_search($cat_true,$instance);
			$home_arr_search = array_search($home_true,$instance);
			$page_arr_search = array_search($page_true,$instance);
			$post_arr_search = array_search($post_true,$instance);
			$tag_arr_search  = array_search($tag_true,$instance);

			if ($pap_arr_search == true && $cat_arr_search  == true && is_category() ||
				$pap_arr_search == true && $home_arr_search == true && is_home() ||
				$pap_arr_search == true && $home_arr_search == true && is_front_page() ||
				$pap_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
				$pap_arr_search == true && $post_arr_search == true && is_single() ||
				$pap_arr_search == true && $tag_arr_search  == true && is_tag())
			{
				echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
			}
		}
	}

	// Thesis Hook Before Footer
	public function mtp_ad_thbf()
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$pbf_true = $value['mtp_ad_position'] == 'before_footer';
			$pbf_arr_search = array_search($pbf_true,$instance);

			$cat_true  = $value['mtp_ad_category'] == true;
			$home_true = $value['mtp_ad_home'] == true;
			$page_true = $value['mtp_ad_page'] == true;
			$post_true = $value['mtp_ad_post'] == true;
			$tag_true  = $value['mtp_ad_tag'] == true;

			$cat_arr_search  = array_search($cat_true,$instance);
			$home_arr_search = array_search($home_true,$instance);
			$page_arr_search = array_search($page_true,$instance);
			$post_arr_search = array_search($post_true,$instance);
			$tag_arr_search  = array_search($tag_true,$instance);

			if ($pbf_arr_search == true && $cat_arr_search  == true && is_category() ||
				$pbf_arr_search == true && $home_arr_search == true && is_home() ||
				$pbf_arr_search == true && $home_arr_search == true && is_front_page() ||
				$pbf_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
				$pbf_arr_search == true && $post_arr_search == true && is_single() ||
				$pbf_arr_search == true && $tag_arr_search  == true && is_tag())
			{
				echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
			}
		}
	}

	// Custom Thesis Hook Between Paragraphs
	public function mtp_ad_cthbp($content)
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$mtp_ad_number = $value['mtp_ad_number'];

			$pbp_true = $value['mtp_ad_position'] == 'between_paragraphs';
			$pbp_arr_search = array_search($pbp_true,$instance);

			$cat_true  = $value['mtp_ad_category'] == true;
			$home_true = $value['mtp_ad_home'] == true;
			$page_true = $value['mtp_ad_page'] == true;
			$post_true = $value['mtp_ad_post'] == true;
			$tag_true  = $value['mtp_ad_tag'] == true;

			$cat_arr_search  = array_search($cat_true,$instance);
			$home_arr_search = array_search($home_true,$instance);
			$page_arr_search = array_search($page_true,$instance);
			$post_arr_search = array_search($post_true,$instance);
			$tag_arr_search  = array_search($tag_true,$instance);

			if ($pbp_arr_search == true && $cat_arr_search  == true && is_category() ||
				$pbp_arr_search == true && $home_arr_search == true && is_home() ||
				$pbp_arr_search == true && $home_arr_search == true && is_front_page() ||
				$pbp_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
				$pbp_arr_search == true && $post_arr_search == true && is_single() ||
				$pbp_arr_search == true && $tag_arr_search  == true && is_tag())
			{
				$content = explode('</p>',$content);
				$content[$mtp_ad_number] .= '</p><p><div class="mtp-ad">'.$value['mtp_ad'].'</div>';
				$content = implode($content,'</p>');
			}
		}

		return $content;
	}

	// Thesis Hook After Post Box
	public function mtp_ad_thapb($post_count)
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$mtp_ad_number = $value['mtp_ad_number'];

			if ($post_count == $mtp_ad_number)
			{
				$papb_true = $value['mtp_ad_position'] == 'after_post_box';
				$papb_arr_search = array_search($papb_true,$instance);

				$cat_true  = $value['mtp_ad_category'] == true;
				$home_true = $value['mtp_ad_home'] == true;
				$page_true = $value['mtp_ad_page'] == true;
				$post_true = $value['mtp_ad_post'] == true;
				$tag_true  = $value['mtp_ad_tag'] == true;

				$cat_arr_search  = array_search($cat_true,$instance);
				$home_arr_search = array_search($home_true,$instance);
				$page_arr_search = array_search($page_true,$instance);
				$post_arr_search = array_search($post_true,$instance);
				$tag_arr_search  = array_search($tag_true,$instance);

				if ($papb_arr_search == true && $cat_arr_search  == true && is_category() ||
					$papb_arr_search == true && $home_arr_search == true && is_home() ||
					$papb_arr_search == true && $home_arr_search == true && is_front_page() ||
					$papb_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
					$papb_arr_search == true && $post_arr_search == true && is_single() ||
					$papb_arr_search == true && $tag_arr_search  == true && is_tag())
				{
					echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
				}
			}
		}
	}

	// Thesis Hook After Teasers Box
	public function mtp_ad_thatb($post_count)
	{
		$instance = get_option($this->option_name);
		array_pop($instance);

		foreach ($instance as $key => $value)
		{
			$mtp_ad_number = $value['mtp_ad_number'];

			if ($post_count == $mtp_ad_number)
			{
				$patb_true = $value['mtp_ad_position'] == 'after_teasers_box';
				$patb_arr_search = array_search($patb_true,$instance);

				$cat_true  = $value['mtp_ad_category'] == true;
				$home_true = $value['mtp_ad_home'] == true;
				$page_true = $value['mtp_ad_page'] == true;
				$post_true = $value['mtp_ad_post'] == true;
				$tag_true  = $value['mtp_ad_tag'] == true;

				$cat_arr_search  = array_search($cat_true,$instance);
				$home_arr_search = array_search($home_true,$instance);
				$page_arr_search = array_search($page_true,$instance);
				$post_arr_search = array_search($post_true,$instance);
				$tag_arr_search  = array_search($tag_true,$instance);

				if ($patb_arr_search == true && $cat_arr_search  == true && is_category() ||
					$patb_arr_search == true && $home_arr_search == true && is_home() ||
					$patb_arr_search == true && $home_arr_search == true && is_front_page() ||
					$patb_arr_search == true && $page_arr_search == true && is_page() && !is_front_page() ||
					$patb_arr_search == true && $post_arr_search == true && is_single() ||
					$patb_arr_search == true && $tag_arr_search  == true && is_tag())
				{
					echo '<div class="mtp-ad">'.$value['mtp_ad'].'</div>';
				}
			}
		}
	}
}

// Registering The Sidebar
function mtp_ad_after_setup_theme()
{
	register_sidebar(array(
		'name'          => __('Ads Area','mtp_ad'),
		'id'            => 'mtp-ad',
		'description'   => __('Use this sidebar together with the Ad Widget to place your ads on different positions inside your website.','mtp_ad'),
		'class'         => '',
		'before_widget' => '<div class="mtp-ad widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="mtp-ad widget-title">',
		'after_title'   => '</h3>',
	));
}

add_action('after_setup_theme','mtp_ad_after_setup_theme');

// Registering The Widget
function mtp_ad_init()
{
	register_widget('mtp_ad');
}

add_action('init','mtp_ad_init',1);

?>