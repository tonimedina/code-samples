<?php
/*
Plugin Name: MTP No 'Category/Tag' Base
Plugin URI: http://mt-performance.net
Description: Removes '/category' and '/tag' from your category/tag permalinks.
Version: 1.0
Author: Toni Medina
Author URI: http://tonimedina.me
License: GPL2
*/

/**
 * MTP No Category Base
 *
 * Check This Site For More Information
 * http://wordpress.org/extend/plugins/wp-no-category-base/
 */
class mtp_no_category_base
{
	public function __construct()
	{
		$this->hooks();
		$this->actions();
		$this->filters();
	}

	public function hooks()
	{
		register_activation_hook(__FILE__,array($this,'refresh_rules'));
		register_deactivation_hook(__FILE__,array($this,'deactivate'));
	}

	public function actions()
	{
		add_action('created_category',array($this,'refresh_rules'));
		add_action('edited_category',array($this,'refresh_rules'));
		add_action('delete_category',array($this,'refresh_rules'));
		add_action('init',array($this,'permastruct'));
	}

	public function filters()
	{
		add_filter('category_rewrite_rules',array($this,'rewrite_rules'));
		add_filter('query_vars',array($this,'query_vars'));
		add_filter('request',array($this,'request'));
	}

	/**
	 * [refresh_rules Refresh Rules On Activation/Category Changes]
	 * @return [array] [rewrite_rules]
	 */
	public function refresh_rules()
	{
		global $wp_rewrite;

		$wp_rewrite->flush_rules();
	}

	/**
	 * [deactivate Refresh Rules On Deactivation/Category Changes]
	 * @return [array] [rewrite_rules]
	 */
	public function deactivate()
	{
		remove_filter('category_rewrite_rules',array($this,'rewrite_rules'));
		
		$this->refresh_rules();
	}

	/**
	 * [permastruct Remove Category Base]
	 * @return [array] [description]
	 */
	public function permastruct()
	{
		global $wp_rewrite, $wp_version;

		if (version_compare($wp_version,'3.4','<'))
		{
			$wp_rewrite->extra_permastructs['category'][0] = '%category%';
		}
		else
		{
			$wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
		}
	}

