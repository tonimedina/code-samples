<?php
class mtp_slider_custom_post_type
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init()
	{
		$this->actions();
		$this->filters();
		$this->custom_post_type();
	}

	public function actions()
	{
		add_action( 'admin_init', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
	}

	public function filters()
	{
		add_filter( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ) );
	}

	public function custom_post_type()
	{
		register_post_type( 'slide', array(
			'labels'              => array(
				'name'               => _x( 'Slides', 'slide' ),
				'singular_name'      => _x( 'Slide', 'slide' ),
				'add_new'            => _x( 'Add New', 'slide' ),
				'add_new_item'       => _x( 'Add New Slide', 'slide' ),
				'edit_item'          => _x( 'Edit Slide', 'slide' ),
				'new_item'           => _x( 'New Slide', 'slide' ),
				'view_item'          => _x( 'View Slide', 'slide' ),
				'search_items'       => _x( 'Search Slides', 'slide' ),
				'not_found'          => _x( 'No slides found', 'slide' ),
				'not_found_in_trash' => _x( 'No slides found in Trash', 'slide' ),
				'parent_item_colon'  => _x( 'Parent Slide:', 'slide' ),
				'menu_name'          => _x( 'Slides', 'slide' )
			),
			'hierarchical'        => false,
			'description'         => _x( 'Slides Custom Post Type', 'slide' ),
			'supports'            => array( 'title', 'excerpt', 'author', 'thumbnail', 'revisions' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => true,
			'capability_type'     => 'post'
		) );
	}

	public function add_meta_boxes()
	{
		add_meta_box( 'slide_meta', _x( 'Slide Meta', 'slide' ), array( $this, 'slide_meta_cb' ), 'slide' );
	}

	public function slide_meta_cb( $post )
	{
		$slide_link = esc_url( get_post_meta( $post->ID, 'slide_link', true ) );

		wp_nonce_field( plugin_basename( __FILE__ ), 'slide_meta_cb_nonce' );
		?>
		<p>
			<label for="slide_link"><?php _e( 'Slide Link', 'slide' ); ?></label>
			<input class="code widefat" id="slide_link" name="slide_link" placeholder="Don't forget the http://" type="url" value="<?php echo $slide_link; ?>">
		</p>
		<?php
	}

	public function save_meta_boxes( $post_id )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !wp_verify_nonce( $_POST['slide_meta_cb_nonce'], plugin_basename( __FILE__ ) ) )
			return;

		if ( $_POST['post_type'] == 'slide' )
		{
			if ( !current_user_can( 'edit_page', $post_id ) )
				return;
		}
		else
		{
			if ( !current_user_can( 'edit_post', $post_id ) )
				return;
		}

		update_post_meta( $post_id, 'slide_link', $_POST['slide_link'] );
	}

	public function remove_meta_boxes()
	{
		if ( wp_get_theme() == 'Thesis' )
		{
			remove_meta_box( 'thesis_meta_keywords', 'slide', 'normal' );
			remove_meta_box( 'thesis_meta_robots', 'slide', 'normal' );
			remove_meta_box( 'thesis_canonical_link', 'slide', 'normal' );
			remove_meta_box( 'thesis_html_body', 'slide', 'normal' );
			remove_meta_box( 'thesis_meta_description', 'slide', 'normal' );
			remove_meta_box( 'thesis_post_content', 'slide', 'normal' );
			remove_meta_box( 'thesis_post_image', 'slide', 'normal' );
			remove_meta_box( 'thesis_post_thumbnail', 'slide', 'normal' );
			remove_meta_box( 'thesis_redirect', 'slide', 'normal' );
			remove_meta_box( 'thesis_title_tag', 'slide', 'normal' );
		}
	}
}

new mtp_slider_custom_post_type();