	/**
	 * [rewrite_rules Add Our Custom Category Rewrite Rules]
	 * @param  [array] $category_rewrite [rewrite_rules]
	 * @return [array]                   [rewrite_rules]
	 */
	public function rewrite_rules($category_rewrite)
	{
		global $wp_rewrite;

		$category_rewrite = array();
		$categories = get_categories(array(
			'hide_empty' => false
		));

		foreach ($categories as $category)
		{
			$slug   = $category->slug;
			$parent = $category->parent;
			$id     = $category->cat_ID;

			if ($parent == $id)
			{
				$parent = 0;
			}
			elseif ($parent != 0)
			{
				$slug = get_category_parents($parent,false,'/',true).$slug;
			}
				
			$category_rewrite['('.$slug.')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
			$category_rewrite['('.$slug.')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
			$category_rewrite['('.$slug.')/?$'] = 'index.php?category_name=$matches[1]';
		}

		$old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
		$old_category_base = trim($old_category_base, '/');
		$category_rewrite[$old_category_base.'/(.*)$'] = 'index.php?category_redirect=$matches[1]';

		return $category_rewrite;
	}

	/**
	 * [query_vars Add 'category_redirect' Query Variable]
	 * @param  [array] $public_query_vars [description]
	 * @return [array]                    [description]
	 */
	public function query_vars($public_query_vars)
	{
		$public_query_vars[] = 'category_redirect';

		return $public_query_vars;
	}

	/**
	 * [request Redirect If 'category_redirect' Is Set]
	 * @param  [array] $query_vars [description]
	 * @return [array]             [description]
	 */
	public function request($query_vars)
	{
		if (isset($query_vars['category_redirect']))
		{
			$link = trailingslashit(get_option('home')).user_trailingslashit($query_vars['category_redirect'],'category');

			status_header(301);

			header('Location: '.$link);

			exit();
		}

		return $query_vars;
	}
}

new mtp_no_category_base();

/**
 * MTP No Tag Base
 *
 * Check This Site For More Information
 * http://wordpress.org/extend/plugins/wp-no-tag-base/
 */
class mtp_no_tag_base
{
	public function __construct()
	{
		$this->hooks();
		$this->actions();
		$this->filters();
	}

	public function hooks()
	{
		register_activation_hook(__FILE__,array($this,'refresh_rules'));
		register_deactivation_hook(__FILE__,array($this,'deactivate'));
	}

	public function actions()
	{
		add_action('created_post_tag',array($this,'refresh_rules'));
		add_action('edited_post_tag',array($this,'refresh_rules'));
		add_action('delete_post_tag',array($this,'refresh_rules'));
		add_action('init',array($this,'permastruct'));
	}

	public function filters()
	{
		add_filter('tag_rewrite_rules',array($this,'rewrite_rules'));
		add_filter('query_vars',array($this,'query_vars'));
		add_filter('request',array($this,'request'));
	}

	/**
	 * [refresh_rules Refresh Rules On Activation/Tag Changes]
	 * @return [array] [rewrite_rules]
	 */
	public function refresh_rules()
	{
		global $wp_rewrite;

		$wp_rewrite->flush_rules();
	}

	/**
	 * [deactivate Refresh Rules On Deactivation/Tag Changes]
	 * @return [array] [rewrite_rules]
	 */
	public function deactivate()
	{
		remove_filter('tag_rewrite_rules',array($this,'rewrite_rules'));

		$this->refresh_rules();
	}

	/**
	 * [permastruct Remove Tag Base]
	 * @return [array] [description]
	 */
	public function permastruct()
	{
		global $wp_rewrite, $wp_version;

		if (version_compare($wp_version, '3.4', '<'))
		{
			$wp_rewrite->extra_permastructs['post_tag'][0] = '%post_tag%';
		}
		else
		{
			$wp_rewrite->extra_permastructs['post_tag']['struct'] = '%post_tag%';
		}
	}

	/**
	 * [rewrite_rules Add Our Custom Tag Rewrite Rules]
	 * @param  [array] $tag_rewrite [rewrite_rules]
	 * @return [array]              [rewrite_rules]
	 */
	public function rewrite_rules($tag_rewrite)
	{
		global $wp_rewrite;

		$tag_rewrite = array();
		$tags = get_tags(array(
			'hide_empty'=>false
		));

		foreach($tags as $tag)
		{
			$slug   = $tag->slug;
			$parent = $tag->parent;
			$id     = $tag->tag_id;

			if ($parent == $id)
			{
				$parent = 0;
			}

			$tag_rewrite['('.$slug.')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?tag=$matches[1]&feed=$matches[2]';
			$tag_rewrite['('.$slug.')/page/?([0-9]{1,})/?$'] = 'index.php?tag=$matches[1]&paged=$matches[2]';
			$tag_rewrite['('.$slug.')/?$'] = 'index.php?tag=$matches[1]';
		}
		
		$old_tag_base = get_option('tag_base') ? get_option('tag_base') : 'tag';
		$old_tag_base = trim($old_tag_base, '/');
		$tag_rewrite[$old_tag_base . '/(.*)$'] = 'index.php?tag_redirect=$matches[1]';

		return $tag_rewrite;
	}

	/**
	 * [query_vars Add 'tag_redirect' Query Variable]
	 * @param  [array] $public_query_vars [description]
	 * @return [array]                    [description]
	 */
	public function query_vars($public_query_vars)
	{
		$public_query_vars[] = 'tag_redirect';

		return $public_query_vars;
	}

	/**
	 * [request Redirect If 'tag_redirect' Is Set]
	 * @param  [array] $query_vars [description]
	 * @return [array]             [description]
	 */
	public function request($query_vars)
	{
		if (isset($query_vars['tag_redirect']))
		{
			$tag  = user_trailingslashit($query_vars['tag_redirect'],'post_tag');
			$link = trailingslashit(get_option('home')).$tag;

			status_header(301);

			header('Location: '.$link);

			exit();
		}

		return $query_vars;
	}
}

new mtp_no_tag_base();